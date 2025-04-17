<?php
session_start();
require_once 'data_obat.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if (isset($_POST['hapus_item'])) {
    $kode = $_POST['kode'];
    unset($_SESSION['keranjang'][$kode]);
}

if (isset($_POST['update_jumlah'])) {
    $kode = $_POST['kode'];
    $jumlah = $_POST['jumlah'];
    if ($jumlah > 0) {
        $_SESSION['keranjang'][$kode]['jumlah'] = $jumlah;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $total = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $total += $item['harga'] * $item['jumlah'];
    }

    $_SESSION['transaksi'] = [
        'waktu' => date('Y-m-d H:i:s'),
        'items' => $_SESSION['keranjang'],
        'total' => $total
    ];

    $_SESSION['keranjang'] = [];
    header("Location: resi.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e6f2e6;
            color: #333;
            padding: 20px;
        }

        h1 {
            color: #2e7d32;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #c8e6c9;
        }

        th {
            background-color: #81c784;
            color: white;
        }

        form {
            display: inline;
        }

        input[type="number"] {
            width: 60px;
            padding: 4px;
        }

        button {
            padding: 6px 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #388e3c;
        }

        .btn-checkout {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #388e3c;
            font-size: 16px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #66bb6a;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
        }

        .back-link:hover {
            background-color: #43a047;
        }

        .empty-msg {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <h1>Keranjang Belanja</h1>
    <?php if (!empty($_SESSION['keranjang'])): ?>
        <form method="post">
            <table>
                <tr>
                    <th>Nama Obat</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($_SESSION['keranjang'] as $kode => $item): ?>
                    <tr>
                        <td><?= $item['nama'] ?></td>
                        <td>Rp<?= number_format($item['harga']) ?></td>
                        <td>
                            <form method="post">
                                <input type="number" name="jumlah" value="<?= $item['jumlah'] ?>" min="1">
                                <input type="hidden" name="kode" value="<?= $kode ?>">
                                <button type="submit" name="update_jumlah">Update</button>
                            </form>
                        </td>
                        <td>Rp<?= number_format($item['harga'] * $item['jumlah']) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="kode" value="<?= $kode ?>">
                                <button type="submit" name="hapus_item">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div style="text-align: center;">
                <button class="btn-checkout" type="submit" name="checkout">Checkout</button>
            </div>
        </form>
    <?php else: ?>
        <div class="empty-msg">
            <p>Keranjang kosong.</p>
        </div>
    <?php endif; ?>

    <div style="text-align: center;">
        <a class="back-link" href="pelanggan.php">Kembali ke Halaman Pelanggan</a>
    </div>
</body>
</html>
