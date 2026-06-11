<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akhasarentcar - Premium Car Rental Bekasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .group:hover .group-hover\:block { display: block; }
    </style>
</head>
<body class="bg-[#F8F9FA] text-gray-800">

    <nav class="bg-white shadow-sm fixed w-full z-50 h-[56px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center">
            <a href="index.php" class="text-xl font-extrabold text-[#0A192F] tracking-tight">AKHASARENTCAR</a>
            
            <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
                <a href="#layanan" class="text-gray-600 hover:text-[#2563EB] transition">LAYANAN KAMI</a>
                <a href="#pricelist" class="text-gray-600 hover:text-[#2563EB] transition">PRICELIST</a>
                
                <div class="relative group py-4">
                    <button class="text-gray-600 hover:text-[#2563EB] transition flex items-center gap-1">
                        INFORMASI <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div class="absolute top-10 left-0 w-56 bg-white border border-gray-100 shadow-lg rounded-md hidden group-hover:block transition-all">
                        <ul class="py-2 text-sm text-gray-600">
                            <li><a href="#" class="block px-4 py-2 hover:bg-gray-50 hover:text-[#2563EB]">Galeri Armada</a></li>
                            <li><a href="#" class="block px-4 py-2 hover:bg-gray-50 hover:text-[#2563EB]">Tentang Kami</a></li>
                            <li><a href="#informasi" class="block px-4 py-2 hover:bg-gray-50 hover:text-[#2563EB]">Lokasi & Kontak</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex space-x-4 text-sm font-semibold">
                <a href="views/auth/masuk.php" class="text-[#0A192F] hover:text-[#2563EB] py-2 transition">MASUK</a>
                <a href="views/auth/daftar.php" class="bg-[#0A192F] text-white px-5 py-2 rounded hover:bg-gray-800 transition">DAFTAR</a>
            </div>
        </div>
    </nav>