<?php
/** @var Election $election */
/** @var VotingController $votingController */
?>
<?php include __DIR__ . '/layout_header.php'; ?>

<h3>ดูรายชื่อผู้สมัคร และลงคะแนน</h3>

<?php if (isset($_GET['voted'])): ?>
  <div class="alert alert-success">บันทึกบัตรลงคะแนนเรียบร้อย</div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<table class="table table-bordered w-auto">
  <thead><tr><th>รหัส</th><th>ชื่อผู้สมัคร</th></tr></thead>
  <tbody>
  <?php foreach ($votingController->getCandidates() as $c): ?>
    <tr><td><?= htmlspecialchars($c->id) ?></td><td><?= htmlspecialchars($c->name) ?></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>

<form method="post" action="index.php" class="card p-3" style="max-width:480px">
  <input type="hidden" name="action" value="vote">

  <div class="mb-3">
    <label class="form-label">เลือกตัวตนผู้มีสิทธิ์เลือกตั้ง</label>
    <select name="voter_id" class="form-select" required>
      <?php foreach ($election->voters as $v): ?>
        <option value="<?= htmlspecialchars($v->id) ?>">
          <?= htmlspecialchars($v->id . ' - ' . $v->name . ($v->voted ? ' (โหวตแล้ว)' : '')) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <?php foreach ([1, 2, 3] as $rank): ?>
  <div class="mb-3">
    <label class="form-label">อันดับ <?= $rank ?></label>
    <select name="rank<?= $rank ?>" class="form-select" required>
      <?php foreach ($election->candidates as $c): ?>
        <option value="<?= htmlspecialchars($c->id) ?>"><?= htmlspecialchars($c->id . ' - ' . $c->name) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endforeach; ?>

  <button type="submit" class="btn btn-primary">ลงคะแนน</button>
</form>

<?php include __DIR__ . '/layout_footer.php'; ?>
