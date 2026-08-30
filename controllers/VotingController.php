<?php
class VotingController {
    private $election;

    function __construct($election) {
        $this->election = $election;
    }

    function getCandidates() {
        return $this->election->getCandidates();
    }

    function vote($voterId, $ranking) {
        return $this->election->vote($voterId, $ranking);
    }
}
