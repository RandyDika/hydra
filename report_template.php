<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function hydra_render_report(array $data): string
{
    $sections = hydra_sections();
    $legend = hydra_score_legend();
    $logoSrc = hydra_logo_src();

    ob_start();
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Bulanan Member Hydra Swim Club</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="page">

    <div class="header">
      <div class="header-left">
        <table class="member-info">
          <tr>
            <td>Nama</td>
            <td>: <strong><?= hydra_h($data['student']['name']) ?></strong></td>
          </tr>
          <tr>
            <td>Kelas</td>
            <td>: <strong><?= hydra_h($data['student']['class']) ?></strong></td>
          </tr>
          <tr>
            <td>Bulan</td>
            <td>: <strong><?= hydra_h($data['student']['month']) ?></strong></td>
          </tr>
        </table>
      </div>

      <div class="header-center">
        <h1 class="title">LAPORAN BULANAN MEMBER HYDRA SWIM CLUB</h1>
      </div>

      <div class="header-right">
        <?php if ($logoSrc): ?>
          <img src="<?= hydra_h($logoSrc) ?>" alt="Logo Hydra Swim Club" class="logo">
        <?php else: ?>
          <div class="logo-placeholder">HYDRA<br>LOGO</div>
        <?php endif; ?>
      </div>
    </div>

    <?php foreach ($sections as $sectionKey => $section): ?>
      <div class="section-title"><?= hydra_h($section['title']) ?></div>
      <table class="report-table">
        <thead>
          <tr>
            <th class="col-no">No</th>
            <th>Indikator</th>
            <th class="col-score">1</th>
            <th class="col-score">2</th>
            <th class="col-score">3</th>
            <th class="col-score">4</th>
            <th class="col-note">Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($section['indicators'] as $index => $indicator): $rowNo = $index + 1; ?>
          <tr>
            <td class="text-center"><?= $rowNo ?></td>
            <td>
              <?php if ($indicator['html']): ?>
                <?= $indicator['text'] ?>
              <?php else: ?>
                <?= hydra_h($indicator['text']) ?>
              <?php endif; ?>
            </td>
            <?php for ($scoreCol = 1; $scoreCol <= 4; $scoreCol++): ?>
              <td class="text-center check"><?= (($data['scores'][$sectionKey][$rowNo] ?? '') === $scoreCol) ? '✓' : '' ?></td>
            <?php endfor; ?>
            <td><?= hydra_h($data['remarks'][$sectionKey][$rowNo] ?? '') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endforeach; ?>

    <div class="bottom-section">
      <div class="bottom-left">
        <div class="section-title">Keterangan Skor</div>
        <table class="legend-table">
          <thead>
            <tr>
              <th class="legend-score">Skor</th>
              <th class="legend-label">Keterangan</th>
              <th>Deskripsi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($legend as $row): ?>
              <tr>
                <td class="text-center"><?= $row['score'] ?></td>
                <td><?= hydra_h($row['label']) ?></td>
                <td><?= hydra_h($row['description']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="bottom-right">
        <div class="section-title">Catatan</div>
        <table class="notes-table">
          <tr>
            <td><?= nl2br(hydra_h($data['coach_notes'])) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
    <?php

    return (string) ob_get_clean();
}
