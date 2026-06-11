<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem - Akhasarentcar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased text-[#0A192F] bg-gray-50 flex items-center justify-center min-h-screen relative overflow-hidden">

    <div class="absolute top-0 left-0 w-full h-full z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#2563EB]/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
    </div>

    <main class="w-full max-w-[1000px] bg-white rounded-[2.5rem] shadow-2xl flex flex-col md:flex-row overflow-hidden relative z-10 m-6 border border-white/50">
        
        <div class="hidden md:flex md:w-1/2 bg-[#0A192F] relative p-12 flex-col justify-between overflow-hidden">
            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=2070" 
                 alt="Luxury Car" 
                 class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0A192F] via-[#0A192F]/80 to-transparent"></div>

            <div class="relative z-10">
                <a href="../../index.php" class="flex items-center gap-2 group mb-12 inline-block">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg shadow-white/10 group-hover:scale-105 transition-transform">
                        <span class="text-[#0A192F] font-black text-lg">A</span>
                    </div>
                    <span class="text-xl font-black text-white tracking-tighter">AKHASA<span class="text-[#2563EB]">RENT</span></span>
                </a>
            </div>

            <div class="relative z-10">
                <h2 class="text-4xl font-extrabold text-white mb-4 leading-tight tracking-tight">Perjalanan<br>Premium Anda<br>Dimulai di Sini.</h2>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm mb-8">Masuk untuk mengelola reservasi, melihat katalog terbaru, dan menikmati layanan mobilitas tanpa batas dari kami.</p>
                
                <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                    <div class="flex -space-x-2">
                        <img class="w-8 h-8 rounded-full border-2 border-[#0A192F]" src="https://i.pravatar.cc/100?img=1" alt="User">
                        <img class="w-8 h-8 rounded-full border-2 border-[#0A192F]" src="https://i.pravatar.cc/100?img=2" alt="User">
                        <img class="w-8 h-8 rounded-full border-2 border-[#0A192F]" src="https://i.pravatar.cc/100?img=3" alt="User">
                    </div>
                    <div>
                        <div class="flex text-yellow-400 text-[10px] mb-0.5">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-xs text-gray-300 font-medium">Dipercaya 500+ Pelanggan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-14 lg:p-16 flex flex-col justify-center bg-white relative">
            
            <a href="../../index.php" class="absolute top-8 right-8 w-10 h-10 bg-gray-50 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-[#0A192F] transition-colors tooltip" title="Kembali ke Beranda">
                <i class="fas fa-times"></i>
            </a>

            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-[#0A192F] tracking-tight mb-2">Selamat Datang</h2>
                <p class="text-sm text-gray-500 font-medium">Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-red-800">Login Gagal</p>
                        <p class="text-xs text-red-600 mt-0.5">
                            <?php 
                                if($_GET['error'] == 'empty') echo "Email dan Kata Sandi wajib diisi.";
                                else if($_GET['error'] == 'failed') echo "Email atau Kata Sandi Anda salah.";
                                else if($_GET['error'] == 'unauthorized') echo "Sesi berakhir. Silakan login kembali.";
                                else echo "Terjadi kesalahan sistem.";
                            ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="../../api_client/auth_handler.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 ml-1">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#2563EB] transition-colors">
                            <i class="far fa-envelope"></i>
                        </div>
                        <input type="email" name="email" required placeholder="nama@email.com" 
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-[#2563EB] outline-none text-sm transition-all font-medium text-gray-800 placeholder-gray-400">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Kata Sandi</label>
                        <a href="#" class="text-xs font-bold text-[#2563EB] hover:underline">Lupa Sandi?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#2563EB] transition-colors">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••" 
                               class="w-full pl-11 pr-10 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-[#2563EB] outline-none text-sm transition-all font-medium text-gray-800 placeholder-gray-400" id="passwordInput">
                        
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#0A192F] transition-colors focus:outline-none">
                            <i class="far fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#0A192F] text-white py-4 rounded-2xl text-[11px] font-bold tracking-widest uppercase hover:bg-[#2563EB] transition-all shadow-xl shadow-navy-100 flex items-center justify-center gap-2 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center gap-2">MASUK <i class="fas fa-arrow-right"></i></span>
                        <div class="absolute inset-0 bg-[#2563EB] transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-500 font-medium">Belum memiliki akun? 
                    <a href="daftar.php" class="text-[#0A192F] font-bold hover:text-[#2563EB] transition-colors ml-1 underline decoration-2 underline-offset-4">Daftar Sekarang</a>
                </p>
            </div>
        </div>
    </main>

    <script>
        function togglePassword() {
            const pwdInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            
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