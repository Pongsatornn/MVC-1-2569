<?php
class AdminController {
    private $election;

    function __construct($election) {
        $this->election = $election;
    }

    function close() {
        $this->election->close();
    }

    function decideGroup($groupId, $result) {
        $this->election->decideGroup($groupId, $result);
    }

    function finish() {
        $this->election->finish();
    }
}
