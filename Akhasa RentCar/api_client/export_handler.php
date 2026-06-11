<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/masuk.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "akhasarentcar";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi Database Gagal!");
}

// 1. Ambil Total Pendapatan & Antrean
$q_pendapatan = $conn->query("SELECT SUM(total_biaya) as total FROM bookings WHERE status_pembayaran = 'Lunas'");
$pendapatan = $q_pendapatan->fetch_assoc()['total'] ?? 0;

$q_antrean = $conn->query("SELECT COUNT(id_booking) as jumlah FROM bookings WHERE status_pembayaran = 'Menunggu' AND bukti_transfer IS NOT NULL");
$antrean = $q_antrean->fetch_assoc()['jumlah'] ?? 0;

// 2. QUERY BUSINESS INTELLIGENCE (CHART DATA)
// A. Tren Pendapatan 6 Bulan Terakhir
$q_trend = $conn->query("
    SELECT DATE_FORMAT(created_at, '%b %Y') as bulan, SUM(total_biaya) as total 
    FROM bookings 
    WHERE status_pembayaran = 'Lunas' 
    GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y') 
    ORDER BY DATE_FORMAT(created_at, '%Y-%m') ASC LIMIT 6
");
$trend_labels = []; $trend_data = [];
while($row = $q_trend->fetch_assoc()){
    $trend_labels[] = $row['bulan'];
    $trend_data[] = $row['total'];
}

// B. Armada Paling Sering Disewa (Top 5)
$q_top_cars = $conn->query("
    SELECT c.nama_mobil, COUNT(b.id_booking) as jumlah 
    FROM bookings b 
    JOIN cars c ON b.id_mobil = c.id_mobil 
    WHERE b.status_pembayaran = 'Lunas' 
    GROUP BY c.id_mobil 
    ORDER BY jumlah DESC LIMIT 5
");
$car_labels = []; $car_data = [];
while($row = $q_top_cars->fetch_assoc()){
    $car_labels[] = $row['nama_mobil'];
    $car_data[] = $row['jumlah'];
}

// 3. Ambil Data Semua Transaksi
$sql_bookings = "SELECT b.*, u.nama_lengkap, c.nama_mobil, c.status AS status_mobil 
                 FROM bookings b 
                 JOIN users u ON b.id_user = u.id_user 
                 JOIN cars c ON b.id_mobil = c.id_mobil 
                 ORDER BY b.created_at DESC";
$result_bookings = $conn->query($sql_bookings);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Akhasarentcar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Tambahan Library Chart.js untuk Visualisasi BI -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="text-slate-800 antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#0A192F] text-slate-300 flex flex-col hidden md:flex flex-shrink-0 border-r border-slate-800">
        <div class="h-16 flex items-center px-6 border-b border-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-blue-600 rounded flex items-center justify-center">
                    <span class="text-white font-bold text-xs">A</span>
                </div>
                <span class="text-sm font-bold tracking-wide text-white">AKHASA ADMIN</span>
            </div>
        </div>
        
        <div class="flex-grow py-6 px-3">
            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest px-3 mb-3">Navigasi Utama</p>
            <nav class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 bg-blue-600/10 text-blue-400 rounded-lg font-medium text-sm transition-colors border border-blue-500/20">
                    <i class="fas fa-chart-pie w-4 text-center"></i> Overview
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 rounded-lg font-medium text-sm transition-colors">
                    <i class="fas fa-car w-4 text-center"></i> Manajemen Armada
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 rounded-lg font-medium text-sm transition-colors">
                    <i class="fas fa-check-circle w-4 text-center"></i> Validasi Pembayaran
                </a>
            </nav>
        </div>
        
        <div class="p-4 border-t border-slate-800/50">
            <a href="../../api_client/logout_handler.php" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg font-medium text-sm transition-colors">
                <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar Sistem
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden bg-[#F8FAFC]">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0 z-10 sticky top-0">
            <h1 class="text-lg font-semibold text-slate-800">Dashboard Operasional & Analitik</h1>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800 leading-none"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                    <p class="text-[11px] font-medium text-slate-500 mt-1">Administrator</p>
                </div>
                <div class="w-9 h-9 bg-slate-100 text-slate-600 rounded-full flex items-center justify-center text-sm border border-slate-200">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            
            <!-- KARTU STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-1">Total Pendapatan Finansial</p>
                            <h2 class="text-2xl font-bold text-slate-800">Rp <?= number_format($pendapatan, 0, ',', '.') ?></h2>
                        </div>
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center border border-blue-100">
                            <i class="fas fa-wallet text-sm"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-1">Menunggu Validasi Pembayaran</p>
                            <h2 class="text-2xl font-bold <?= $antrean > 0 ? 'text-amber-600' : 'text-slate-800' ?>"><?= $antrean ?> Transaksi</h2>
                        </div>
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center border border-amber-100">
                            <i class="fas fa-inbox text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRAFIK BUSINESS INTELLIGENCE (Baru) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Grafik Tren Pendapatan -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 lg:col-span-2">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Tren Pendapatan Lunas</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Grafik Armada Terlaris -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Top 5 Armada Terlaris</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="topCarsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- TABEL MANAJEMEN TRANSAKSI -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800 text-sm">Manajemen Transaksi</h3>
                    <div class="flex gap-3">
                        <!-- Tombol Ekspor Laporan Baru -->
                        <a href="../../api_client/export_handler.php" target="_blank" class="text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-lg hover:bg-emerald-600 hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="fas fa-file-excel"></i> Ekspor Laporan
                        </a>
                        <a href="panel.php" class="text-xs font-medium text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition-colors px-2">
                            <i class="fas fa-sync-alt"></i> Segarkan
                        </a>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-200">
                                <th class="px-6 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Detail Referensi</th>
                                <th class="px-6 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Identitas Penyewa</th>
                                <th class="px-6 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Spesifikasi Layanan</th>
                                <th class="px-6 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Tagihan & Berkas</th>
                                <th class="px-6 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-center">Status Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            <?php if($result_bookings && $result_bookings->num_rows > 0): ?>
                                <?php while($row = $result_bookings->fetch_assoc()): ?>
                                    <tr class="bg-white hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-medium text-slate-800">#RSV-<?= $row['id_booking'] ?></div>
                                            <div class="text-[11px] text-slate-500 mt-1"><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></div>
                                        </td>
                                        
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-medium text-slate-800"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                            <div class="text-[11px] text-slate-500 mt-1">ID Pengguna: <?= $row['id_user'] ?></div>
                                        </td>
                                        
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-medium text-slate-800"><?= htmlspecialchars($row['nama_mobil']) ?></div>
                                            <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5">
                                                <i class="far fa-calendar text-slate-400"></i>
                                                <?= date('d M', strtotime($row['tgl_mulai'])) ?> &mdash; <?= date('d M', strtotime($row['tgl_selesai'])) ?>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-medium text-slate-800">Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?></div>
                                            <div class="mt-1.5">
                                                <?php if(!empty($row['bukti_transfer'])): ?>
                                                    <a href="../../<?= htmlspecialchars($row['bukti_transfer']) ?>" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 hover:text-blue-700 bg-blue-50 px-2 py-1 rounded border border-blue-100 transition-colors">
                                                        <i class="fas fa-external-link-alt text-[9px]"></i> Lihat Bukti
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-[11px] text-slate-400 italic">Belum diunggah</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 align-top text-center">
                                            <?php if(strtolower($row['status_pembayaran']) === 'lunas'): ?>
                                                <div class="flex flex-col items-center gap-2">
                                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                                    </div>
                                                    <?php if(strtolower($row['status_mobil']) === 'disewa'): ?>
                                                        <form action="../../api_client/validasi_handler.php" method="POST">
                                                            <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>">
                                                            <input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>">
                                                            <input type="hidden" name="aksi" value="selesai">
                                                            <button type="submit" onclick="return confirm('Tandai mobil telah dikembalikan ke garasi?')" class="text-[10px] font-medium text-blue-600 hover:text-blue-800 underline">
                                                                Selesaikan Sewa
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-widest">Sewa Berakhir</span>
                                                    <?php endif; ?>
                                                </div>

                                            <?php elseif(strtolower($row['status_pembayaran']) === 'ditolak'): ?>
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                                </div>

                                            <?php elseif(!empty($row['bukti_transfer'])): ?>
                                                <div class="flex flex-col items-center gap-2">
                                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Perlu Validasi
                                                    </div>
                                                    <div class="flex gap-1.5 mt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <form action="../../api_client/validasi_handler.php" method="POST" class="inline">
                                                            <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>">
                                                            <input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>">
                                                            <input type="hidden" name="aksi" value="setuju">
                                                            <button type="submit" class="px-2 py-1 bg-white border border-slate-200 text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 text-[10px] font-medium rounded transition-colors">
                                                                Setuju
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="../../api_client/validasi_handler.php" method="POST" class="inline">
                                                            <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>">
                                                            <input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>">
                                                            <input type="hidden" name="aksi" value="tolak">
                                                            <button type="submit" onclick="return confirm('Tolak pembayaran ini? Mobil akan kembali Tersedia.')" class="px-2 py-1 bg-white border border-slate-200 text-rose-600 hover:bg-rose-50 hover:border-rose-200 text-[10px] font-medium rounded transition-colors">
                                                                Tolak
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            
                                            <?php else: ?>
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Menunggu
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-slate-400 mb-2"><i class="fas fa-inbox text-2xl"></i></div>
                                        <div class="text-sm font-medium text-slate-500">Tidak ada data transaksi</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- SCRIPT UNTUK RENDER GRAFIK BI (CHART.JS) -->
    <script>
        // Data dari PHP
        const trendLabels = <?= json_encode($trend_labels) ?>;
        const trendData = <?= json_encode($trend_data) ?>;
        const carLabels = <?= json_encode($car_labels) ?>;
        const carData = <?= json_encode($car_data) ?>;

        // Render Grafik Tren Pendapatan (Line Chart)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: trendLabels.length ? trendLabels : ['Belum Ada Data'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: trendData.length ? trendData : [0],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Render Grafik Armada Terlaris (Doughnut Chart)
        const ctxTopCars = document.getElementById('topCarsChart').getContext('2d');
        new Chart(ctxTopCars, {
            type: 'doughnut',
            data: {
                labels: carLabels.length ? carLabels : ['Belum Ada Data'],
                datasets: [{
                    data: carData.length ? carData : [1],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#6366f1', '#ec4899'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                }
            }
        });
    </script>
</body>
</html>