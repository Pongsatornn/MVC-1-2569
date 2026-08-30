<?php
class Ballot {
    public $id;
    public $voterId;
    public $ranking;   
    public $status;    
    public $groupId;  

    function __construct($id, $voterId, $ranking) {
        $this->id = $id;
        $this->voterId = $voterId;
        $this->ranking = $ranking;
        $this->status = 'บันทึกแล้ว';
        $this->groupId = null;
    }
}
