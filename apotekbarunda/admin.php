<?php
session_start();
require_once 'data_obat.php';

// Pastikan hanya admin yang bisa akses halaman ini
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

// Tambah obat baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_obat'])) {
  $data_obat = loadObat(); // Ambil data lama
  $data_obat[] = [
    'id' => count($data_obat) + 1, // Menambahkan ID baru secara otomatis
    'nama' => $_POST['nama'],
    'harga' => $_POST['harga'],
    'stok' => $_POST['stok']
  ];
  saveObat($data_obat); // Simpan data yang sudah diupdate
}

// Ambil data obat untuk ditampilkan
$data_obat = getAllObat(); // Pastikan data obat sudah ada
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Admin - Apotek Barunda</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="navbar">
    <div class="logo">Apotek <span>Barunda</span></div>
    <ul class="nav-links">
      <li><a href="admin.php">Dashboard</a></li>
      <li><a href="#tambah-obat">Tambah Obat</a></li>
      <li><a href="#daftar-obat">Daftar Obat</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="hero">
    <h1>Selamat datang, <?= $_SESSION['user']['nama'] ?></h1>
    <p>Panel Administrasi Apotek Barunda</p>
  </div>

  <section class="container">
    <?php if (isset($success)): ?>
      <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <div id="tambah-obat" class="form-container">
      <h2>Tambah Obat Baru</h2>
      <form method="POST">
        <div class="form-group">
          <label for="nama">Nama Obat</label>
          <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
          <label for="harga">Harga</label>
          <input type="number" id="harga" name="harga" required>
        </div>
        <div class="form-group">
          <label for="stok">Stok</label>
          <input type="number" id="stok" name="stok" required>
        </div>
        <button type="submit" name="tambah_obat" class="btn">Tambah Obat</button>
      </form>
    </div>

    <div id="daftar-obat" class="table-container">
      <h2>Daftar Obat</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Obat</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data_obat) && is_array($data_obat)): ?>
            <?php foreach ($data_obat as $obat): ?>
              <tr>
                <td><?= $obat['id'] ?></td>
                <td><?= $obat['nama'] ?></td>
                <td>Rp <?= number_format($obat['harga'], 0, ',', '.') ?></td>
                <td><?= $obat['stok'] ?></td>
                <td>
                  <a href="edit_obat.php?id=<?= $obat['id'] ?>" class="action-btn edit-btn">Edit</a>
                  <a href="hapus_obat.php?id=<?= $obat['id'] ?>" class="action-btn delete-btn">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">Data obat tidak tersedia</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <footer>
    <div class="footer-container">
      <p>&copy; 2024 Apotek Barunda. Seluruh hak cipta dilindungi.</p>
    </div>
  </footer>
</body>

</html>
