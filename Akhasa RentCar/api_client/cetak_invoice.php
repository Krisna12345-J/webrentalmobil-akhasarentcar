<?php
session_start();
if (!isset($_SESSION['user_id'])) { die("Akses ditolak."); }

$conn = new mysqli("localhost", "root", "", "akhasarentcar");

if(isset($_GET['id'])) {
    $id_booking = $conn->real_escape_string($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT b.*, u.nama_lengkap, u.email, u.no_telepon, c.nama_mobil, c.harga_per_hari 
            FROM bookings b 
            JOIN users u ON b.id_user = u.id_user 
            JOIN cars c ON b.id_mobil = c.id_mobil 
            WHERE b.id_booking = '$id_booking' AND b.id_user = '$user_id' AND b.status_pembayaran = 'Lunas'";
    
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $d1 = new DateTime($data['tgl_mulai']);
        $d2 = new DateTime($data['tgl_selesai']);
        $diff = $d2->diff($d1)->days;
        $durasi = ($diff == 0) ? 1 : $diff;
    } else {
        die("Data Invoice tidak ditemukan atau belum Lunas.");
    }
} else {
    die("ID Booking tidak valid.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #RSV-<?= $data['id_booking'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .invoice-box { max-width: 800px; margin: 40px auto; padding: 40px; background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-top: 8px solid #0A192F; }
        @media print { body { background: white; margin: 0; } .invoice-box { box-shadow: none; margin: 0; padding: 0; } }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-box rounded-lg">
        <div class="flex justify-between items-start mb-10 border-b border-gray-100 pb-8">
            <div>
                <h1 class="text-3xl font-black text-[#0A192F] tracking-tighter">INVOICE DIGITAL</h1>
                <p class="text-sm font-semibold text-gray-500 mt-1">#RSV-<?= $data['id_booking'] ?></p>
                <p class="text-xs text-gray-400 mt-2">Diterbitkan: <?= date('d M Y, H:i', strtotime($data['created_at'])) ?></p>
            </div>
            <div class="text-right">
                <div class="text-xl font-black text-[#0A192F] tracking-tighter mb-2">AKHASA<span class="text-blue-600">RENT</span></div>
                <p class="text-xs text-gray-500">Komplek Bantar Gebang, Bekasi</p>
                <p class="text-xs text-gray-500">admin@akhasarentcar.com</p>
            </div>
        </div>
        <div class="flex justify-between mb-10">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Ditagihkan Kepada:</p>
                <p class="text-base font-bold text-gray-900"><?= htmlspecialchars($data['nama_lengkap']) ?></p>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($data['no_telepon']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status:</p>
                <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-wider rounded border border-emerald-200">LUNAS</span>
            </div>
        </div>
        <table class="w-full text-left border-collapse mb-10">
            <tr class="bg-gray-50 border-y border-gray-200">
                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">Deskripsi Layanan</th>
                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase text-center">Durasi</th>
                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase text-right">Subtotal</th>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-4 px-4">
                    <p class="font-bold text-[#0A192F]"><?= htmlspecialchars($data['nama_mobil']) ?></p>
                    <p class="text-xs text-gray-500">Jadwal: <?= date('d M Y', strtotime($data['tgl_mulai'])) ?> - <?= date('d M Y', strtotime($data['tgl_selesai'])) ?></p>
                </td>
                <td class="py-4 px-4 text-center text-sm font-medium"><?= $durasi ?> Hari</td>
                <td class="py-4 px-4 text-right text-sm font-bold text-[#0A192F]">Rp <?= number_format($data['total_biaya'], 0, ',', '.') ?></td>
            </tr>
        </table>
        <div class="flex justify-end pt-4 border-t border-gray-200">
            <span class="text-lg font-black text-gray-500 mr-4">Total Dibayar:</span>
            <span class="text-lg font-black text-[#0A192F]">Rp <?= number_format($data['total_biaya'], 0, ',', '.') ?></span>
        </div>
        <div class="mt-16 text-center text-xs text-gray-400 font-medium">
            <p>Terima kasih telah mempercayakan perjalanan Anda.</p>
            <p>Invoice ini sah sebagai bukti pembayaran digital tanpa tanda tangan basah.</p>
        </div>
    </div>
</body>
</html>