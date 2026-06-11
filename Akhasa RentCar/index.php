<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';

$host = "localhost";
$user = "root";
$pass = "";
$db   = "akhasarentcar";

$conn = new mysqli($host, $user, $pass, $db);

$allCars = [];
if (!$conn->connect_error) {
    $result = $conn->query("SELECT * FROM cars");
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $allCars[] = [
                'idMobil' => $row['id_mobil'] ?? $row['id'] ?? 0,
                'namaMobil' => $row['nama_mobil'] ?? '',
                'kategori' => $row['kategori'] ?? '',
                'hargaPerHari' => $row['harga_per_hari'] ?? 0,
                'status' => $row['status'] ?? 'Tersedia'
            ];
        }
    }
}

$cars5Seater = array_filter($allCars, function($car) { return isset($car['kategori']) && (strpos($car['kategori'], '5 Seater') !== false); });
$cars7Seater = array_filter($allCars, function($car) { return isset($car['kategori']) && (strpos($car['kategori'], '7 Seater') !== false); });

function formatRupiah($angka){ return "Rp. " . number_format($angka, 0, ',', '.'); }

function getCarImageFilename($carName) {
    return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $carName)) . '.png';
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-control" content="public, max-age=31536000">
    <title>Akhasarentcar - Premium Car Rental Bekasi</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"></noscript>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        
        .text-gradient-animate {
            background: linear-gradient(270deg, #ffffff, #93c5fd, #ffffff, #60a5fa);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientFlow 5s ease infinite;
        }
        @keyframes gradientFlow { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .floating-badge { animation: floaty 4s ease-in-out infinite; }
        @keyframes floaty { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        .nav-entrance { animation: slideDownFade 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideDownFade { 0% { transform: translateY(-100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .whatsapp-float { animation: bounceInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards, pulse-wa 2s infinite; }
        @keyframes pulse-wa { 0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); } 70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); } 100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); } }
        @keyframes bounceInUp { 0% { transform: translateY(100px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        
        .modal-enter { animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .modal-box-enter { animation: modalBoxPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes modalBoxPop { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body class="font-sans antialiased bg-[#F8FAFC] text-[#0A192F]">

    <main>
        <!-- NAVIGASI UTAMA -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 nav-entrance shadow-sm">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <a class="flex items-center space-x-2 group" href="index.php">
                        <img src="logo.png" alt="Akhasa Rent Car" class="h-10 object-contain drop-shadow-sm transition-transform duration-300 group-hover:scale-105" onerror="this.outerHTML='<div class=\'w-8 h-8 bg-[#0A192F] rounded-lg flex items-center justify-center group-hover:bg-[#1D4ED8] transition-colors duration-300\'><span class=\'text-white font-black text-xs\'>A</span></div><span class=\'text-lg font-black text-[#0A192F] tracking-tighter\'>AKHASA<span class=\'text-[#1D4ED8]\'>RENT</span></span>'">
                    </a>
                    
                    <div class="hidden lg:flex items-center space-x-8">
                        <a href="#layanan" class="text-[11px] font-bold text-gray-500 hover:text-[#1D4ED8] tracking-widest uppercase py-2 transition-colors">LAYANAN KAMI</a>
                        <a href="#pricelist" class="text-[11px] font-bold text-gray-500 hover:text-[#1D4ED8] tracking-widest uppercase py-2 transition-colors">PRICELIST</a>
                        <a href="#informasi" class="text-[11px] font-bold text-gray-500 hover:text-[#1D4ED8] tracking-widest uppercase py-2 transition-colors">REVIEW</a>
                    </div>
                    
                    <div class="hidden lg:flex items-center space-x-4">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="flex items-center bg-gray-50 px-4 py-2 rounded-full border border-gray-200 mr-2">
                                <i class="fas fa-user-circle text-[#1D4ED8] mr-2 text-lg"></i>
                                <span class="text-xs font-bold text-[#0A192F] capitalize"><?= htmlspecialchars($_SESSION['nama']) ?></span>
                            </div>
                            <?php if($_SESSION['role'] === 'admin'): ?>
                                <a href="views/admin/panel.php" class="text-[10px] font-bold text-[#1D4ED8] hover:text-[#0A192F] tracking-widest uppercase transition-colors">Panel Admin</a>
                            <?php else: ?>
                                <a href="views/customer/dashboard.php" class="text-[10px] font-bold text-[#1D4ED8] hover:text-[#0A192F] tracking-widest uppercase transition-colors">Dashboard Saya</a>
                            <?php endif; ?>
                            <a class="bg-red-50 text-red-500 px-5 py-2.5 rounded-full text-[10px] font-bold tracking-widest uppercase hover:bg-red-500 hover:text-white transition-all shadow-sm" href="api_client/logout_handler.php">Keluar</a>
                        <?php else: ?>
                            <a class="text-[10px] font-bold text-gray-500 hover:text-[#1D4ED8] tracking-widest uppercase transition-colors" href="views/auth/masuk.php">Masuk Sistem</a>
                            <a class="relative overflow-hidden bg-[#0A192F] text-white px-6 py-3 rounded-full text-[10px] font-bold tracking-widest uppercase hover:bg-[#1D4ED8] transition-all shadow-lg group" href="views/auth/daftar.php">
                                <span class="relative z-10">Daftar Sekarang</span>
                                <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-500 ease-in-out"></div>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <section id="hero" class="relative h-[85vh] flex items-center overflow-hidden bg-[#0A192F] mt-[80px]">
            <div class="absolute inset-0 z-0">
                <img fetchpriority="high" decoding="async" alt="Luxury Car Background" class="w-full h-full object-cover opacity-40 transform scale-105 transition-transform duration-[20s] hover:scale-100" src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=2070">
            </div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl">
                    <div data-aos="fade-down" data-aos-delay="200" class="floating-badge inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-6">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-xs font-bold text-white tracking-widest uppercase">Sistem Reservasi Aktif</span>
                    </div>
                    <p data-aos="fade-up" data-aos-delay="400" class="text-white/80 text-sm font-semibold tracking-[0.3em] uppercase mb-4">Premium Car Rental Bekasi</p>
                    <h1 data-aos="fade-up" data-aos-delay="600" class="text-5xl md:text-7xl font-black text-white leading-[1.1] tracking-tighter mb-6 uppercase">
                        <span class="text-gradient-animate">BEBAS EKSPLORASI</span> <br>TANPA BATAS.
                    </h1>
                    <p data-aos="fade-up" data-aos-delay="800" class="text-white/90 text-base md:text-lg font-medium leading-relaxed mb-10 max-w-lg">
                        Layanan rental mobil lepas kunci dan driver profesional di Bantar Gebang. Status kendaraan tersinkronisasi secara real-time.
                    </p>
                    <div data-aos="fade-up" data-aos-delay="1000" class="flex flex-wrap gap-4">
                        <a href="#pricelist" class="bg-[#1D4ED8] hover:bg-[#1e3a8a] text-white px-8 py-4 rounded-xl text-xs font-bold tracking-widest transition-all uppercase shadow-[0_0_20px_rgba(29,78,216,0.4)] hover:-translate-y-1">
                            Lihat Katalog Armada
                        </a>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#F8FAFC] to-transparent"></div>
        </section>

        <!-- BAGIAN KATALOG ARMADA -->
        <section id="pricelist" class="py-24 bg-[#F8FAFC]">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="text-center mb-16" data-aos="zoom-in">
                    <h2 class="text-[#0A192F] text-3xl md:text-4xl font-bold tracking-tight mb-4">KATALOG ARMADA KAMI</h2>
                    <p class="text-gray-500 text-sm font-medium">Pilih armada terbaik untuk perjalanan Anda. Terawat, aman, dan harga bersaing.</p>
                    <div class="w-20 h-1.5 bg-[#1D4ED8] mx-auto mt-6 rounded-full"></div>
                </div>

                <?php if(empty($allCars)): ?>
                    <div class="bg-red-50 text-red-600 p-6 rounded-xl text-center font-bold border border-red-200">Sistem Database belum merespon.</div>
                <?php else: ?>

                <!-- Kategori 5 Seater -->
                <div class="mb-20">
                    <h3 data-aos="fade-right" class="text-[#0A192F] text-xl font-bold mb-8 flex items-center">
                        <span class="w-2 h-8 bg-[#1D4ED8] mr-4 rounded-full"></span> 5 Seater / 2 Baris
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach($cars5Seater as $car): ?>
                        <?php 
                            $transmisi = (strpos(strtoupper($car['namaMobil']), 'MT') !== false) ? 'Manual' : 'Automatic'; 
                            $filename = getCarImageFilename($car['namaMobil']);
                        ?>
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group overflow-hidden" data-aos="fade-up">
                            
                            <div class="bg-white h-56 p-6 flex items-center justify-center relative border-b border-gray-100 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 opacity-50"></div>
                                <img src="assets/cars/<?= $filename ?>" 
                                     onerror="this.src='https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600&bg=fff'" 
                                     alt="<?= htmlspecialchars($car['namaMobil']) ?>" 
                                     class="relative z-10 max-h-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-110">
                                
                                <?php if(strtolower($car['status']) !== 'tersedia'): ?>
                                    <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 flex items-center justify-center">
                                        <span class="bg-red-600 text-white px-5 py-2.5 rounded-lg font-bold text-xs uppercase tracking-widest shadow-lg">Sedang Disewa</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <h4 class="text-xl font-bold text-[#0A192F] mb-3"><?= htmlspecialchars($car['namaMobil']) ?></h4>
                                
                                <div class="flex items-center text-xs text-gray-500 mb-5 font-medium">
                                    <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center mr-2"><i class="far fa-clock text-[#1D4ED8]"></i></div>
                                    <span>Harian / Mingguan / Bulanan</span>
                                </div>
                                
                                <p class="text-xs text-gray-500 font-medium mb-6">Mulai dari <br><span class="text-[#0A192F] font-black text-xl"><?= formatRupiah($car['hargaPerHari']) ?></span></p>

                                <div class="mt-auto pt-5 border-t border-gray-100 flex items-center justify-between gap-3">
                                    <!-- UPDATE: Mengirimkan variable $filename ke JS -->
                                    <button onclick="openDetailModal('<?= addslashes(htmlspecialchars($car['namaMobil'])) ?>', '<?= $transmisi ?>', '<?= $filename ?>')" 
                                            class="text-[10px] font-bold text-gray-500 hover:text-[#1D4ED8] transition-colors uppercase tracking-widest flex items-center gap-1.5 flex-1 justify-center py-3 bg-gray-50 hover:bg-blue-50 rounded-lg">
                                        <i class="fas fa-info-circle text-sm"></i> Detail
                                    </button>
                                    
                                    <?php if(strtolower($car['status']) === 'tersedia'): ?>
                                        <button onclick="openBookingModal('<?= $car['idMobil'] ?>', '<?= addslashes(htmlspecialchars($car['namaMobil'])) ?>', <?= $car['hargaPerHari'] ?>)" 
                                                class="flex-1 py-3 bg-[#0A192F] hover:bg-[#1D4ED8] text-white text-[10px] font-bold rounded-lg transition-colors uppercase tracking-widest shadow-md">
                                            Reservasi
                                        </button>
                                    <?php else: ?>
                                        <button disabled class="flex-1 py-3 bg-gray-200 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed uppercase tracking-widest">
                                            Disewa
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Kategori 7 Seater -->
                <div class="mb-16">
                    <h3 data-aos="fade-right" class="text-[#0A192F] text-xl font-bold mb-8 flex items-center">
                        <span class="w-2 h-8 bg-[#1D4ED8] mr-4 rounded-full"></span> 7 Seater / 3 Baris
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach($cars7Seater as $car): ?>
                        <?php 
                            $transmisi = (strpos(strtoupper($car['namaMobil']), 'MT') !== false) ? 'Manual' : 'Automatic'; 
                            $filename = getCarImageFilename($car['namaMobil']);
                        ?>
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group overflow-hidden" data-aos="fade-up">
                            
                            <div class="bg-white h-56 p-6 flex items-center justify-center relative border-b border-gray-100 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 opacity-50"></div>
                                <img src="assets/cars/<?= $filename ?>" 
                                     onerror="this.src='https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600&bg=fff'" 
                                     alt="<?= htmlspecialchars($car['namaMobil']) ?>" 
                                     class="relative z-10 max-h-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-110">
                                
                                <?php if(strtolower($car['status']) !== 'tersedia'): ?>
                                    <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 flex items-center justify-center">
                                        <span class="bg-red-600 text-white px-5 py-2.5 rounded-lg font-bold text-xs uppercase tracking-widest shadow-lg">Sedang Disewa</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <h4 class="text-xl font-bold text-[#0A192F] mb-3"><?= htmlspecialchars($car['namaMobil']) ?></h4>
                                
                                <div class="flex items-center text-xs text-gray-500 mb-5 font-medium">
                                    <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center mr-2"><i class="far fa-clock text-[#1D4ED8]"></i></div>
                                    <span>Harian / Mingguan / Bulanan</span>
                                </div>
                                
                                <p class="text-xs text-gray-500 font-medium mb-6">Mulai dari <br><span class="text-[#0A192F] font-black text-xl"><?= formatRupiah($car['hargaPerHari']) ?></span></p>

                                <div class="mt-auto pt-5 border-t border-gray-100 flex items-center justify-between gap-3">
                                    <!-- UPDATE: Mengirimkan variable $filename ke JS -->
                                    <button onclick="openDetailModal('<?= addslashes(htmlspecialchars($car['namaMobil'])) ?>', '<?= $transmisi ?>', '<?= $filename ?>')" 
                                            class="text-[10px] font-bold text-gray-500 hover:text-[#1D4ED8] transition-colors uppercase tracking-widest flex items-center gap-1.5 flex-1 justify-center py-3 bg-gray-50 hover:bg-blue-50 rounded-lg">
                                        <i class="fas fa-info-circle text-sm"></i> Detail
                                    </button>
                                    
                                    <?php if(strtolower($car['status']) === 'tersedia'): ?>
                                        <button onclick="openBookingModal('<?= $car['idMobil'] ?>', '<?= addslashes(htmlspecialchars($car['namaMobil'])) ?>', <?= $car['hargaPerHari'] ?>)" 
                                                class="flex-1 py-3 bg-[#0A192F] hover:bg-[#1D4ED8] text-white text-[10px] font-bold rounded-lg transition-colors uppercase tracking-widest shadow-md">
                                            Reservasi
                                        </button>
                                    <?php else: ?>
                                        <button disabled class="flex-1 py-3 bg-gray-200 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed uppercase tracking-widest">
                                            Disewa
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <section id="layanan" class="py-24 bg-white overflow-hidden">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="relative" data-aos="fade-right">
                        <div class="rounded-3xl overflow-hidden shadow-2xl relative z-10 group border-8 border-gray-50">
                            <img loading="lazy" decoding="async" alt="Akhasa Team" class="w-full h-[450px] object-cover transition-transform duration-700 group-hover:scale-110" src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=2070">
                            <div class="absolute inset-0 bg-[#0A192F]/10 group-hover:bg-transparent transition-colors duration-500"></div>
                        </div>
                        <div class="absolute -bottom-8 -right-8 w-64 h-64 bg-[#1D4ED8]/10 rounded-full blur-3xl z-0 animate-pulse"></div>
                    </div>
                    <div data-aos="fade-left">
                        <p class="text-[#1D4ED8] text-xs font-bold tracking-[0.3em] uppercase mb-4">Profil Layanan</p>
                        <h2 class="text-[#0A192F] text-3xl md:text-4xl font-bold tracking-tight mb-8">Solusi Mobilitas Terpercaya di Bekasi.</h2>
                        <div class="space-y-6 text-gray-600 font-medium leading-relaxed">
                            <p>Akhasa Rent Car melayani sewa mobil <span class="font-bold text-[#0A192F]">Lepas Kunci</span> maupun dengan <span class="font-bold text-[#0A192F]">Driver Profesional</span>.</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                                <div class="p-6 bg-[#F8FAFC] rounded-2xl shadow-sm border border-gray-100 hover:border-blue-200 transition-all duration-300 group cursor-default">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110 shadow-sm">
                                        <i class="fas fa-clock text-[#1D4ED8] text-lg"></i>
                                    </div>
                                    <p class="text-[#0A192F] font-bold text-sm mb-2 uppercase tracking-widest">Durasi Fleksibel</p>
                                    <p class="text-xs text-gray-500 leading-relaxed font-medium">Sewa harian, mingguan, hingga bulanan. Sesuaikan waktu pemakaian dengan kebutuhan.</p>
                                </div>
                                
                                <div class="p-6 bg-[#F8FAFC] rounded-2xl shadow-sm border border-gray-100 hover:border-blue-200 transition-all duration-300 group cursor-default">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110 shadow-sm">
                                        <i class="fas fa-map-marked-alt text-[#1D4ED8] text-lg"></i>
                                    </div>
                                    <p class="text-[#0A192F] font-bold text-sm mb-2 uppercase tracking-widest">Antar Jemput</p>
                                    <p class="text-xs text-gray-500 leading-relaxed font-medium">Layanan praktis drop/pickup armada langsung ke lokasi Anda (rumah, kantor, stasiun).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="informasi" class="py-24 bg-[#F8FAFC] overflow-hidden">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-start">
                    <div data-aos="fade-right">
                        <div class="mb-10">
                            <p class="text-[#1D4ED8] text-xs font-bold tracking-[0.3em] uppercase mb-2">Suara Pelanggan</p>
                            <h2 class="text-[#0A192F] text-3xl font-bold tracking-tight mb-4">REVIEW JUJUR PELANGGAN</h2>
                            <div class="flex items-center space-x-1 mb-2">
                                <i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i><i class="fas fa-star text-yellow-400"></i>
                                <span class="ml-2 text-sm font-bold text-[#0A192F]">5.0 / 5.0 Rating</span>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="font-bold text-[#0A192F]">Hafiz Sutomo</p>
                                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                                </div>
                                <p class="text-[10px] text-gray-400 mb-3 font-semibold tracking-widest">11 BULAN LALU</p>
                                <p class="text-sm text-gray-600 italic">"Mobil bersih, wangi, motor customer dicuciin. Tnks ya mas"</p>
                            </div>
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="font-bold text-[#0A192F]">Najwa farhana Kamila</p>
                                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                                </div>
                                <p class="text-[10px] text-gray-400 mb-3 font-semibold tracking-widest">11 BULAN LALU</p>
                                <p class="text-sm text-gray-600 italic">"Hospitalitynya okei banget, ramah semua, harga sangat amat terjangkau, bakal jadi langganan si ini"</p>
                            </div>
                            
                            <a href="https://maps.app.goo.gl/Q48VMDhfzpSFRjZbA" target="_blank" class="inline-block mt-4 text-[11px] font-bold text-[#1D4ED8] uppercase tracking-widest hover:underline hover:text-[#0A192F] transition-colors">
                                LIHAT SEMUA ULASAN DI GOOGLE MAPS &rarr;
                            </a>
                        </div>
                    </div>
                    <div data-aos="fade-left">
                        <div class="mb-12"><p class="text-[#1D4ED8] text-xs font-bold tracking-[0.3em] uppercase mb-2">Lokasi Kami</p><h2 class="text-[#0A192F] text-3xl font-bold tracking-tight mb-6">BANTAR GEBANG, BEKASI</h2></div>
                        <div class="rounded-3xl overflow-hidden shadow-lg h-[450px] border border-gray-200"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.719665391295!2d106.97587657503802!3d-6.308596693681144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699316b395332d%3A0x713ddfd5abee94c7!2sAKHASARENTCAR%20-%20RENTAL%20MOBIL%20BEKASI!5e0!3m2!1sen!2sid!4v1715316869000!5m2!1sen!2sid" loading="lazy" width="100%" height="100%" allowfullscreen style="border:0;"></iframe></div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-[#0A192F] text-white py-20 border-t-4 border-[#1D4ED8]">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid md:grid-cols-4 gap-12">
                    <div class="col-span-1 md:col-span-1">
                        <img loading="lazy" decoding="async" src="logo.png" alt="Akhasa Rent Car" class="h-16 bg-white px-3 py-2 rounded-xl mb-6 object-contain shadow-lg" onerror="this.outerHTML='<h3 class=\'text-2xl font-black tracking-tighter mb-6\'>AKHASA<span class=\'text-[#1D4ED8]\'>RENTCAR</span></h3>''">
                        <p class="text-sm text-gray-400 font-medium leading-relaxed mb-6">Sewa mobil lepas kunci dan dengan driver profesional di Bekasi dan sekitarnya. Terpercaya sejak tahun 2020.</p>
                        <div class="flex space-x-4">
                            <a href="https://www.instagram.com/akhasarentcar_bekasi/" target="_blank" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center hover:bg-[#1D4ED8] hover:border-[#1D4ED8] transition-all transform hover:-translate-y-1"><i class="fab fa-instagram text-lg"></i></a>
                            <a href="https://www.tiktok.com/@akhasarentcar_bekasi" target="_blank" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center hover:bg-[#1D4ED8] hover:border-[#1D4ED8] transition-all transform hover:-translate-y-1"><i class="fab fa-tiktok text-lg"></i></a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold tracking-widest uppercase mb-8 text-white/50">Layanan Kami</h4>
                        <ul class="space-y-4 text-sm font-medium text-gray-400">
                            <li><a href="#layanan" class="hover:text-white transition-colors">Sewa Lepas Kunci</a></li>
                            <li><a href="#layanan" class="hover:text-white transition-colors">Sewa Dengan Driver</a></li>
                            <li><a href="#layanan" class="hover:text-white transition-colors">Sewa Mingguan / Bulanan</a></li>
                            <li><a href="#layanan" class="hover:text-white transition-colors">Antar-Jemput Armada</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold tracking-widest uppercase mb-8 text-white/50">Kontak Kami</h4>
                        <ul class="space-y-6 text-sm font-medium text-gray-400">
                            <li class="flex items-start"><i class="fas fa-map-marker-alt mr-3 text-[#1D4ED8] mt-1 shrink-0"></i><span>Komplek Bantar Gebang, Bekasi, Jawa Barat.</span></li>
                            <li class="flex items-center"><i class="fas fa-phone mr-3 text-[#1D4ED8] shrink-0"></i><span>+62 882-1154-2209</span></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold tracking-widest uppercase mb-8 text-white/50">Dapatkan Penawaran</h4>
                        <a href="https://wa.me/6288211542209" target="_blank" class="inline-block bg-[#1D4ED8] text-white px-6 py-4 rounded-xl text-xs font-bold tracking-widest w-full text-center hover:bg-white hover:text-[#0A192F] transition-all shadow-lg hover:shadow-white/20">CHAT SEKARANG</a>
                    </div>
                </div>
                <div class="border-t border-white/10 mt-20 pt-8 flex flex-col md:flex-row justify-between items-center text-[10px] uppercase tracking-widest text-gray-500 font-bold">
                    <p>&copy; 2026 AKHASA RENT CAR. ALL RIGHTS RESERVED.</p>
                </div>
            </div>
        </footer>

        <a href="https://wa.me/6288211542209" target="_blank" class="whatsapp-float flex items-center justify-center fixed bottom-8 right-8 w-16 h-16 bg-[#25D366] text-white rounded-full shadow-[0_4px_10px_rgba(0,0,0,0.3)] hover:bg-[#128C7E] transition-all z-40 text-3xl"><i class="fab fa-whatsapp"></i></a>
    </main>

    <!-- MODAL DETAIL MOBIL SPESIFIKASI -->
    <div id="detailModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#0A192F]/60 backdrop-blur-sm modal-enter" onclick="closeDetailModal()"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden modal-box-enter border border-white/20">
            <div class="bg-[#0A192F] px-8 py-5 flex justify-between items-center">
                <h2 class="text-white font-bold tracking-wide flex items-center gap-2"><i class="fas fa-car-side"></i> Detail Spesifikasi Armada</h2>
                <button onclick="closeDetailModal()" class="text-white/50 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <div class="p-8 bg-[#F8FAFC]">
                <div class="w-full h-64 bg-gray-100 flex items-center justify-center rounded-2xl overflow-hidden mb-6 relative shadow-inner">
                    <!-- UPDATE: Id dipertahankan, opacity dihapus agar gambar aslinya terang, object-contain agar proporsional -->
                    <img id="detailImage" src="" alt="Detail Mobil" class="w-full h-full object-contain p-4 drop-shadow-xl">
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg text-[10px] font-bold text-[#0A192F] uppercase tracking-widest shadow-sm">
                        VISUAL ARMADA
                    </div>
                </div>
                
                <h3 class="text-2xl font-black text-[#0A192F] mb-4" id="detailCarName">Nama Mobil</h3>
                
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <i class="fas fa-cog text-[#1D4ED8] mb-2 text-lg"></i>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Transmisi</p>
                        <p class="text-sm font-bold text-[#0A192F]" id="detailTransmisi">Manual</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <i class="fas fa-gas-pump text-[#1D4ED8] mb-2 text-lg"></i>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Bahan Bakar</p>
                        <p class="text-sm font-bold text-[#0A192F]">Bensin</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <i class="fas fa-users text-[#1D4ED8] mb-2 text-lg"></i>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kapasitas</p>
                        <p class="text-sm font-bold text-[#0A192F]">Standar</p>
                    </div>
                </div>
                
                <button onclick="closeDetailModal()" class="w-full py-3.5 bg-[#0A192F] text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-[#1D4ED8] transition-colors">
                    Tutup Tampilan
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL RESERVASI -->
    <div id="bookingModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#0A192F]/60 backdrop-blur-sm modal-enter" onclick="closeBookingModal()"></div>
        <div class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden modal-box-enter border border-white/20">
            <div class="bg-[#0A192F] px-8 py-5 flex justify-between items-center">
                <h2 class="text-white font-bold tracking-wide">Formulir Reservasi</h2>
                <button onclick="closeBookingModal()" class="text-white/50 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>

            <div class="p-8 bg-[#F8FAFC]">
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-5 mb-6 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Armada Pilihan</p>
                        <h3 class="text-lg font-bold text-[#0A192F]" id="modalCarName">Nama Mobil</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tarif Dasar</p>
                        <p class="text-lg font-black text-[#1D4ED8]" id="modalCarPrice">Rp 0</p>
                        <p class="text-[10px] font-medium text-gray-500">/ Hari</p>
                    </div>
                </div>

                <form id="formReservasiAjax" class="space-y-6">
                    <input type="hidden" name="id_mobil" id="modalInputId">
                    <input type="hidden" name="id_user" value="<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '' ?>">
                    <input type="hidden" name="total_biaya" id="modalInputTotal" value="0">
                    <input type="hidden" id="modalBasePrice" value="0">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Tgl Pengambilan</label>
                            <input type="date" name="tgl_mulai" id="modalTglMulai" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-[#1D4ED8] outline-none text-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Tgl Pengembalian</label>
                            <input type="date" name="tgl_selesai" id="modalTglSelesai" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-[#1D4ED8] outline-none text-sm font-medium">
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-bold text-[#0A192F]">Total Biaya</p>
                            <p class="text-[10px] font-medium text-gray-500 mt-0.5" id="modalDurasiLabel">Pilih tanggal sewa.</p>
                        </div>
                        <h3 class="text-2xl font-black text-[#1D4ED8]" id="modalDisplayTotal">Rp 0</h3>
                    </div>

                    <div class="flex items-start gap-2 pt-2">
                        <input type="checkbox" id="tnc" name="tnc" required class="mt-0.5 w-4 h-4 text-[#1D4ED8] bg-white border-gray-300 rounded cursor-pointer accent-[#1D4ED8]">
                        <label for="tnc" class="text-[10px] text-gray-500 font-medium leading-relaxed cursor-pointer">
                            Saya telah membaca dan setuju dengan <span class="font-bold text-[#0A192F]">Syarat & Ketentuan</span> sewa lepas kunci, termasuk kebijakan denda keterlambatan dan risiko asuransi armada.
                        </label>
                    </div>

                    <button type="submit" id="btnSubmitReservasi" class="w-full py-4 bg-[#0A192F] text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-[#1D4ED8] transition-colors shadow-lg shadow-[#0A192F]/20 flex justify-center items-center gap-2">
                        <span>Proses Reservasi Sekarang</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script> 
    
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            AOS.init({ duration: 800, offset: 100, once: true });

            const isLoggedIn = <?= $isLoggedIn ?>;
            const modal = document.getElementById('bookingModal');
            const mName = document.getElementById('modalCarName');
            const mPrice = document.getElementById('modalCarPrice');
            const mInputId = document.getElementById('modalInputId');
            const mBasePrice = document.getElementById('modalBasePrice');
            
            const tMulai = document.getElementById('modalTglMulai');
            const tSelesai = document.getElementById('modalTglSelesai');
            const dispTotal = document.getElementById('modalDisplayTotal');
            const inpTotal = document.getElementById('modalInputTotal');
            const lblDurasi = document.getElementById('modalDurasiLabel');

            const modalDetail = document.getElementById('detailModal');
            const detailCarName = document.getElementById('detailCarName');
            const detailTransmisi = document.getElementById('detailTransmisi');

            // UPDATE: Menambahkan parameter filename untuk menampilkan gambar sesuai armada
            window.openDetailModal = function(nama, transmisi, filename) {
                detailCarName.textContent = nama;
                detailTransmisi.textContent = transmisi;
                
                const detailImg = document.getElementById('detailImage');
                detailImg.src = 'assets/cars/' + filename;
                
                // Fallback jika file gambar belum dimasukkan ke folder assets/cars/
                detailImg.onerror = function() {
                    this.src = 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=800&bg=fff';
                };

                modalDetail.classList.remove('hidden');
            };

            window.closeDetailModal = function() {
                modalDetail.classList.add('hidden');
            };

            window.openBookingModal = function(id, nama, harga) {
                if (!isLoggedIn) {
                    Swal.fire({
                        title: 'Akses Terbatas',
                        text: 'Silakan masuk sistem atau daftar akun terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'Masuk Sistem',
                        confirmButtonColor: '#1D4ED8'
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = 'views/auth/masuk.php';
                    });
                    return;
                }

                mName.textContent = nama;
                mPrice.textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
                mInputId.value = id;
                mBasePrice.value = harga;
                
                tMulai.value = ''; tSelesai.value = '';
                dispTotal.textContent = 'Rp 0'; inpTotal.value = '0'; lblDurasi.textContent = 'Pilih tanggal sewa.';
                document.getElementById('tnc').checked = false;
                
                modal.classList.remove('hidden');
            };

            window.closeBookingModal = function() { modal.classList.add('hidden'); };

            function hitungTotal() {
                if (tMulai.value && tSelesai.value) {
                    const d1 = new Date(tMulai.value);
                    const d2 = new Date(tSelesai.value);
                    
                    if (d2 < d1) {
                        tSelesai.value = tMulai.value;
                        hitungTotal();
                        return;
                    }

                    const diff = Math.abs(d2 - d1);
                    let days = Math.ceil(diff / (1000 * 60 * 60 * 24));
                    if (days === 0) days = 1;

                    const base = parseInt(mBasePrice.value);
                    const total = days * base;
                    
                    dispTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    inpTotal.value = total;
                    lblDurasi.textContent = `Durasi: ${days} Hari`;
                }
            }

            tMulai.addEventListener('change', hitungTotal);
            tSelesai.addEventListener('change', hitungTotal);

            document.getElementById('formReservasiAjax').addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                const btn = document.getElementById('btnSubmitReservasi');
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses Sistem...';
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');

                const formData = new FormData(this);

                fetch('api_client/booking_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        closeBookingModal();
                        Swal.fire({
                            title: 'Reservasi Sukses!',
                            text: data.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = data.redirect; 
                        });
                    } else {
                        Swal.fire('Terjadi Kesalahan', data.message, 'error');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        btn.classList.remove('opacity-70', 'cursor-not-allowed');
                    }
                })
                .catch(error => {
                    Swal.fire('Kesalahan Server', 'Gagal menghubungi server.', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                });
            });
        });
    </script>
</body>
</html>