<?php
/** @var Election $election */
?>
<?php include __DIR__ . '/layout_header.php'; ?>

<h3>หน้าเจ้าหน้าที่เลือกตั้ง</h3>

<?php if (isset($_GET['error'])): ?>
  <div class="alert alert-warning"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<?php if ($election->status === 'OPEN'): ?>
  <form method="post" action="index.php" class="mb-4">
    <input type="hidden" name="action" value="close">
    <button type="submit" class="btn btn-danger">ปิดรับคะแนน</button>
  </form>
<?php endif; ?>

<?php if ($election->status !== 'OPEN'): ?>
  <form method="post" action="index.php" class="mb-4">
    <input type="hidden" name="action" value="reopen">
    <button type="submit" class="btn btn-warning">เปิดรับคะแนนอีกครั้ง</button>
    <span class="text-muted ms-2">กลุ่มบัตรซ้ำที่ตรวจไว้จะถูกล้างทั้งหมด</span>
  </form>
<?php endif; ?>

<?php if ($election->status !== 'OPEN'): ?>
  <h5>บัตรรูปแบบซ้ำกันได้ (ตั้งแต่ <?= $election->dupLimit ?> ใบขึ้นไป)</h5>

  <?php if (empty($election->groups)): ?>
    <p class="text-muted">ไม่มีบัตรรูปแบบซ้ำ</p>
  <?php else: ?>
    <?php foreach ($election->groups as $g): ?>
      <div class="card mb-3">
        <div class="card-body">
          <p class="mb-1">กลุ่ม <strong><?= htmlspecialchars($g->id) ?></strong> — รูปแบบ: <?= htmlspecialchars(implode(' > ', $g->pattern)) ?></p>
          <p class="mb-1">บัตรในกลุ่ม: <?= htmlspecialchars(implode(', ', $g->ballotIds)) ?> (<?= count($g->ballotIds) ?> ใบ)</p>
          <p class="mb-2">สถานะ: <span class="badge bg-info text-dark"><?= htmlspecialchars($g->status) ?></span></p>

          <?php if ($g->status === 'รอตรวจสอบ'): ?>
            <form method="post" action="index.php" class="d-inline">
              <input type="hidden" name="action" value="decide">
              <input type="hidden" name="group_id" value="<?= htmlspecialchars($g->id) ?>">
              <input type="hidden" name="result" value="รับรอง">
              <button type="submit" class="btn btn-success btn-sm">รับรอง</button>
            </form>
            <form method="post" action="index.php" class="d-inline">
              <input type="hidden" name="action" value="decide">
              <input type="hidden" name="group_id" value="<?= htmlspecialchars($g->id) ?>">
              <input type="hidden" name="result" value="ไม่นับ">
              <button type="submit" class="btn btn-outline-danger btn-sm">ไม่นับ</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($election->status === 'ปิดรับคะแนนแล้ว'): ?>
    <form method="post" action="index.php">
      <input type="hidden" name="action" value="finish">
      <button type="submit" class="btn btn-primary" <?= $election->hasPendingGroup() ? 'disabled' : '' ?>>สรุปผล</button>
      <?php if ($election->hasPendingGroup()): ?>
        <span class="text-muted ms-2">ต้องตัดสินให้ครบทุกกลุ่มก่อนถึงสรุปผลได้</span>
      <?php endif; ?>
    </form>
  <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/layout_footer.php'; ?>
