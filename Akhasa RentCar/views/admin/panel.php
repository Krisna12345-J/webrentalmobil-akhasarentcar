<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/masuk.php");
    exit();
}

// 1. TANGKAP FLASH MESSAGE DARI VALIDASI HANDLER
$alert_type = '';
$alert_title = '';
$alert_text = '';
if (isset($_SESSION['alert_type'])) {
    $alert_type = $_SESSION['alert_type'];
    $alert_title = $_SESSION['alert_title'];
    $alert_text = $_SESSION['alert_text'];
    // Hapus session setelah ditangkap agar popup tidak muncul terus saat di-refresh
    unset($_SESSION['alert_type'], $_SESSION['alert_title'], $_SESSION['alert_text']);
}

$conn = new mysqli("localhost", "root", "", "akhasarentcar");
if ($conn->connect_error) { die("Koneksi Database Gagal!"); }

// Total Pendapatan
$q_pendapatan = $conn->query("SELECT SUM(total_biaya) as total FROM bookings WHERE status_pembayaran = 'Lunas'");
$pendapatan = $q_pendapatan->fetch_assoc()['total'] ?? 0;

// Antrean Validasi
$q_antrean = $conn->query("SELECT COUNT(id_booking) as jumlah FROM bookings WHERE status_pembayaran = 'Menunggu' AND bukti_transfer IS NOT NULL AND bukti_transfer != ''");
$antrean = $q_antrean->fetch_assoc()['jumlah'] ?? 0;

// Data Chart (Tren)
$q_trend = $conn->query("SELECT DATE_FORMAT(created_at, '%b %Y') as bulan, SUM(total_biaya) as total FROM bookings WHERE status_pembayaran = 'Lunas' GROUP BY DATE_FORMAT(created_at, '%Y-%m') ORDER BY DATE_FORMAT(created_at, '%Y-%m') ASC LIMIT 6");
$trend_labels = []; $trend_data = [];
while($row = $q_trend->fetch_assoc()){ $trend_labels[] = $row['bulan']; $trend_data[] = $row['total']; }

// Data Chart (Top Cars)
$q_top_cars = $conn->query("SELECT c.nama_mobil, COUNT(b.id_booking) as jumlah FROM bookings b JOIN cars c ON b.id_mobil = c.id_mobil WHERE b.status_pembayaran = 'Lunas' GROUP BY c.id_mobil ORDER BY jumlah DESC LIMIT 5");
$car_labels = []; $car_data = [];
while($row = $q_top_cars->fetch_assoc()){ $car_labels[] = $row['nama_mobil']; $car_data[] = $row['jumlah']; }

