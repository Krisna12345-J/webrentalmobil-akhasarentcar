<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "akhasarentcar"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("<script>alert('Koneksi Database Gagal!'); window.history.back();</script>");
}

$nama = $conn->real_escape_string($_POST['nama'] ?? '');
$email = $conn->real_escape_string($_POST['email'] ?? '');
$whatsapp = $conn->real_escape_string($_POST['whatsapp'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

if ($password !== $konfirmasi) {
    echo "<script>alert('Konfirmasi Kata Sandi tidak cocok!'); window.location.href='../views/auth/daftar.php?error=mismatch';</script>";
    exit();
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$check_email = $conn->query("SELECT email FROM users WHERE email = '$email'");
if ($check_email->num_rows > 0) {
    echo "<script>alert('Email sudah terdaftar! Gunakan email lain.'); window.location.href='../views/auth/daftar.php?error=exists';</script>";
    exit();
}

$sql = "INSERT INTO users (nama_lengkap, email, password, no_telepon, role) 
        VALUES ('$nama', '$email', '$hashed_password', '$whatsapp', 'customer')";

if ($conn->query($sql) === TRUE) {
    // FITUR AUTO-LOGIN
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['nama'] = $nama;
    $_SESSION['role'] = 'customer';
    
    // Arahkan ke Halaman Utama
    echo "<script>alert('Selamat bergabung! Akun berhasil dibuat.'); window.location.href='../../index.php';</script>";
} else {
    echo "<script>alert('Terjadi kesalahan database: " . $conn->error . "'); window.history.back();</script>";
}

$conn->close();
?>