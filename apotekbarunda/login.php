<?php
session_start();

$users = [
    'admin' => ['password' => '123', 'role' => 'admin', 'nama' => 'Administrator'],
    'staff' => ['password' => '123', 'role' => 'staff', 'nama' => 'Staff Apotek'],
    // Pelanggan bisa login dengan nama apapun (tanpa password)
];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strtolower(trim($_POST['username']));
    
    // Untuk admin/staff
    if ($username === 'admin' || $username === 'staff') {
        if (empty($_POST['password']) ){
            $error = "Password diperlukan untuk admin/staff";
        } elseif ($_POST['password'] !== $users[$username]['password']) {
            $error = "Password salah";
        } else {
            $_SESSION['user'] = $users[$username];
            header("Location: {$users[$username]['role']}.php");
            exit;
        }
    } 
    // Untuk pelanggan (bisa login dengan nama apapun)
    else {
        $_SESSION['user'] = [
            'nama' => ucwords($username),
            'role' => 'pelanggan'
        ];
        header("Location: pelanggan.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Apotek Barunda</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .password-field {
      margin-top: 15px;
    }
    .error-message {
      color: red;
      margin-bottom: 15px;
    }
    .login-info {
      margin-top: 20px;
      font-size: 14px;
      color: #555;
      border-top: 1px solid #ddd;
      padding-top: 15px;
    }
    .role-selector {
      margin: 15px 0;
    }
    .role-selector button {
      padding: 8px 15px;
      margin-right: 10px;
      background: #e0e0e0;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .role-selector button.active {
      background: #2e7d32;
      color: white;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">Apotek <span>Barunda</span></div>
    <ul class="nav-links">
      <li><a href="index.php">Beranda</a></li>
      <li><a href="index.php#tentang">Tentang Kami</a></li>
      <li><a href="index.php#kontak">Kontak</a></li>
    </ul>
  </nav>

  <div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
      <p class="error-message"><?= $error ?></p>
    <?php endif; ?>
    
    <form method="POST" id="loginForm">
      <input type="text" name="username" placeholder="Username/Nama" required>
      
      <div class="password-field" id="passwordField">
        <input type="password" name="password" placeholder="Password">
      </div>
      
      <div class="role-selector">
        <button type="button" class="active" data-role="all">Semua</button>
        <button type="button" data-role="admin">Admin</button>
        <button type="button" data-role="staff">Staff</button>
        <button type="button" data-role="pelanggan">Pelanggan</button>
      </div>
      
      <button type="submit" class="btn-login">Login</button>
    </form>

  <footer>
    <div class="footer-container">
      <p><strong>Kontak Apotek Barunda:</strong></p>
      <p>Email: <a href="mailto:apotekbarunda@gmail.com">apotekbarunda@gmail.com</a></p>
    </div>
  </footer>

  <script>
    // Toggle password field
    const roleButtons = document.querySelectorAll('.role-selector button');
    const passwordField = document.getElementById('passwordField');
    
    roleButtons.forEach(button => {
      button.addEventListener('click', function() {
        roleButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        if (this.dataset.role === 'pelanggan') {
          passwordField.style.display = 'none';
        } else {
          passwordField.style.display = 'block';
        }
      });
    });
    
    // Set default to show password field
    passwordField.style.display = 'block';
  </script>
</body>
</html>