// Ambil Tabel
$sql_bookings = "SELECT b.*, u.nama_lengkap, c.nama_mobil, c.status AS status_mobil FROM bookings b JOIN users u ON b.id_user = u.id_user JOIN cars c ON b.id_mobil = c.id_mobil ORDER BY b.created_at DESC";
$result_bookings = $conn->query($sql_bookings);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Akhasarentcar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="text-slate-800 flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#0A192F] text-slate-300 flex flex-col hidden md:flex flex-shrink-0 shadow-xl z-20">
        <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-[#071324]">
            <a href="panel.php" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-[#1D4ED8] rounded-lg flex items-center justify-center shadow-md">
                    <span class="text-white font-black text-xs">A</span>
                </div>
                <span class="text-lg font-black text-white tracking-tighter">AKHASA<span class="text-[#1D4ED8]">ADMIN</span></span>
            </a>
        </div>
        
        <div class="flex-grow py-6 px-4">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 mb-4">Navigasi Utama</p>
            <nav class="space-y-2">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 bg-[#1D4ED8] text-white rounded-lg font-semibold text-sm shadow-md shadow-blue-900/20 transition-all"><i class="fas fa-chart-pie w-5 text-center"></i> Overview</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-colors"><i class="fas fa-car w-5 text-center"></i> Manajemen Armada</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-colors"><i class="fas fa-check-circle w-5 text-center"></i> Validasi Pembayaran</a>
            </nav>
        </div>
        
        <div class="p-4 border-t border-slate-800/50 bg-[#071324]">
            <a href="../../api_client/logout_handler.php" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg font-medium text-sm transition-colors">
                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar Sistem
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden relative">
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0 z-10 shadow-sm">
            <h1 class="text-lg font-bold text-[#0A192F]">Dashboard Operasional & Analitik</h1>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-bold text-[#0A192F]"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Administrator</p>
                </div>
                <div class="w-10 h-10 bg-[#F8FAFC] rounded-full flex items-center justify-center text-[#1D4ED8] border border-slate-200 shadow-sm"><i class="fas fa-user"></i></div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex justify-between items-start hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-bold text-slate-400 mb-1 uppercase tracking-widest">Total Pendapatan</p>
                        <h2 class="text-2xl font-black text-[#0A192F]">Rp <?= number_format($pendapatan, 0, ',', '.') ?></h2>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-[#1D4ED8] rounded-xl flex items-center justify-center"><i class="fas fa-wallet text-lg"></i></div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex justify-between items-start hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-xs font-bold text-slate-400 mb-1 uppercase tracking-widest">Menunggu Validasi</p>
                        <h2 class="text-2xl font-black <?= $antrean > 0 ? 'text-amber-500' : 'text-[#0A192F]' ?>"><?= $antrean ?> Transaksi</h2>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center border border-amber-100"><i class="fas fa-inbox text-lg"></i></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:col-span-2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Tren Pendapatan Lunas</h3>
                    <div class="relative h-64 w-full"><canvas id="revChart"></canvas></div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Top 5 Armada Terlaris</h3>
                    <div class="relative h-64 w-full"><canvas id="carChart"></canvas></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-[#F8FAFC]">
                    <h3 class="font-bold text-[#0A192F] text-sm"><i class="fas fa-list-alt text-[#1D4ED8] mr-2"></i> Manajemen Transaksi Operasional</h3>
                    <div class="flex gap-3">
                        <a href="../../api_client/export_handler.php" target="_blank" class="text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 px-4 py-2 rounded-lg flex items-center gap-1.5 hover:bg-emerald-600 hover:text-white transition"><i class="fas fa-file-excel"></i> Ekspor Laporan</a>
                        <a href="panel.php" class="text-xs font-bold text-slate-500 hover:text-[#0A192F] flex items-center gap-1.5 px-3 bg-white border border-slate-200 rounded-lg py-2 hover:bg-slate-50 transition"><i class="fas fa-sync-alt"></i> Segarkan</a>
                    </div>
                </div>
                
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 bg-white">
                            <th class="px-6 py-4">Detail Referensi</th>
                            <th class="px-6 py-4">Identitas Penyewa</th>
                            <th class="px-6 py-4">Spesifikasi Layanan</th>
                            <th class="px-6 py-4">Tagihan & Berkas</th>
                            <th class="px-6 py-4 text-center">Status Operasional</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-50 bg-white">
                        <?php while($row = $result_bookings->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50 group transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#0A192F]">#RSV-<?= $row['id_booking'] ?></div>
                                    <div class="text-[10px] text-slate-400 mt-1 font-medium"><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#0A192F]"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                    <div class="text-[10px] text-slate-400 mt-1 font-medium">ID Pengguna: <?= $row['id_user'] ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#0A192F]"><?= htmlspecialchars($row['nama_mobil']) ?></div>
                                    <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1.5 font-medium"><i class="far fa-calendar text-[#1D4ED8]"></i> <?= date('d M', strtotime($row['tgl_mulai'])) ?> &mdash; <?= date('d M', strtotime($row['tgl_selesai'])) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#1D4ED8]">Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?></div>
                                    <div class="mt-1.5">
                                        <?php if(!empty($row['bukti_transfer'])): ?>
                                            <a href="../../<?= htmlspecialchars($row['bukti_transfer']) ?>" target="_blank" class="inline-block text-[10px] font-bold text-[#1D4ED8] bg-blue-50 px-2 py-1 rounded border border-blue-100 hover:bg-[#1D4ED8] hover:text-white transition-colors"><i class="fas fa-external-link-alt mr-1"></i> Lihat Bukti</a>
                                        <?php else: ?>
                                            <span class="text-[10px] text-slate-400 italic font-medium">Belum diunggah</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    
                                    <?php if($row['status_pembayaran'] === 'Lunas'): ?>
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="px-3 py-1 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">LUNAS</span>
                                            <?php if(strtolower($row['status_mobil']) === 'disewa'): ?>
                                                <form action="../../api_client/validasi_handler.php" method="POST" onsubmit="handleFormSubmit(event, this, 'selesai')">
                                                    <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>"><input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>"><input type="hidden" name="aksi" value="selesai">
                                                    <button type="submit" class="text-[10px] font-bold text-[#1D4ED8] hover:text-blue-800 underline"><i class="fas fa-check-circle mr-1"></i> Selesaikan Sewa</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Sewa Berakhir</span>
                                            <?php endif; ?>
                                        </div>

                                    <?php elseif($row['status_pembayaran'] === 'Ditolak'): ?>
                                        <span class="px-3 py-1 rounded text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">DIBATALKAN</span>
                                        
                                    <?php elseif(!empty($row['bukti_transfer'])): ?>
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="px-3 py-1 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 animate-pulse">Menunggu Validasi</span>
                                            <div class="flex gap-2 mt-1">
                                                <form action="../../api_client/validasi_handler.php" method="POST" onsubmit="handleFormSubmit(event, this, 'setuju')">
                                                    <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>"><input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>"><input type="hidden" name="aksi" value="setuju">
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white hover:bg-emerald-600 text-[10px] font-bold rounded shadow-sm transition-colors flex items-center gap-1"><i class="fas fa-check"></i> Setuju</button>
                                                </form>
                                                <form action="../../api_client/validasi_handler.php" method="POST" onsubmit="handleFormSubmit(event, this, 'tolak')">
                                                    <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>"><input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>"><input type="hidden" name="aksi" value="tolak">
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-500 text-white hover:bg-rose-600 text-[10px] font-bold rounded shadow-sm transition-colors flex items-center gap-1"><i class="fas fa-times"></i> Tolak</button>
                                                </form>
                                            </div>
                                        </div>

                                    <?php else: ?>
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="px-3 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">Belum Bayar</span>
                                            <form action="../../api_client/validasi_handler.php" method="POST" onsubmit="handleFormSubmit(event, this, 'batal')">
                                                <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>">
                                                <input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>">
                                                <input type="hidden" name="aksi" value="tolak">
                                                <button type="submit" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-[10px] font-bold rounded shadow-sm transition-colors flex items-center gap-1">
                                                    <i class="fas fa-ban"></i> Batalkan
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // FUNGSI KONFIRMASI (SEBELUM SUBMIT)
        function handleFormSubmit(event, form, type) {
            event.preventDefault();
            let swalConfig = {};

            if (type === 'setuju') {
                swalConfig = { title: "Setujui Pembayaran?", text: "Pelanggan akan menerima notifikasi otomatis dan status pesanan menjadi LUNAS.", icon: "info", confirmButtonText: "Ya, Setujui!", confirmButtonColor: "#10b981" };
            } else if (type === 'tolak') {
                swalConfig = { title: "Tolak Pembayaran?", text: "Bukti transfer tidak valid? Armada akan kembali Tersedia di Katalog Utama.", icon: "error", confirmButtonText: "Ya, Tolak!", confirmButtonColor: "#f43f5e" };
            } else if (type === 'batal') {
                swalConfig = { title: "Batalkan Pesanan?", text: "Pesanan ini akan dibatalkan secara sistem dan mobil langsung kembali Tersedia.", icon: "warning", confirmButtonText: "Ya, Batalkan!", confirmButtonColor: "#f43f5e" };
            } else if (type === 'selesai') {
                swalConfig = { title: "Selesaikan Masa Sewa?", text: "Sistem akan merubah status armada menjadi Tersedia kembali.", icon: "question", confirmButtonText: "Selesai & Kembalikan", confirmButtonColor: "#1D4ED8" };
            }

            Swal.fire({
                ...swalConfig, showCancelButton: true, cancelButtonText: "Kembali", cancelButtonColor: "#94a3b8", reverseButtons: true
            }).then((result) => { if (result.isConfirmed) { form.submit(); } });
        }

        // TRIGGER POPUP SETELAH PROSES DATABASE SELESAI (FLASH MESSAGE)
        let alertType = '<?= $alert_type ?>';
        let alertTitle = '<?= $alert_title ?>';
        let alertText = '<?= $alert_text ?>';

        if (alertType !== '') {
            Swal.fire({
                title: alertTitle,
                text: alertText,
                icon: alertType,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#1D4ED8'
            });
        }

        // CHART TREN PENDAPATAN
        let tLabels = <?= json_encode($trend_labels) ?>;
        let tData = <?= json_encode($trend_data) ?>;
        if(tLabels.length === 0) { tLabels = ['Belum Ada Transaksi']; tData = [0]; }

        const revCtx = document.getElementById('revChart').getContext('2d');
        new Chart(revCtx, { type: 'line', data: { labels: tLabels, datasets: [{ data: tData, borderColor: '#1D4ED8', backgroundColor: 'rgba(29, 78, 216, 0.1)', borderWidth: 3, fill: true, tension: 0.4 }] }, options: { plugins: { legend: { display: false } }, maintainAspectRatio: false } });

        // CHART TOP ARMADA
        let cLabels = <?= json_encode($car_labels) ?>;
        let cData = <?= json_encode($car_data) ?>;
        let cColors = ['#1D4ED8', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'];
        if(cLabels.length === 0) { cLabels = ['Belum Ada Transaksi Lunas']; cData = [1]; cColors = ['#e2e8f0']; }

        const carCtx = document.getElementById('carChart').getContext('2d');
        new Chart(carCtx, { type: 'doughnut', data: { labels: cLabels, datasets: [{ data: cData, backgroundColor: cColors, borderWidth: 0 }] }, options: { cutout: '75%', maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } });
    </script>
</body>
</html>