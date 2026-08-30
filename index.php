<?php
require_once __DIR__ . '/config.php';
$votingController = new VotingController($election);
$adminController = new AdminController($election);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'vote') {
        $ranking = [$_POST['rank1'], $_POST['rank2'], $_POST['rank3']];
        $error = $votingController->vote($_POST['voter_id'], $ranking);
        $_SESSION['election'] = $election;
        if ($error) {
            header('Location: index.php?page=voting&error=' . urlencode($error));
        } else {
            header('Location: index.php?page=voting&voted=1');
        }
        exit;
    }

    if ($action === 'close') {
        $adminController->close();
        $_SESSION['election'] = $election;
        header('Location: index.php?page=admin');
        exit;
    }

    if ($action === 'reopen') {
        $error = $adminController->reopen();
        $_SESSION['election'] = $election;
        if ($error) {
            header('Location: index.php?page=admin&error=' . urlencode($error));
        } else {
            header('Location: index.php?page=admin');
        }
        exit;
    }

    if ($action === 'decide') {
        $adminController->decideGroup($_POST['group_id'], $_POST['result']);
        $_SESSION['election'] = $election;
        header('Location: index.php?page=admin');
        exit;
    }

    if ($action === 'finish') {
        $adminController->finish();
        $_SESSION['election'] = $election;
        header('Location: index.php?page=status');
        exit;
    }
}

$page = $_GET['page'] ?? 'voting';

if ($page === 'admin') {
    include __DIR__ . '/views/admin.php';
} elseif ($page === 'status') {
    include __DIR__ . '/views/status.php';
} else {
    include __DIR__ . '/views/voting.php';
}
