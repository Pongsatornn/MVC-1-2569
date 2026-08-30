<?php
/** @var Election $election */
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($election->title) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark mb-4">
  <div class="container">
    <span class="navbar-brand"><?= htmlspecialchars($election->title) ?></span>
    <div class="navbar-nav me-auto">
      <a class="nav-link" href="index.php?page=voting">ลงคะแนน</a>
      <a class="nav-link" href="index.php?page=admin">เจ้าหน้าที่</a>
      <a class="nav-link" href="index.php?page=status">สถานะ/ผล</a>
    </div>
    <span class="badge bg-secondary">สถานะ: <?= htmlspecialchars($election->status) ?></span>
  </div>
</nav>
<div class="container">
