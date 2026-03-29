<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$sections = hydra_sections();
$data = hydra_collect_request_data($_POST ?: []);

$page = $_GET['page'] ?? 'individu';
$allowedPages = ['individu', 'komunal'];

if (!in_array($page, $allowedPages, true)) {
    $page = 'individu';
}

$pageTitle = match ($page) {
    'komunal' => 'Raport Komunal',
    default => 'Raport Individu',
};

$dummyKomunalStudents = [
    ['name' => 'Radja'],
    ['name' => 'Vino'],
    ['name' => 'Itun'],
    ['name' => 'Myesha'],
    ['name' => 'Tisya'],
];

$komunalMonth = trim((string)($_POST['komunal_month'] ?? 'Februari 2025'));
$komunalCoach = trim((string)($_POST['komunal_coach'] ?? 'Coach Nadya'));

$komunalRemarks = $_POST['komunal_remarks'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydra Swim Club - <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #111827;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #111827;
            color: #fff;
            padding: 24px 16px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sidebar h2 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .sidebar p {
            margin: 0 0 24px;
            font-size: 13px;
            color: #cbd5e1;
            line-height: 1.5;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav a {
            display: block;
            padding: 12px 14px;
            border-radius: 10px;
            text-decoration: none;
            color: #e5e7eb;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .nav a:hover {
            background: #1f2937;
            color: #fff;
        }

        .nav a.active {
            background: #2563eb;
            color: #fff;
        }

        .main {
            flex: 1;
            padding: 24px 16px 32px;
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto;
        }

        .card {
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 16px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        p.helper {
            margin: 0;
            color: #4b5563;
            font-size: 14px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        input[type="text"], textarea, select {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .section-title {
            margin: 0 0 12px;
            font-size: 18px;
        }

        table.form-table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-table th,
        .form-table td {
            border: 1px solid #e5e7eb;
            padding: 10px 8px;
            vertical-align: top;
            font-size: 13px;
        }

        .form-table th {
            background: #f9fafb;
            text-align: left;
        }

        .col-no {
            width: 42px;
            text-align: center;
        }

        .col-score {
            width: 90px;
        }

        .col-name {
            width: 220px;
        }

        .readonly-box {
            width: 100%;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: #111827;
            color: #fff;
        }

        .btn-secondary {
            background: #2563eb;
            color: #fff;
        }

        .small-note {
            margin-top: 10px;
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                padding-top: 16px;
            }

            .grid-3,
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .form-table th,
            .form-table td {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h2>Hydra Swim Club</h2>
        <p>Menu navigasi report</p>

        <nav class="nav">
            <a href="?page=individu" class="<?= $page === 'individu' ? 'active' : '' ?>">
                Raport Individu
            </a>
            <a href="?page=komunal" class="<?= $page === 'komunal' ? 'active' : '' ?>">
                Raport Komunal
            </a>
        </nav>
    </aside>

    <main class="main">
        <div class="wrap">

            <?php if ($page === 'individu'): ?>
                <div class="card">
                    <h1>Form Input Raport Individu</h1>
                    <p class="helper">Silakan isi data raport individu siswa.</p>
                </div>

                <form method="post">
                    <input type="hidden" name="page" value="individu">

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
                                                    <option value="<?= $score ?>" <?= (($data['scores'][$sectionKey][$rowNo] ?? '') === $score) ? 'selected' : '' ?>>
                                                        <?= $score ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                name="remarks[<?= hydra_h($sectionKey) ?>][<?= $rowNo ?>]"
                                                value="<?= hydra_h($data['remarks'][$sectionKey][$rowNo] ?? '') ?>"
                                            >
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
                            <button class="btn btn-secondary" type="submit" formaction="pdf.php?mode=inline&page=individu" formtarget="_blank">Preview PDF</button>
                            <button class="btn btn-primary" type="submit" formaction="pdf.php?mode=download&page=individu">Export PDF</button>
                        </div>
                    </div>
                </form>

            <?php elseif ($page === 'komunal'): ?>
                <div class="card">
                    <h1>Form Input Raport Komunal</h1>
                    <p class="helper">Hanya catatan progress yang diisi.</p>
                </div>

                <form method="post">
                    <input type="hidden" name="page" value="komunal">

                    <div class="card">
                        <div class="grid-2">
                            <div>
                                <label for="komunal_month">Bulan</label>
                                <input id="komunal_month" type="text" name="komunal_month" value="<?= hydra_h($komunalMonth) ?>">
                            </div>
                            <div>
                                <label for="komunal_coach">Coach</label>
                                <input id="komunal_coach" type="text" name="komunal_coach" value="<?= hydra_h($komunalCoach) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <h2 class="section-title">Daftar Progress Murid</h2>
                        <table class="form-table">
                            <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th class="col-name">Nama</th>
                                <th>Catatan / Progress</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($dummyKomunalStudents as $index => $student): ?>
                                <?php $rowNo = $index + 1; ?>
                                <tr>
                                    <td class="col-no"><?= $rowNo ?></td>
                                    <td>
                                        <div class="readonly-box"><?= hydra_h($student['name']) ?></div>
                                        <input type="hidden" name="komunal_students[<?= $rowNo ?>][name]" value="<?= hydra_h($student['name']) ?>">
                                    </td>
                                    <td>
                                        <textarea
                                            name="komunal_remarks[<?= $rowNo ?>]"
                                            placeholder="Tulis progress / catatan murid di sini..."
                                        ><?= hydra_h($komunalRemarks[$rowNo] ?? '') ?></textarea>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="small-note">
                            Nama murid dibuat dummy terlebih dahulu sesuai permintaan, tanpa database. Yang dapat diubah hanya kolom catatan.
                        </div>
                    </div>

                    <div class="card">
                        <div class="actions">
                            <button class="btn btn-secondary" type="submit" formaction="pdf.php?mode=inline&page=komunal" formtarget="_blank">Preview PDF</button>
                            <button class="btn btn-primary" type="submit" formaction="pdf.php?mode=download&page=komunal">Export PDF</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>

        </div>
    </main>
</div>
</body>
</html>