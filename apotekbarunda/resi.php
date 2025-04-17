<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pelanggan' || !isset($_SESSION['transaksi'])) {
    header("Location: pelanggan.php");
    exit;
}

$transaksi = $_SESSION['transaksi'];
unset($_SESSION['transaksi']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Resi Pembelian - Apotek Barunda</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .resi-container {
      max-width: 600px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .resi-header {
      text-align: center;
      margin-bottom: 20px;
    }
    .resi-detail {
      margin: 20px 0;
    }
    .item-list {
      margin: 15px 0;
    }
    .item {
      display: flex;
      justify-content: space-between;
      margin: 5px 0;
    }
    .total {
      font-weight: bold;
      border-top: 1px solid #ddd;
      padding-top: 10px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="logo">Apotek <span>Barunda</span></div>
    <ul class="nav-links">
      <li><a href="pelanggan.php">Beranda</a></li>
      <li><a href="keranjang.php">Keranjang</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="resi-container">
    <div class="resi-header">
      <h2>Resi Pembelian</h2>
      <p>Apotek Barunda</p>
      <p>Tanggal: <?= $transaksi['waktu'] ?></p>
    </div>
    
    <div class="resi-detail">
      <p>Pelanggan: <?= $_SESSION['user']['nama'] ?></p>
    </div>
    
    <div class="item-list">
      <h3>Daftar Pembelian:</h3>
      <?php foreach ($transaksi['items'] as $item): ?>
      <div class="item">
        <span><?= $item['nama'] ?> (<?= $item['jumlah'] ?> x Rp <?= number_format($item['harga'], 0, ',', '.') ?>)</span>
        <span>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    
    <div class="total">
      <span>Total Pembelian:</span>
      <span>Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></span>
    </div>
    
    <div style="text-align:center; margin-top:30px;">
      <p>Terima kasih telah berbelanja di Apotek Barunda!</p>
      <a href="pelanggan.php" class="btn">Kembali ke Beranda</a>
    </div>
  </div>

  <footer>
    <div class="footer-container">
      <p>&copy; 2024 Apotek Barunda</p>
    </div>
  </footer>
</body>
</html>