<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/auth/masuk.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "akhasarentcar");
if ($conn->connect_error) { die("Koneksi Gagal"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_booking = $conn->real_escape_string($_POST['id_booking']);
    $id_mobil = $conn->real_escape_string($_POST['id_mobil']);
    $aksi = $_POST['aksi'];

    if ($aksi === 'setuju') {
        $sql = "UPDATE bookings SET status_pembayaran = 'Lunas' WHERE id_booking = '$id_booking'";
        
        if($conn->query($sql)) {
            // Ambil data untuk WA
            $sql_data = "SELECT b.*, u.nama_lengkap, u.no_telepon, c.nama_mobil 
                         FROM bookings b 
                         JOIN users u ON b.id_user = u.id_user 
                         JOIN cars c ON b.id_mobil = c.id_mobil 
                         WHERE b.id_booking = '$id_booking'";
            $res_data = $conn->query($sql_data);
            
            if($res_data && $res_data->num_rows > 0) {
                $data = $res_data->fetch_assoc();
                
                // --- INTEGRASI WA FONNTE ---
                $target_wa = "085692370364"; 
                $pesan = "Halo *" . $data['nama_lengkap'] . "*,\n\n";
                $pesan .= "Pembayaran reservasi *#RSV-" . $id_booking . "* telah *BERHASIL DIKONFIRMASI (LUNAS)*. 🎉\n\n";
                $pesan .= "🚘 Armada: *" . $data['nama_mobil'] . "*\n";
                $pesan .= "📅 Jadwal: " . date('d M Y', strtotime($data['tgl_mulai'])) . " s/d " . date('d M Y', strtotime($data['tgl_selesai'])) . "\n\n";
                $pesan .= "Silakan unduh Invoice di Dashboard Anda. Armada siap diambil! Terima kasih telah menggunakan Akhasa Rent Car.";

                $token = 'yxEZupahpEBDdtq2q7wP'; 

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.fonnte.com/send',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array(
                    'target' => $target_wa,
                    'message' => $pesan,
                    'countryCode' => '62',
                  ),
                  CURLOPT_HTTPHEADER => array("Authorization: $token"),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }
            
            // SET FLASH MESSAGE SUKSES
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_title'] = 'Validasi Berhasil!';
            $_SESSION['alert_text'] = 'Transaksi disetujui (Lunas) & Notifikasi WA terkirim otomatis.';
        }

    } elseif ($aksi === 'tolak') {
        $conn->query("UPDATE bookings SET status_pembayaran = 'Ditolak' WHERE id_booking = '$id_booking'");
        $conn->query("UPDATE cars SET status = 'Tersedia' WHERE id_mobil = '$id_mobil'");
        
        // SET FLASH MESSAGE DITOLAK
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_title'] = 'Dibatalkan';
        $_SESSION['alert_text'] = 'Transaksi ditolak. Armada kembali Tersedia di Katalog Utama.';

    } elseif ($aksi === 'selesai') {
        $conn->query("UPDATE cars SET status = 'Tersedia' WHERE id_mobil = '$id_mobil'");
        
        // SET FLASH MESSAGE SELESAI
        $_SESSION['alert_type'] = 'success';
        $_SESSION['alert_title'] = 'Sewa Selesai';
        $_SESSION['alert_text'] = 'Armada telah dikembalikan ke garasi dan berstatus Tersedia.';
    }

    // Redirect bersih tanpa layar putih
    header("Location: ../views/admin/panel.php");
    exit();
}
$conn->close();
?>