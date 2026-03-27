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
  <style>
    * {
      box-sizing: border-box;
    }

    @page {
      size: A4 portrait;
      margin: 10mm;
    }

    html,
    body {
      margin: 0;
      padding: 0;
      background: #fff;
      color: #111;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
      line-height: 1.25;
    }

    body {
      padding: 0;
    }

    .page {
      width: 190mm;
      min-height: 277mm;
      margin: 0 auto;
    }

    .header {
      display: grid;
      grid-template-columns: 1fr 2fr 1fr;
      align-items: center;
      border-bottom: 1.5px solid #000;
      padding-bottom: 6px;
      margin-bottom: 8px;
    }

    .header-left {
      font-size: 10px;
    }

    .header-center {
      text-align: center;
    }

    .title {
      margin: 0;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      line-height: 1.2;
    }

    .header-right {
      text-align: right;
    }

    .logo {
      width: 58px;
      height: auto;
    }

    .logo-placeholder {
      display: inline-block;
      width: 58px;
      height: 42px;
      border: 1px solid #000;
      font-size: 8px;
      text-align: center;
      padding-top: 10px;
      line-height: 1.2;
    }

    .member-info {
      border-collapse: collapse;
    }

    .member-info td {
      padding: 1px 3px;
      vertical-align: top;
    }

    .member-info td:first-child {
      font-weight: 700;
      width: 42px;
    }

    .section-title {
      margin: 8px 0 4px;
      font-size: 10px;
      font-weight: 700;
      background: #f0f0f0;
      padding: 4px 6px;
      border: 1px solid #000;
    }

    table.report-table,
    table.legend-table,
    table.notes-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 6px;
      table-layout: fixed;
    }

    .report-table th,
    .report-table td,
    .legend-table th,
    .legend-table td,
    .notes-table td {
      border: 1px solid #000;
      padding: 3px 4px;
      vertical-align: top;
    }

    .report-table th,
    .legend-table th {
      text-align: center;
      font-weight: 700;
      background: #f8f8f8;
    }

    .report-table td,
    .legend-table td,
    .notes-table td {
      word-wrap: break-word;
    }

    .text-center {
      text-align: center;
    }

    .col-no {
      width: 26px;
      text-align: center;
    }

    .col-score {
      width: 26px;
      text-align: center;
    }

    .col-note {
      width: 110px;
    }

    .legend-score {
      width: 60px;
    }

    .legend-label {
      width: 130px;
    }

    .check {
      font-weight: 700;
      font-size: 12px;
      line-height: 1;
      vertical-align: middle;
    }

    .bottom-section {
      display: grid;
      grid-template-columns: 1.2fr 0.8fr;
      gap: 8px;
      align-items: start;
    }

    .bottom-left .section-title,
    .bottom-right .section-title {
      margin-top: 4px;
    }

    .notes-table td {
      min-height: 115px;
      height: 115px;
      white-space: pre-line;
      font-size: 10px;
      line-height: 1.3;
    }

    @media print {
      html,
      body {
        width: 210mm;
        height: 297mm;
      }

      .page {
        width: 190mm;
        min-height: auto;
        margin: 0 auto;
      }

      .section-title,
      .report-table,
      .legend-table,
      .notes-table,
      .bottom-section,
      .header {
        page-break-inside: avoid;
      }
    }
  </style>
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
