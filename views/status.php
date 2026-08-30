<?php
/** @var Election $election */
?>
<?php include __DIR__ . '/layout_header.php'; ?>

<h3>สถานะและผลการเลือกตั้ง</h3>

<?php $summary = $election->getSummary(); ?>

<?php if ($summary['phase'] === 'OPEN'): ?>

  <p>อยู่ระหว่างเปิดรับคะแนน — ได้รับบัตรแล้ว <strong><?= $summary['ballots'] ?></strong> ใบ</p>

<?php elseif ($summary['phase'] === 'CLOSED'): ?>

  <p>ปิดรับคะแนนแล้ว — รอตรวจสอบ <strong><?= $summary['pending'] ?></strong> กลุ่ม</p>
  <table class="table w-auto">
    <thead><tr><th>ผู้สมัคร</th><th>คะแนนชั่วคราว</th></tr></thead>
    <tbody>
    <?php foreach ($summary['score'] as $candidateId => $score): ?>
      <tr><td><?= htmlspecialchars($election->candidates[$candidateId]->name) ?></td><td><?= $score ?></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p class="text-muted">คะแนนนี้ยังไม่รวมบัตรที่รอตรวจสอบ</p>

<?php else: ?>

  <p>สรุปผลแล้ว — บัตรที่นับคะแนน <strong><?= $summary['counted'] ?></strong> ใบ, บัตรที่ไม่นับ <strong><?= $summary['rejected'] ?></strong> ใบ</p>
  <table class="table w-auto">
    <thead><tr><th>ผู้สมัคร</th><th>คะแนนรวม</th></tr></thead>
    <tbody>
    <?php foreach ($summary['score'] as $candidateId => $score): ?>
      <tr><td><?= htmlspecialchars($election->candidates[$candidateId]->name) ?></td><td><?= $score ?></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table>

<?php endif; ?>

<?php include __DIR__ . '/layout_footer.php'; ?>
