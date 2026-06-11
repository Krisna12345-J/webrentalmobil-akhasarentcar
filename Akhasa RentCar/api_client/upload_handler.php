<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Akses Ditolak");
}

$conn = new mysqli("localhost", "root", "", "akhasarentcar");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['bukti_transfer'])) {
    $id_booking = $conn->real_escape_string($_POST['id_booking']);
    
    $target_dir = "../uploads/receipts/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $file_extension = strtolower(pathinfo($_FILES["bukti_transfer"]["name"], PATHINFO_EXTENSION));
    $new_filename = "RSV-" . $id_booking . "-" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Validasi format file
    $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
    if (!in_array($file_extension, $allowed_types)) {
        die("<script>alert('Format file tidak didukung!'); window.history.back();</script>");
    }

    if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $target_file)) {
        $db_filepath = "uploads/receipts/" . $new_filename;
        
        // KUNCI REAL-TIME: Pastikan status diubah menjadi "Menunggu"
        $sql = "UPDATE bookings SET bukti_transfer = '$db_filepath', status_pembayaran = 'Menunggu' WHERE id_booking = '$id_booking'";
        if ($conn->query($sql)) {
            echo "<script>alert('Bukti pembayaran berhasil diunggah! Menunggu validasi admin.'); window.location.href='../views/customer/dashboard.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui database.'); window.history.back();</script>";
        }
    }
}
$conn->close();
?>