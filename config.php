<?php
require_once __DIR__ . '/models/Candidate.php';
require_once __DIR__ . '/models/Voter.php';
require_once __DIR__ . '/models/Ballot.php';
require_once __DIR__ . '/models/PatternGroup.php';
require_once __DIR__ . '/models/Election.php';
require_once __DIR__ . '/controllers/VotingController.php';
require_once __DIR__ . '/controllers/AdminController.php';
session_start();
$dataVersion = 2;
$needReset = !isset($_SESSION['election'])
    || ($_SESSION['data_version'] ?? 0) !== $dataVersion
    || isset($_GET['reset']);

if ($needReset) {
    $json = file_get_contents(__DIR__ . '/seed_data.json');
    $data = json_decode($json, true);
    $_SESSION['election'] = Election::load($data);
    $_SESSION['data_version'] = $dataVersion;
}

$election = $_SESSION['election'];
