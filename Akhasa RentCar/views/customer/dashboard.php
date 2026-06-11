<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../auth/masuk.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "akhasarentcar");
if ($conn->connect_error) { die("Koneksi Database Gagal!"); }

$user_id = $_SESSION['user_id'];
$sql = "SELECT b.*, c.nama_mobil FROM bookings b LEFT JOIN cars c ON b.id_mobil = c.id_mobil WHERE b.id_user = '$user_id' ORDER BY b.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Tambahan Library SweetAlert2 untuk Notifikasi Copy -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Animasi Transisi untuk Kartu Pembayaran */
        .payment-card { transition: all 0.2s ease-in-out; }
        .payment-card:hover { border-color: #3b82f6; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06); }
        .payment-card.active { border-color: #2563eb; background-color: #eff6ff; box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2); }
    </style>
</head>
<body class="text-slate-800 antialiased flex flex-col min-h-screen">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex justify-between items-center">
            <a href="../../index.php" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-[#0A192F] rounded flex items-center justify-center">
                    <span class="text-white font-black text-xs">A</span>
                </div>
                <span class="text-lg font-black text-[#0A192F] tracking-tighter">AKHASA<span class="text-blue-600">RENT</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="../../index.php#pricelist" class="hidden sm:flex text-[11px] font-bold text-blue-600 bg-blue-50 px-4 py-2 rounded-full hover:bg-blue-600 hover:text-white transition-colors"><i class="fas fa-plus mr-1.5"></i> Reservasi Baru</a>
                <div class="text-right">
                    <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($_SESSION['nama'] ?? 'Pelanggan') ?></p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Customer Area</p>
                </div>
                <a href="../../api_client/logout_handler.php" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-500 rounded-full hover:bg-rose-500 hover:text-white transition-colors"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 py-10 w-full">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-[#0A192F] tracking-tight mb-2">Riwayat Reservasi Saya</h1>
            <p class="text-sm text-slate-500 font-medium">Pantau status pesanan dan selesaikan pembayaran Anda di sini.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-white">
                <h3 class="font-bold text-[#0A192F] text-sm">Daftar Transaksi</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200 bg-slate-50/50">
                            <th class="px-6 py-4">ID Booking</th>
                            <th class="px-6 py-4">Armada & Jadwal</th>
                            <th class="px-6 py-4">Total Biaya</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        <?php if($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4"><span class="font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded text-xs border border-slate-200">#RSV-<?= $row['id_booking'] ?></span></td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-[#0A192F] text-sm mb-1"><?= htmlspecialchars($row['nama_mobil']) ?></p>
                                        <div class="text-[11px] text-slate-500 font-medium"><i class="far fa-calendar mr-1"></i> <?= date('d M Y', strtotime($row['tgl_mulai'])) ?> &mdash; <?= date('d M Y', strtotime($row['tgl_selesai'])) ?></div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-blue-600">Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?></td>
                                    
                                    <td class="px-6 py-4">
                                        <?php if($row['status_pembayaran'] === 'Lunas'): ?>
                                            <span class="px-3 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">Lunas</span>
                                        <?php elseif($row['status_pembayaran'] === 'Ditolak'): ?>
                                            <span class="px-3 py-1 rounded text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">Ditolak</span>
                                        <?php elseif($row['status_pembayaran'] === 'Menunggu' && !empty($row['bukti_transfer'])): ?>
                                            <span class="px-3 py-1 rounded text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">Menunggu</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 rounded text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">Belum Bayar</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <?php if($row['status_pembayaran'] === 'Lunas'): ?>
                                            <a href="../../api_client/cetak_invoice.php?id=<?= $row['id_booking'] ?>" target="_blank" class="px-4 py-2 bg-emerald-600 text-white text-[10px] font-bold uppercase rounded hover:bg-emerald-700 transition-colors shadow-sm"><i class="fas fa-print mr-1"></i> UNDUH INVOICE</a>
                                        <?php elseif($row['status_pembayaran'] === 'Ditolak'): ?>
                                            <span class="text-xs font-bold text-slate-400">Dibatalkan</span>
                                        <?php elseif($row['status_pembayaran'] === 'Menunggu' && !empty($row['bukti_transfer'])): ?>
                                            <span class="px-4 py-2 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded border border-slate-200"><i class="fas fa-spinner fa-spin mr-1"></i> Diproses</span>
                                        <?php else: ?>
                                            <button onclick="openUploadModal('<?= $row['id_booking'] ?>')" class="px-4 py-2 bg-[#0A192F] text-white text-[10px] font-bold uppercase rounded hover:bg-blue-600 transition-colors shadow-md">BAYAR SEKARANG</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-slate-500">Belum ada riwayat reservasi.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- MODAL PEMBAYARAN DIGITAL (INTERAKTIF & PROFESIONAL) -->
    <div id="uploadModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeUploadModal()"></div>
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl relative z-10 border border-white/20 flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-[#0A192F] text-sm"><i class="fas fa-wallet text-blue-600 mr-2"></i> Pilihan Pembayaran</h3>
                <button onclick="closeUploadModal()" class="text-slate-400 hover:text-rose-500"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="p-6 overflow-y-auto custom-scroll">
                
                <!-- UPDATE UX: Kartu Pembayaran Interaktif -->
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Transfer Bank</p>
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <!-- Kartu BCA -->
                    <div onclick="selectPayment(this)" class="payment-card cursor-pointer border border-slate-200 bg-white p-3 rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-6 bg-blue-700 text-white text-[8px] font-black flex items-center justify-center rounded">BCA</div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">1234567890</p>
                                <p class="text-[9px] text-slate-500">a.n Akhasa Rentcar</p>
                            </div>
                        </div>
                        <button type="button" onclick="copyToClipboard('1234567890', event)" class="text-slate-300 hover:text-blue-600 transition-colors" title="Salin Rekening">
                            <i class="far fa-copy text-sm"></i>
                        </button>
                    </div>
                    <!-- Kartu Mandiri -->
                    <div onclick="selectPayment(this)" class="payment-card cursor-pointer border border-slate-200 bg-white p-3 rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-6 bg-yellow-400 text-blue-900 text-[8px] font-black flex items-center justify-center rounded">MANDIRI</div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">0987654321</p>
                                <p class="text-[9px] text-slate-500">a.n Akhasa Rentcar</p>
                            </div>
                        </div>
                        <button type="button" onclick="copyToClipboard('0987654321', event)" class="text-slate-300 hover:text-blue-600 transition-colors" title="Salin Rekening">
                            <i class="far fa-copy text-sm"></i>
                        </button>
                    </div>
                </div>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">E-Wallet (Digital)</p>
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <!-- Kartu GoPay -->
                    <div onclick="selectPayment(this)" class="payment-card cursor-pointer border border-slate-200 bg-white p-3 rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-6 bg-blue-400 text-white text-[8px] font-bold flex items-center justify-center rounded">gopay</div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">088211542209</p>
                                <p class="text-[9px] text-slate-500">a.n Akhasa Rentcar</p>
                            </div>
                        </div>
                        <button type="button" onclick="copyToClipboard('088211542209', event)" class="text-slate-300 hover:text-blue-600 transition-colors" title="Salin Nomor HP">
                            <i class="far fa-copy text-sm"></i>
                        </button>
                    </div>
                    <!-- Kartu DANA -->
                    <div onclick="selectPayment(this)" class="payment-card cursor-pointer border border-slate-200 bg-white p-3 rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-6 bg-blue-500 text-white text-[8px] font-bold flex items-center justify-center rounded">DANA</div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">088211542209</p>
                                <p class="text-[9px] text-slate-500">a.n Akhasa Rentcar</p>
                            </div>
                        </div>
                        <button type="button" onclick="copyToClipboard('088211542209', event)" class="text-slate-300 hover:text-blue-600 transition-colors" title="Salin Nomor HP">
                            <i class="far fa-copy text-sm"></i>
                        </button>
                    </div>
                </div>

                <form action="../../api_client/upload_handler.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="id_booking" id="modalBookingId">
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:bg-blue-50 hover:border-blue-300 transition-colors relative cursor-pointer group">
                        <i class="fas fa-cloud-upload-alt text-2xl text-slate-300 group-hover:text-blue-500 transition-colors mb-2"></i>
                        <p class="text-sm font-bold text-[#0A192F]">Unggah Bukti Transfer</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1">Format: JPG, PNG, PDF (Maks. 2MB)</p>
                        <input type="file" name="bukti_transfer" id="file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required accept=".jpg,.png,.jpeg,.pdf">
                    </div>
                    <div id="file-name-display" class="hidden text-center bg-blue-50 py-2 rounded-lg border border-blue-100">
                        <p id="file-name-text" class="text-xs font-bold text-blue-700"></p>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-blue-600 text-white font-bold uppercase tracking-widest text-[11px] rounded-xl shadow-md hover:bg-[#0A192F] transition-colors mt-2">
                        KIRIM BUKTI PEMBAYARAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Modal Upload
        const modal = document.getElementById('uploadModal');
        
        function openUploadModal(id) { 
            document.getElementById('modalBookingId').value = id; 
            modal.classList.remove('hidden'); 
        }
        
        function closeUploadModal() { 
            modal.classList.add('hidden'); 
            // Reset pilihan saat ditutup
            document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('active'));
        }

        // Tampilkan nama file saat diunggah
        document.getElementById('file-upload').addEventListener('change', function(e) {
            const fileNameDisplay = document.getElementById('file-name-text');
            const fileWrapper = document.getElementById('file-name-display');
            if(e.target.files.length > 0) {
                fileNameDisplay.innerHTML = '<i class="fas fa-file-image mr-1"></i> ' + e.target.files[0].name;
                fileWrapper.classList.remove('hidden');
            } else {
                fileWrapper.classList.add('hidden');
            }
        });

        // FUNGSI UX PROFESIONAL: Memilih Kartu Pembayaran
        function selectPayment(element) {
            // Hapus kelas 'active' dari semua kartu
            document.querySelectorAll('.payment-card').forEach(card => {
                card.classList.remove('active');
            });
            // Tambahkan kelas 'active' pada kartu yang diklik
            element.classList.add('active');
        }

        // FUNGSI UX PROFESIONAL: Salin Nomor ke Clipboard (1-Click Copy)
        function copyToClipboard(text, event) {
            // Mencegah klik kartu (selectPayment) terpanggil saat menekan tombol copy
            event.stopPropagation();
            
            navigator.clipboard.writeText(text).then(() => {
                // Tampilkan Notifikasi Elegan dengan SweetAlert2 Toast
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tersalin: ' + text,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    background: '#0A192F',
                    color: '#fff',
                    iconColor: '#34d399'
                });
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        }
    </script>
</body>
</html>