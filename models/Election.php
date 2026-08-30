<?php
class Election {
    public $id;
    public $title;
    public $status;    
    public $points;    
    public $dupLimit;  

    public $candidates = []; 
    public $voters = [];     
    public $ballots = [];  
    public $groups = [];     

    private $lastBallotNo = 0;
    private $lastGroupNo = 0;
    static function load($data) {
        $election = new Election();
        $election->id = $data['election']['id'];
        $election->title = $data['election']['title'];
        $election->status = $data['election']['status'];
        $election->points = $data['election']['ranking_points'];
        $election->dupLimit = $data['election']['duplicate_pattern_threshold'];

        foreach ($data['candidates'] as $c) {
            $election->candidates[$c['id']] = new Candidate($c['id'], $c['name']);
        }
        foreach ($data['voters'] as $v) {
            $election->voters[$v['id']] = new Voter($v['id'], $v['name'], $v['active']);
        }
        foreach ($data['ballots'] as $b) {
            $election->ballots[$b['id']] = new Ballot($b['id'], $b['voter_id'], $b['ranking']);
            $election->voters[$b['voter_id']]->voted = true;

            $no = intval(substr($b['id'], 1));
            if ($no > $election->lastBallotNo) $election->lastBallotNo = $no;
        }
        return $election;
    }

    function getCandidates() {
        return $this->candidates;
    }
    function vote($voterId, $ranking) {
        if ($this->status !== 'OPEN') {
            return 'การเลือกตั้งไม่ได้อยู่ในสถานะเปิดรับคะแนนแล้ว';
        }
        $voter = $this->voters[$voterId];
        if (!$voter->active) {
            return 'ผู้ใช้นี้ไม่มีสิทธิ์เลือกตั้ง';
        }
        if ($voter->voted) {
            return 'ผู้ใช้นี้ลงคะแนนไปแล้ว ลงซ้ำไม่ได้';
        }
        if (count(array_unique($ranking)) !== 3) {
            return 'ต้องจัดอันดับผู้สมัคร 3 คนที่ไม่ซ้ำกัน';
        }

        $this->lastBallotNo++;
        $id = 'B' . str_pad($this->lastBallotNo, 2, '0', STR_PAD_LEFT);
        $this->ballots[$id] = new Ballot($id, $voterId, $ranking);
        $voter->voted = true;
        return null;
    }
       
    function hasPendingGroup() {
        foreach ($this->groups as $g) {
            if ($g->status === 'รอตรวจสอบ') return true;
        }
        return false;
    }
    function close() {
        $this->status = 'ปิดรับคะแนนแล้ว';
        $patterns = []; 
        foreach ($this->ballots as $b) {
            $key = implode('>', $b->ranking);
            $patterns[$key][] = $b->id;
        }
        foreach ($patterns as $key => $ballotIds) {
            if (count($ballotIds) >= $this->dupLimit) {
                $this->lastGroupNo++;
                $groupId = 'G' . str_pad($this->lastGroupNo, 2, '0', STR_PAD_LEFT);
                $this->groups[$groupId] = new PatternGroup($groupId, explode('>', $key), $ballotIds);
                foreach ($ballotIds as $ballotId) {
                    $this->ballots[$ballotId]->status = 'รอตรวจสอบ';
                    $this->ballots[$ballotId]->groupId = $groupId;
                }
            } else {
                foreach ($ballotIds as $ballotId) {
                    $this->ballots[$ballotId]->status = 'รับรอง';
                }
            }
        }
    }
 
    function decideGroup($groupId, $result) {
        $group = $this->groups[$groupId];
        $group->decide($result);
        foreach ($group->ballotIds as $ballotId) {
            $this->ballots[$ballotId]->status = $result;
        }
    }

    function reopen() {
        if ($this->status === 'OPEN') {
            return 'การเลือกตั้งเปิดรับคะแนนอยู่แล้ว';
        }
        $this->status = 'OPEN';
        $this->groups = [];
        $this->lastGroupNo = 0;
        foreach ($this->ballots as $b) {
            $b->status = 'บันทึกแล้ว';
            $b->groupId = null;
        }
        return null;
    }

    function finish() {
        $this->status = 'สรุปผลแล้ว';
    }
    function countScore() {
        $score = [];
        foreach ($this->candidates as $c) {
            $score[$c->id] = 0;
        }
        foreach ($this->ballots as $b) {
            if ($b->status === 'รอตรวจสอบ' || $b->status === 'ไม่นับ') continue;
            foreach ($b->ranking as $i => $candidateId) {
                $score[$candidateId] += $this->points[$i];
            }
        }
        return $score;
    }
    function getSummary() {
        if ($this->status === 'OPEN') {
            return [
                'phase' => 'OPEN',
                'ballots' => count($this->ballots),
            ];
        }
        if ($this->status === 'ปิดรับคะแนนแล้ว') {
            $pending = array_filter($this->groups, function ($g) {
                return $g->status === 'รอตรวจสอบ';
            });
            return [
                'phase' => 'CLOSED',
                'pending' => count($pending),
                'score' => $this->countScore(),
            ];
        }

        $counted = 0;
        $rejected = 0;
        foreach ($this->ballots as $b) {
            if ($b->status === 'ไม่นับ') $rejected++;
            else $counted++;
        }
        return [
            'phase' => 'DONE',
            'score' => $this->countScore(),
            'counted' => $counted,
            'rejected' => $rejected,
        ];
    }
}
