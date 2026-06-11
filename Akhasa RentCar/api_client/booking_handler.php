<?php
session_start();
header('Content-Type: application/json'); // Wajib JSON untuk AJAX

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi berakhir. Silakan masuk sistem kembali.']);
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "akhasarentcar";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi Database Gagal!']);
    exit();
}

$id_user = $_POST['id_user'] ?? '';
$id_mobil = $_POST['id_mobil'] ?? '';
$tgl_mulai = $_POST['tgl_mulai'] ?? '';
$tgl_selesai = $_POST['tgl_selesai'] ?? '';
$total_biaya = $_POST['total_biaya'] ?? 0;

if(empty($id_mobil) || empty($tgl_mulai) || empty($tgl_selesai)) {
    echo json_encode(['status' => 'error', 'message' => 'Data reservasi tidak lengkap!']);
    exit();
}

$createTable = "CREATE TABLE IF NOT EXISTS bookings (
    id_booking INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_mobil INT NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    total_biaya INT NOT NULL,
    status_pembayaran ENUM('Menunggu', 'Lunas', 'Ditolak') DEFAULT 'Menunggu',
    bukti_transfer VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createTable);

$sql = "INSERT INTO bookings (id_user, id_mobil, tgl_mulai, tgl_selesai, total_biaya, status_pembayaran) 
        VALUES ('$id_user', '$id_mobil', '$tgl_mulai', '$tgl_selesai', '$total_biaya', 'Menunggu')";

if ($conn->query($sql) === TRUE) {
    
    // PERBAIKAN BUG DISINI: Hanya menggunakan id_mobil sesuai tabel Anda
    $conn->query("UPDATE cars SET status = 'Disewa' WHERE id_mobil = '$id_mobil'");
    
    // Berikan respon SUKSES ke JavaScript
    echo json_encode([
        'status' => 'success',
        'message' => 'Reservasi armada berhasil diamankan!',
        'redirect' => 'views/customer/dashboard.php'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memproses sistem: ' . $conn->error]);
}

$conn->close();
?>