<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Akhasarentcar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar khusus form jika layar terlalu kecil */
        .form-scroll::-webkit-scrollbar { width: 4px; }
        .form-scroll::-webkit-scrollbar-track { background: transparent; }
        .form-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
    </style>
</head>
<body class="font-sans antialiased text-[#0A192F] bg-gray-50 flex items-center justify-center min-h-screen relative overflow-hidden py-8">

    <div class="absolute top-0 left-0 w-full h-full z-0 overflow-hidden pointer-events-none fixed">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#2563EB]/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
    </div>

    <main class="w-full max-w-[1000px] bg-white rounded-[2.5rem] shadow-2xl flex flex-col md:flex-row overflow-hidden relative z-10 m-4 border border-white/50 h-auto md:h-[750px]">
        
        <div class="hidden md:flex md:w-[45%] bg-[#0A192F] relative p-12 flex-col justify-between overflow-hidden">
            <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=2070" 
                 alt="Luxury Fleet" 
                 class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0A192F] via-[#0A192F]/70 to-[#0A192F]/20"></div>

            <div class="relative z-10">
                <a href="../../index.php" class="flex items-center gap-2 group inline-block">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg shadow-white/10 group-hover:scale-105 transition-transform">
                        <span class="text-[#0A192F] font-black text-lg">A</span>
                    </div>
                    <span class="text-xl font-black text-white tracking-tighter">AKHASA<span class="text-[#2563EB]">RENT</span></span>
                </a>
            </div>

            <div class="relative z-10">
                <h2 class="text-4xl font-extrabold text-white mb-4 leading-tight tracking-tight">Bergabung<br>Bersama Kami.</h2>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm mb-6">Nikmati kemudahan reservasi, kelola riwayat perjalanan, dan dapatkan penawaran eksklusif hanya untuk member.</p>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm text-gray-300 font-medium bg-white/5 p-3 rounded-xl border border-white/10 backdrop-blur-sm">
                        <i class="fas fa-check-circle text-emerald-400"></i> Proses Cepat & Mudah
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300 font-medium bg-white/5 p-3 rounded-xl border border-white/10 backdrop-blur-sm">
                        <i class="fas fa-check-circle text-emerald-400"></i> Harga Transparan
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-[55%] p-8 md:p-12 flex flex-col bg-white relative overflow-y-auto form-scroll">
            
            <a href="../../index.php" class="absolute top-8 right-8 w-10 h-10 bg-gray-50 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-[#0A192F] transition-colors tooltip" title="Kembali ke Beranda">
                <i class="fas fa-times"></i>
            </a>

            <div class="mb-8 mt-4 md:mt-0">
                <h2 class="text-3xl font-extrabold text-[#0A192F] tracking-tight mb-2">Buat Akun Baru</h2>
                <p class="text-sm text-gray-500 font-medium">Lengkapi data di bawah ini untuk mulai menyewa armada.</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-red-800">Pendaftaran Gagal</p>
                        <p class="text-xs text-red-600 mt-0.5">Pastikan semua data terisi dengan benar dan kata sandi cocok.</p>
                    </div>
                </div>
            <?php endif; ?>

            <button type="button" class="w-full bg-white border border-gray-200 text-gray-700 py-3.5 rounded-2xl text-[12px] font-bold tracking-wide hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm flex items-center justify-center gap-3 mb-6 group">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5 group-hover:scale-110 transition-transform">
                Lanjutkan dengan Google
            </button>

            <div class="relative flex items-center justify-center mb-6">
                <hr class="w-full border-gray-200">
                <span class="absolute bg-white px-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Atau Daftar Manual</span>
            </div>

            <form action="../../api_client/register_handler.php" method="POST" class="space-y-4">
                
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#2563EB] transition-colors">
                        <i class="far fa-user"></i>
                    </div>
                    <input type="text" name="nama" required placeholder="Nama Lengkap" 
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-[#2563EB] outline-none text-sm transition-all font-medium text-gray-800 placeholder-gray-400">
                </div>

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#2563EB] transition-colors">
                        <i class="far fa-envelope"></i>
                    </div>
                    <input type="email" name="email" required placeholder="Alamat Email" 
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-[#2563EB] outline-none text-sm transition-all font-medium text-gray-800 placeholder-gray-400">
                </div>

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#2563EB] transition-colors">
                        <i class="fab fa-whatsapp text-[16px]"></i>
                    </div>
                    <input type="tel" name="whatsapp" required placeholder="Nomor WhatsApp" 
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-[#2563EB] outline-none text-sm transition-all font-medium text-gray-800 placeholder-gray-400">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#2563EB] transition-colors">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" required placeholder="Kata Sandi (Min. 8)" 
                               class="w-full pl-11 pr-10 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-[#2563EB] outline-none text-sm transition-all font-medium text-gray-800 placeholder-gray-400" id="pwd1">
                        <button type="button" onclick="togglePwd('pwd1', 'eye1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#0A192F] focus:outline-none">
                            <i class="far fa-eye" id="eye1"></i>
                        </button>
                    </div>

                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#2563EB] transition-colors">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <input type="password" name="konfirmasi_password" required placeholder="Konfirmasi Sandi" 
                               class="w-full pl-11 pr-10 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-[#2563EB] outline-none text-sm transition-all font-medium text-gray-800 placeholder-gray-400" id="pwd2">
                        <button type="button" onclick="togglePwd('pwd2', 'eye2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#0A192F] focus:outline-none">
                            <i class="far fa-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-start gap-2 pt-2 pb-2">
                    <input type="checkbox" required class="mt-1 w-4 h-4 text-[#2563EB] bg-gray-50 border-gray-300 rounded focus:ring-[#2563EB] cursor-pointer">
                    <label class="text-xs text-gray-500 leading-tight">
                        Saya menyetujui <a href="#" class="text-[#2563EB] font-bold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-[#2563EB] font-bold hover:underline">Kebijakan Privasi</a> Akhasarentcar.
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#0A192F] text-white py-4 rounded-2xl text-[11px] font-bold tracking-widest uppercase hover:bg-[#2563EB] transition-all shadow-xl shadow-navy-100 flex items-center justify-center gap-2 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center gap-2">Daftar Sekarang <i class="fas fa-check"></i></span>
                        <div class="absolute inset-0 bg-[#2563EB] transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-500 font-medium">Sudah memiliki akun? 
                    <a href="masuk.php" class="text-[#0A192F] font-bold hover:text-[#2563EB] transition-colors ml-1 underline decoration-2 underline-offset-4">Masuk di sini</a>
                </p>
            </div>
        </div>
    </main>

    <script>
        function togglePwd(inputId, iconId) {
            const pwdInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                pwdInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>