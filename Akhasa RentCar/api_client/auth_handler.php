<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "akhasarentcar";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Database Gagal!");
}

$email = $conn->real_escape_string($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: ../views/auth/masuk.php?error=empty");
    exit();
}

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    
    if (password_verify($password, $user_data['password'])) {
        
        $_SESSION['user_id'] = $user_data['id_user']; 
        $_SESSION['nama'] = $user_data['nama_lengkap'];
        $_SESSION['role'] = strtolower($user_data['role']);

        if ($_SESSION['role'] === 'admin') {
            header("Location: ../views/admin/panel.php"); 
        } else {
            // PERBAIKAN RUTE: Mundur 1 langkah saja ke halaman utama Akhasa
            header("Location: ../index.php"); 
        }
        exit();
        
    } else {
        header("Location: ../views/auth/masuk.php?error=failed");
        exit();
    }
} else {
    header("Location: ../views/auth/masuk.php?error=failed");
    exit();
}

$conn->close();
?>