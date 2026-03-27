<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$sections = hydra_sections();
$data = hydra_collect_request_data($_POST ?: []);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydra Swim Club - Input Report</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f5f7fb; color: #111827; }
        .wrap { max-width: 1100px; margin: 24px auto; padding: 0 16px 32px; }
        .card { background: #fff; border: 1px solid #d1d5db; border-radius: 12px; padding: 18px; margin-bottom: 16px; }
        h1 { margin: 0 0 8px; font-size: 26px; }
        p.helper { margin: 0; color: #4b5563; font-size: 14px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; }
        input[type="text"], textarea, select { width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px; font-size: 14px; font-family: Arial, Helvetica, sans-serif; }
        textarea { min-height: 90px; resize: vertical; }
        .section-title { margin: 0 0 12px; font-size: 18px; }
        table.form-table { width: 100%; border-collapse: collapse; }
        .form-table th, .form-table td { border: 1px solid #e5e7eb; padding: 8px; vertical-align: top; font-size: 13px; }
        .form-table th { background: #f9fafb; text-align: left; }
        .col-no { width: 42px; text-align: center; }
        .col-score { width: 90px; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { border: 0; border-radius: 10px; padding: 12px 16px; font-size: 14px; font-weight: 700; cursor: pointer; }
        .btn-primary { background: #111827; color: #fff; }
        .btn-secondary { background: #2563eb; color: #fff; }
        .small-note { margin-top: 10px; font-size: 12px; color: #6b7280; line-height: 1.5; }
        code { background: #f3f4f6; padding: 2px 4px; border-radius: 4px; }
        @media (max-width: 800px) {
            .grid-3 { grid-template-columns: 1fr; }
            .form-table th, .form-table td { font-size: 12px; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Form Input Report Hydra Swim Club</h1>
    </div>

    <form method="post">
        <div class="card">
            <div class="grid-3">
                <div>
                    <label for="student_name">Nama</label>
                    <input id="student_name" type="text" name="student_name" value="<?= hydra_h($data['student']['name']) ?>">
                </div>
                <div>
                    <label for="student_class">Kelas</label>
                    <input id="student_class" type="text" name="student_class" value="<?= hydra_h($data['student']['class']) ?>">
                </div>
                <div>
                    <label for="student_month">Bulan</label>
                    <input id="student_month" type="text" name="student_month" value="<?= hydra_h($data['student']['month']) ?>">
                </div>
            </div>
        </div>

        <?php foreach ($sections as $sectionKey => $section): ?>
            <div class="card">
                <h2 class="section-title"><?= hydra_h($section['title']) ?></h2>
                <table class="form-table">
                    <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>Indikator</th>
                        <th class="col-score">Skor</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($section['indicators'] as $index => $indicator): $rowNo = $index + 1; ?>
                        <tr>
                            <td class="col-no"><?= $rowNo ?></td>
                            <td>
                                <?php if ($indicator['html']): ?>
                                    <?= $indicator['text'] ?>
                                <?php else: ?>
                                    <?= hydra_h($indicator['text']) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <select name="scores[<?= hydra_h($sectionKey) ?>][<?= $rowNo ?>]">
                                    <option value="">-</option>
                                    <?php for ($score = 1; $score <= 4; $score++): ?>
                                        <option value="<?= $score ?>" <?= (($data['scores'][$sectionKey][$rowNo] ?? '') === $score) ? 'selected' : '' ?>><?= $score ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="remarks[<?= hydra_h($sectionKey) ?>][<?= $rowNo ?>]" value="<?= hydra_h($data['remarks'][$sectionKey][$rowNo] ?? '') ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

        <div class="card">
            <label for="coach_notes">Catatan Pelatih</label>
            <textarea id="coach_notes" name="coach_notes"><?= hydra_h($data['coach_notes']) ?></textarea>
        </div>

        <div class="card">
            <div class="actions">
                <button class="btn btn-secondary" type="submit" formaction="pdf.php?mode=inline" formtarget="_blank">Preview PDF</button>
                <button class="btn btn-primary" type="submit" formaction="pdf.php?mode=download">Export PDF</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
