<?php
session_start();
require_once 'data_obat.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pelanggan') {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}
$data_obat = loadObat();

// Tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_keranjang'])) {
    $id_obat = $_POST['id_obat'];
    $jumlah = $_POST['jumlah'];
    
    $obat = getObatById($id_obat);
    
    if ($obat && $obat['stok'] >= $jumlah) {
        if (isset($_SESSION['keranjang'][$id_obat])) {
            $_SESSION['keranjang'][$id_obat]['jumlah'] += $jumlah;
        } else {
            $_SESSION['keranjang'][$id_obat] = [
                'nama' => $obat['nama'],
                'harga' => $obat['harga'],
                'jumlah' => $jumlah
            ];
        }
        
        updateStokObat($id_obat, -$jumlah);
        $success = "Obat berhasil ditambahkan ke keranjang";
    } else {
        $error = "Stok tidak mencukupi";
    }
}

// Checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $total = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $total += $item['harga'] * $item['jumlah'];
    }
    
    // Simpan transaksi
    $_SESSION['transaksi'] = [
        'waktu' => date('Y-m-d H:i:s'),
        'items' => $_SESSION['keranjang'],
        'total' => $total
    ];
    
    // Kosongkan keranjang
    $_SESSION['keranjang'] = [];
    
    header("Location: resi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pelanggan - Apotek Barunda</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="navbar">
    <div class="logo">Apotek <span>Barunda</span></div>
    <ul class="nav-links">
      <li><a href="pelanggan.php">Beranda</a></li>
      <li><a href="keranjang.php">Keranjang (<?= array_sum(array_column($_SESSION['keranjang'], 'jumlah')) ?>)</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="container">
    <h2>Selamat berbelanja, <?= $_SESSION['user']['nama'] ?></h2>
    
    <?php if (isset($success)): ?>
      <p class="success"><?= $success ?></p>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
    
    <div class="daftar-obat">
      <h3>Daftar Obat Tersedia</h3>
      <div class="obat-grid">
        <?php foreach ($data_obat as $obat): ?>
        <div class="obat-card">
          <h4><?= $obat['nama'] ?></h4>
          <p>Rp <?= number_format($obat['harga'], 0, ',', '.') ?></p>
          <p>Stok: <?= $obat['stok'] ?></p>
          <?php if ($obat['stok'] > 0): ?>
          <form method="POST">
            <input type="hidden" name="id_obat" value="<?= $obat['id'] ?>">
            <input type="number" name="jumlah" min="1" max="<?= $obat['stok'] ?>" value="1">
            <button type="submit" name="tambah_keranjang" class="btn">Tambah ke Keranjang</button>
          </form>
          <?php else: ?>
          <p class="error">Stok habis</p>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <footer>
    <div class="footer-container">
      <p>&copy; 2024 Apotek Barunda</p>
    </div>
  </footer>
</body>
</html>