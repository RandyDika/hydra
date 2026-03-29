<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function hydra_render_report(array $data): string
{
    $page = $_GET['page'] ?? ($_POST['page'] ?? 'individu');

    if ($page === 'komunal') {
        return hydra_render_report_komunal($data);
    }

    return hydra_render_report_individu($data);
}

function hydra_render_report_individu(array $data): string
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

    html, body {
      margin: 0;
      padding: 0;
      background: #fff;
      color: #111;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 9px;
      line-height: 1.2;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    body {
      width: 100%;
    }

    .page {
      width: 190mm;
      height: 277mm;
      margin: 0 auto;
      padding: 0;
      overflow: hidden;
      transform: scale(0.97);
      transform-origin: top center;
    }

    .header {
      display: grid;
      grid-template-columns: 1.1fr 2fr 0.8fr;
      align-items: center;
      gap: 5px;
      margin-bottom: 6px;
      padding-bottom: 4px;
      border-bottom: 1px solid #000;
    }

    .header-left {
      font-size: 8.5px;
    }

    .header-center {
      text-align: center;
    }

    .header-right {
      text-align: right;
    }

    .title {
      margin: 0;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      line-height: 1.15;
    }

    .logo {
      width: 46px;
      height: auto;
    }

    .logo-placeholder {
      display: inline-block;
      width: 46px;
      height: 32px;
      border: 1px solid #000;
      font-size: 6px;
      text-align: center;
      padding-top: 8px;
      line-height: 1.1;
    }

    .member-info {
      width: 100%;
      border-collapse: collapse;
    }

    .member-info td {
      padding: 1px 3px;
      vertical-align: top;
      line-height: 1.15;
    }

    .member-info td:first-child {
      width: 38px;
      font-weight: 700;
      white-space: nowrap;
    }

    .section-title {
      margin: 6px 0 3px;
      padding: 3px 5px;
      border: 1px solid #000;
      background: #f0f0f0;
      font-size: 9px;
      font-weight: 700;
      line-height: 1.15;
    }

    table.report-table,
    table.legend-table,
    table.notes-table {
      width: 100%;
      margin-bottom: 3px;
      border-collapse: collapse;
      table-layout: fixed;
    }

    .report-table th,
    .report-table td,
    .legend-table th,
    .legend-table td,
    .notes-table td {
      border: 1px solid #000;
      padding: 2.5px 3.5px;
      vertical-align: top;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    .report-table th,
    .legend-table th {
      background: #f8f8f8;
      text-align: center;
      font-weight: 700;
      line-height: 1.15;
    }

    .report-table td {
      line-height: 1.2;
    }

    .text-center {
      text-align: center;
    }

    .col-no {
      width: 24px;
      text-align: center;
    }

    .col-score {
      width: 24px;
      text-align: center;
    }

    .col-note {
      width: 85px;
    }

    .legend-score {
      width: 45px;
    }

    .legend-label {
      width: 90px;
    }

    .check {
      font-size: 10px;
      font-weight: 700;
      line-height: 1;
      vertical-align: middle;
    }

    .bottom-section {
      display: grid;
      grid-template-columns: 1.2fr 0.8fr;
      gap: 6px;
      align-items: start;
      margin-top: 4px;
    }

    .legend-table td,
    .legend-table th {
      padding: 2.5px 3.5px;
      font-size: 8.5px;
      line-height: 1.15;
    }

    .notes-table td {
      height: 80px;
      min-height: 80px;
      white-space: pre-line;
      font-size: 8.5px;
      line-height: 1.2;
    }

    .report-table tbody tr {
      page-break-inside: avoid;
    }

    .section-block {
      page-break-inside: avoid;
      break-inside: avoid;
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
      <div class="section-block">
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
                  <td class="text-center check">
                    <?= (($data['scores'][$sectionKey][$rowNo] ?? '') === $scoreCol) ? '✓' : '' ?>
                  </td>
                <?php endfor; ?>
                <td><?= hydra_h($data['remarks'][$sectionKey][$rowNo] ?? '') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endforeach; ?>

    <div class="bottom-section">
      <div>
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

      <div>
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

function hydra_render_report_komunal(array $data): string
{
    $logoSrc = hydra_logo_src();

    $month = trim((string)($_POST['komunal_month'] ?? 'Februari 2025'));
    $coach = trim((string)($_POST['komunal_coach'] ?? 'Coach Nadya'));
    $students = $_POST['komunal_students'] ?? [];
    $remarks = $_POST['komunal_remarks'] ?? [];

    ob_start();
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Bulanan Komunal Hydra Swim Club</title>
  <style>
    * {
      box-sizing: border-box;
    }

    @page {
      size: A4 portrait;
      margin: 8mm;
    }

    html, body {
      margin: 0;
      padding: 0;
      background: #fff;
      color: #111;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
      line-height: 1.25;
    }

    .page {
      width: 194mm;
      min-height: 281mm;
      margin: 0 auto;
    }

    .header {
      display: grid;
      grid-template-columns: 38mm 1fr;
      align-items: center;
      gap: 6mm;
      margin-bottom: 6px;
      padding-bottom: 6px;
      border-bottom: 1px solid #000;
    }

    .brand-box {
      text-align: center;
    }

    .logo {
      width: 38mm;
      max-width: 100%;
      height: auto;
      margin-bottom: 3px;
    }

    .logo-placeholder {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 38mm;
      height: 24mm;
      border: 1px solid #000;
      font-size: 9px;
      font-weight: 700;
      margin-bottom: 3px;
    }

    .brand-label {
      font-size: 9px;
      letter-spacing: 2px;
    }

    .report-header h1 {
      margin: 0;
      font-size: 22px;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
    }

    .report-header h2 {
      margin: 5px 0 0;
      font-size: 12px;
      line-height: 1.1;
      font-weight: 800;
      text-transform: uppercase;
    }

    .meta {
      margin-bottom: 6px;
      font-size: 10px;
      font-weight: 700;
    }

    table.report-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    .report-table th,
    .report-table td {
      border: 1px solid #000;
      padding: 6px 8px;
      vertical-align: top;
    }

    .report-table th {
      background: #efefef;
      text-align: left;
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
    }

    .col-name {
      width: 30mm;
    }

    .student-name {
      font-weight: 800;
      text-transform: uppercase;
      white-space: pre-line;
    }

    .progress-text {
      white-space: pre-line;
      font-weight: 700;
      line-height: 1.35;
    }

    .footer-note {
      margin-top: 6px;
      font-size: 8.5px;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="page">
    <div class="header">
      <div class="brand-box">
        <?php if ($logoSrc): ?>
          <img src="<?= hydra_h($logoSrc) ?>" alt="Logo Hydra Swim Club" class="logo">
        <?php else: ?>
          <div class="logo-placeholder">HYDRA LOGO</div>
        <?php endif; ?>
        <div class="brand-label">SWIM CLUB</div>
      </div>

      <div class="report-header">
        <h1>MONTHLY REPORT</h1>
        <h2><?= hydra_h($coach) ?></h2>
      </div>
    </div>

    <div class="meta">
      BULAN: <?= hydra_h($month) ?>
    </div>

    <table class="report-table">
      <thead>
        <tr>
          <th class="col-name">NAMA</th>
          <th>PROGRESS</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $rowNo => $student): ?>
          <tr>
            <td class="student-name"><?= hydra_h($student['name'] ?? '') ?></td>
            <td class="progress-text"><?= nl2br(hydra_h($remarks[$rowNo] ?? '')) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="footer-note">
      Hydra Swim Club - Monthly Report
    </div>
  </div>
</body>
</html>
    <?php

    return (string) ob_get_clean();
}