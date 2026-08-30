<?php
class PatternGroup {
    public $id;
    public $pattern;    
    public $ballotIds;  
    public $status;    
    function __construct($id, $pattern, $ballotIds) {
        $this->id = $id;
        $this->pattern = $pattern;
        $this->ballotIds = $ballotIds;
        $this->status = 'รอตรวจสอบ';
    }

    function decide($result) {
        $this->status = $result;
    }
}
