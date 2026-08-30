<?php
// Model: ผู้มีสิทธิ์เลือกตั้ง
class Voter {
    public $id;
    public $name;
    public $active;  
    public $voted;   

    function __construct($id, $name, $active) {
        $this->id = $id;
        $this->name = $name;
        $this->active = $active;
        $this->voted = false;
    }
}
