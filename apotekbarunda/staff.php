<?php
session_start();
require_once 'data_obat.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}
$data_obat = loadObat();

// Update stok obat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stok'])) {
    $id_obat = $_POST['id_obat'];
    $jumlah = $_POST['jumlah'];
    
    if (updateStokObat($id_obat, $jumlah)) {
        $success = "Stok obat berhasil diupdate";
    } else {
        $error = "Gagal mengupdate stok obat";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Staff - Apotek Barunda</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="navbar">
    <div class="logo">Apotek <span>Barunda</span></div>
    <ul class="nav-links">
      <li><a href="staff.php">Dashboard</a></li>
      <li><a href="#update-stok">Update Stok</a></li>
      <li><a href="#daftar-obat">Daftar Obat</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="hero">
    <h1>Selamat datang, <?= $_SESSION['user']['nama'] ?></h1>
    <p>Panel Staff Apotek Barunda</p>
  </div>

  <section class="container">
    <?php if (isset($success)): ?>
      <p class="success"><?= $success ?></p>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <div id="update-stok" class="form-container">
      <h2>Update Stok Obat</h2>
      <form method="POST">
        <div class="form-group">
          <label for="id_obat">Pilih Obat</label>
          <select id="id_obat" name="id_obat" required>
            <?php foreach ($data_obat as $obat): ?>
            <option value="<?= $obat['id'] ?>"><?= $obat['nama'] ?> (Stok: <?= $obat['stok'] ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="jumlah">Jumlah (positif untuk menambah, negatif untuk mengurangi)</label>
          <input type="number" id="jumlah" name="jumlah" required>
        </div>
        <button type="submit" name="update_stok" class="btn">Update Stok</button>
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
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data_obat as $obat): ?>
          <tr>
            <td><?= $obat['id'] ?></td>
            <td><?= $obat['nama'] ?></td>
            <td>Rp <?= number_format($obat['harga'], 0, ',', '.') ?></td>
            <td><?= $obat['stok'] ?></td>
          </tr>
          <?php endforeach; ?>
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