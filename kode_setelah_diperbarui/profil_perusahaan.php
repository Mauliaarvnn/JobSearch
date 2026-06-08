<?php
include 'config/koneksi.php';
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login_perusahaan.php");
    exit;
}

include 'employer_score.php';

// Simulasi data dari database jika belum di-set di session (Idealnya diambil via query SQL berdasarkan $_SESSION['id_user'])
$deskripsi_default = (isset($_SESSION['deskripsi'])) ? $_SESSION['deskripsi'] : $_SESSION['nama'] . " adalah perusahaan berkembang yang berfokus pada inovasi dan penciptaan solusi digital terbaik di kelasnya. Kami berdedikasi membangun lingkungan kerja yang inklusif, kolaboratif, dan mendukung pertumbuhan karier setiap talenta.";
$website_default = (isset($_SESSION['website'])) ? $_SESSION['website'] : "www." . strtolower(str_replace(' ', '', $_SESSION['nama'])) . ".com";
$telepon_default = (isset($_SESSION['telepon'])) ? $_SESSION['telepon'] : "021-5550123";
$alamat_default = (isset($_SESSION['alamat'])) ? $_SESSION['alamat'] : "Jl. Jend. Sudirman No. Kav 21, Kuningan, Jakarta Selatan";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan - Lokerin</title>
    <link rel="icon" type="image/png" href="assets/icon_head_lokerin.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9fafb;
            color: #111827;
            line-height: 1.6;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid #e5e7eb;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 50;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            padding: 0 8px;
        }

        .logo-img {
            width: 27px;
            height: 27px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #0D9488;
        }

        .company-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .company-avatar {
            width: 40px;
            height: 40px;
            background: #f59e0b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .company-info {
            flex: 1;
            overflow: hidden;
        }

        .company-name {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .company-industry {
            font-size: 12px;
            color: #6b7280;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .employer-score {
            padding: 8px 12px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .employer-score-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .employer-score-label {
            font-size: 12px;
            color: #6b7280;
        }

        .employer-score-value {
            font-size: 14px;
            font-weight: 600;
            color: #f59e0b;
        }

        .employer-score-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }

        .employer-score-fill {
            height: 100%;
            background: #f59e0b;
            border-radius: 2px;
        }

        nav {
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: #6b7280;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .nav-item:hover {
            background: #f9fafb;
            color: #111827;
        }

        .nav-item.active {
            background: #e0f2f1;
            color: #0d9488;
        }

        .nav-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 16px 0;
        }

        .nav-item-logout {
            color: #ef4444;
        }

        .nav-item-logout:hover {
            background: #fef2f2;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 24px 32px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .header-left p {
            font-size: 14px;
            color: #6b7280;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-primary {
            background: #0d9488;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #0f766e;
        }

        .btn-secondary {
            background: white;
            color: #6b7280;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #f9fafb;
        }

        /* Profile Header Card */
        .profile-header {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 32px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .profile-cover {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
        }

        .profile-header-content {
            position: relative;
            display: flex;
            gap: 24px;
            padding-top: 40px;
        }

        .profile-avatar-large {
            width: 120px;
            height: 120px;
            background: #f59e0b;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 48px;
            border: 4px solid white;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .profile-header-info {
            flex: 1;
            padding-top: 40px;
        }

        .profile-header-name {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .profile-header-industry {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 16px;
        }

        .profile-header-meta {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .profile-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: #6b7280;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        /* Card Layout */
        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .card:last-child {
            margin-bottom: 0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 12px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        /* Form Group styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background-color: #fff;
            transition: all 0.2s;
            color: #374151;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
            color: #374151;
        }

        /* Info List */
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 14px;
            color: #111827;
        }

        /* Social Links & Benefits */
        .social-input-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .social-icon-wrapper {
            width: 36px;
            height: 36px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            flex-shrink: 0;
        }

        .benefits-checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .benefit-checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .benefit-checkbox-label:hover {
            background: #f0fdfa;
            border-color: #0d9488;
        }

        .benefit-checkbox-label input {
            accent-color: #0d9488;
            width: 16px;
            height: 16px;
        }

        /* Verification Card */
        .verification-card {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .verification-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .verification-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verification-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e40af;
        }

        .verification-text {
            font-size: 13px;
            color: #1e40af;
            margin-bottom: 16px;
            opacity: 0.9;
        }

        .verification-progress {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .progress-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .progress-check {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #22c55e;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .progress-check.pending {
            background: #cbd5e1;
            color: #64748b;
        }

        /* Responsiveness */
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .profile-header-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-header-meta {
                justify-content: center;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .benefits-checkbox-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img class="logo-img" src="assets/logo_lokerin.png" alt="L">
                <span class="logo-text">LokerIn</span>
            </div>

            <div class="company-profile">
                <div class="company-avatar">
                    <?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?>
                </div>
                <div class="company-info">
                    <div class="company-name"><?= htmlspecialchars($_SESSION['nama']) ?></div>
                    <div class="company-industry"><?= htmlspecialchars($_SESSION['email']) ?></div>
                </div>
            </div>

            <div class="employer-score">
                <div class="employer-score-header">
                    <span class="employer-score-label">Employer Score</span>
                    <span class="employer-score-value"><?= isset($employer_score) ? $employer_score : '75' ?>%</span>
                </div>
                <div class="employer-score-bar">
                    <div class="employer-score-fill" style="width: <?= isset($employer_score) ? $employer_score : '75' ?>%;"></div>
                </div>
            </div>

            <nav>
                <a href="dashboard_perusahaan.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>
                <a href="posting_lowongan.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="12" r="8"></circle><line x1="12" y1="1" x2="12" y2="3"></line></svg>
                    Posting Lowongan
                </a>
                <a href="kelola_lowongan.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    Kelola Lowongan
                </a>
                <a href="kandidat.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Kandidat
                </a>
                <a href="profil_perusahaan.php" class="nav-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Profil Perusahaan
                </a>
                <a href="analytics.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    Analytics
                </a>
                <a href="pesan_perusahaan.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    Pesan
                </a>
                <div class="nav-divider"></div>
                <a href="pengaturan_perusahaan.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M12 1v6m0 6v6"></path><path d="m4.93 4.93 4.24 4.24m5.66 5.66 4.24 4.24"></path><path d="M1 12h6m6 0h6"></path><path d="m4.93 19.07 4.24-4.24m5.66-5.66 4.24-4.24"></path></svg>
                    Pengaturan
                </a>
                <a href="logout.php" class="nav-item nav-item-logout">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Keluar
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <form action="proses_update_profil.php" method="POST">
                <!-- Header -->
                <header class="header">
                    <div class="header-left">
                        <h1>Profil Perusahaan</h1>
                        <p>Kelola informasi dan branding publik perusahaan Anda</p>
                    </div>

                    <div class="header-actions">
                        <button type="button" class="btn-secondary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            Preview Profil
                        </button>
                        <button type="submit" class="btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </header>

                <!-- Profile Header Banner -->
                <div class="profile-header">
                    <div class="profile-cover"></div>
                    <div class="profile-header-content">
                        <div class="profile-avatar-large"><?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?></div>
                        <div class="profile-header-info">
                            <h2 class="profile-header-name"><?= htmlspecialchars($_SESSION['nama']) ?></h2>
                            <p class="profile-header-industry">Teknologi & Informasi • Software House</p>
                            <div class="profile-header-meta">
                                <span class="profile-meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    Jakarta, Indonesia
                                </span>
                                <span class="profile-meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                    50 - 200 Karyawan
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- Left Column (Editable Forms) -->
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        
                        <!-- Tentang Perusahaan -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Tentang Perusahaan</h3>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi Instansi / Perusahaan</label>
                                <textarea name="deskripsi" class="form-textarea" placeholder="Tuliskan latar belakang, visi, dan misi perusahaan Anda..."><?= htmlspecialchars($deskripsi_default) ?></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Kategori Industri</label>
                                    <select name="industri" class="form-select">
                                        <option value="Teknologi">Teknologi & Informasi</option>
                                        <option value="Keuangan">Keuangan & Perbankan</option>
                                        <option value="Manufaktur">Manufaktur & Pabrik</option>
                                        <option value="Kesehatan">Pelayanan Kesehatan</option>
                                        <option value="Pendidikan">Pendidikan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Skala Karyawan</label>
                                    <select name="skala_karyawan" class="form-select">
                                        <option value="1-50">1 - 50 Karyawan (Startup/Kecil)</option>
                                        <option value="50-200" selected>50 - 200 Karyawan (Menengah)</option>
                                        <option value="200-500">200 - 500 Karyawan (Besar)</option>
                                        <option value="500+">Lebih dari 500 Karyawan (Korporat)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kontak & Alamat -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Kontak & Lokasi Kantor</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Website Perusahaan</label>
                                    <input type="text" name="website" class="form-input" value="<?= htmlspecialchars($website_default) ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nomor Telepon Official</label>
                                    <input type="text" name="telepon" class="form-input" value="<?= htmlspecialchars($telepon_default) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap Kantor Pusat</label>
                                <textarea name="alamat" class="form-textarea" rows="3"><?= htmlspecialchars($alamat_default) ?></textarea>
                            </div>
                        </div>

                        <!-- Benefit Karyawan -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Fasilitas & Benefit Perusahaan</h3>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="margin-bottom: 12px;">Pilih keuntungan yang ditawarkan untuk pelamar:</label>
                                <div class="benefits-checkbox-grid">
                                    <label class="benefit-checkbox-label">
                                        <input type="checkbox" name="benefit[]" value="BPJS" checked>
                                        <span>BPJS Kesehatan & Ketenagakerjaan</span>
                                    </label>
                                    <label class="benefit-checkbox-label">
                                        <input type="checkbox" name="benefit[]" value="Remote" checked>
                                        <span>Opsi Kerja Remote / Hybrid</span>
                                    </label>
                                    <label class="benefit-checkbox-label">
                                        <input type="checkbox" name="benefit[]" value="Bonus" checked>
                                        <span>Bonus Tahunan & THR</span>
                                    </label>
                                    <label class="benefit-checkbox-label">
                                        <input type="checkbox" name="benefit[]" value="Laptop">
                                        <span>Fasilitas Laptop / Gadget Kerja</span>
                                    </label>
                                    <label class="benefit-checkbox-label">
                                        <input type="checkbox" name="benefit[]" value="Training" checked>
                                        <span>Pelatihan & Sertifikasi Gratis</span>
                                    </label>
                                    <label class="benefit-checkbox-label">
                                        <input type="checkbox" name="benefit[]" value="Pantry">
                                        <span>Free Lunch & Snack Pantry</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Media Sosial -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Media Sosial Perusahaan</h3>
                            </div>
                            <div class="social-input-group">
                                <div class="social-icon-wrapper">in</div>
                                <input type="url" name="linkedin" class="form-input" placeholder="URL LinkedIn Perusahaan (https://linkedin.com/company/...)" value="https://linkedin.com/company/lokerin-sample">
                            </div>
                            <div class="social-input-group">
                                <div class="social-icon-wrapper">ig</div>
                                <input type="url" name="instagram" class="form-input" placeholder="URL Instagram Perusahaan (https://instagram.com/...)" value="https://instagram.com/lokerin.id">
                            </div>
                        </div>

                    </div>

                    <!-- Right Column (Sidebar Info & Legality) -->
                    <div>
                        <!-- Status Verifikasi (Legalitas) -->
                        <div class="verification-card">
                            <div class="verification-header">
                                <div class="verification-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                </div>
                                <div class="verification-title">Status Verifikasi</div>
                            </div>
                            <p class="verification-text">Lengkapi berkas legalitas untuk meningkatkan rasa percaya pelamar kerja.</p>
                            <div class="verification-progress">
                                <div class="progress-item">
                                    <div class="progress-check">✓</div>
                                    <span style="color: #1e40af; font-weight: 500;">Email Bisnis</span>
                                </div>
                                <div class="progress-item">
                                    <div class="progress-check">✓</div>
                                    <span style="color: #1e40af; font-weight: 500;">Nomor Telepon</span>
                                </div>
                                <div class="progress-item">
                                    <div class="progress-check pending">!</div>
                                    <span style="color: #64748b;">Dokumen NIB / SIUP (Pending)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Ringkasan Tampilan Publik -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Legal</h3>
                            </div>
                            <div class="info-list">
                                <div class="info-item">
                                    <span class="info-label">Nama Resmi Badan Usaha</span>
                                    <span class="info-value">PT. <?= htmlspecialchars($_SESSION['nama']) ?> Indonesia</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email HRD</span>
                                    <span class="info-value"><?= htmlspecialchars($_SESSION['email']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status Akun</span>
                                    <span class="info-value" style="color: #22c55e; font-weight: bold;">Aktif (Premium)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</body>

</html>