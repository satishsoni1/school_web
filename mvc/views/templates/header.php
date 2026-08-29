<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' – DWPS Bhilai' : 'DWPS Holistic Progress Card' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <div class="brand-icon">🏫</div>
        <div class="brand-text">
            <span class="brand-name">DWPS Bhilai</span>
            <span class="brand-sub">Holistic Progress Card System</span>
        </div>
    </div>
    <div class="navbar-links">
        <a href="<?= base_url('students') ?>" class="nav-link">📋 Students</a>
        <a href="<?= base_url('students/add') ?>" class="nav-link nav-link-primary">+ Add Student</a>
    </div>
</nav>

<main class="main-content">
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-error"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>
