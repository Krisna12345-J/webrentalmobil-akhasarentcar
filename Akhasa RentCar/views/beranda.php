<section class="relative pt-[56px] h-[600px] flex items-center justify-center bg-[#0A192F] overflow-hidden">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?auto=format&fit=crop&q=80" alt="Premium Car" class="absolute inset-0 w-full h-full object-cover z-0 opacity-80">
        <div class="relative z-20 text-center px-4 max-w-4xl mx-auto">
            <p class="text-gray-300 text-sm font-semibold tracking-[0.2em] mb-4 uppercase">Premium Car Rental</p>
            <h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight mb-6 tracking-tight">BEBAS EXPLORASI<br>TANPA BATAS.</h1>
            <p class="text-lg text-gray-200 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                Layanan rental mobil Bekasi Bantar Gebang — sewa mobil lepas kunci untuk perjalanan bisnis, wisata, event, dan kebutuhan harian di seluruh wilayah Jabodetabek.
            </p>
            <a href="#pricelist" class="px-8 py-3 bg-[#2563EB] text-white font-semibold rounded hover:bg-blue-700 transition">LIHAT ARMADA</a>
        </div>
    </section>

    <section id="layanan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2">
                <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80" alt="Layanan Akhasarentcar" class="rounded-xl shadow-lg w-full h-[350px] object-cover">
            </div>
            <div class="lg:w-1/2">
                <p class="text-[#2563EB] font-bold text-xs tracking-wider uppercase mb-2">PROFIL LAYANAN</p>
                <h2 class="text-3xl font-extrabold text-[#0A192F] mb-6 leading-snug">Solusi Mobilitas Terpercaya di Bantar Gebang.</h2>
                <p class="text-gray-600 mb-8 leading-relaxed">Akhasa Rent Car melayani sewa mobil <strong class="text-[#0A192F]">Lepas Kunci</strong> maupun dengan <strong class="text-[#0A192F]">Driver Profesional</strong>.</p>
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="p-5 bg-[#F8F9FA] rounded-lg border border-gray-100">
                        <h4 class="font-bold text-[#0A192F] mb-1">DURASI FLEKSIBEL</h4>
                        <p class="text-sm text-gray-500">Harian, Mingguan, Bulanan.</p>
                    </div>
                    <div class="p-5 bg-[#F8F9FA] rounded-lg border border-gray-100">
                        <h4 class="font-bold text-[#0A192F] mb-1">ANTAR JEMPUT</h4>
                        <p class="text-sm text-gray-500">Layanan drop unit ke lokasi Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pricelist" class="py-20 bg-[#F8F9FA]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-[#0A192F] tracking-tight mb-2">DAFTAR HARGA SEWA LEPAS KUNCI</h2>
                <div class="w-16 h-1 bg-[#2563EB] mx-auto"></div>
            </div>

            <?php if(empty($allCars)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-md text-center font-medium border border-red-200">
                    Sistem Backend (Java API) belum merespon. Data tidak dapat ditampilkan.
                </div>
            <?php else: ?>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-10">
                <div class="bg-[#0A192F] text-white px-6 py-4 font-semibold">KATEGORI: 5 SEATER / 2 BARIS</div>
                <table class="w-full text-left border-collapse">
                    <tbody class="text-sm text-gray-700">
                        <?php foreach($cars5Seater as $index => $car): ?>
                        <tr class="border-b border-gray-100 <?php echo $index % 2 == 0 ? 'bg-white' : 'bg-gray-50'; ?> hover:bg-blue-50 transition">
                            <td class="px-6 py-4 font-medium text-[#0A192F]"><?= htmlspecialchars($car['namaMobil']) ?></td>
                            <td class="px-6 py-4 text-right"><?= formatRupiah($car['hargaPerHari']) ?> / Hari</td>
                            <td class="px-6 py-4 text-right">
                                <?php $waText = urlencode("Halo, saya ingin mereservasi unit " . $car['namaMobil'] . " (" . formatRupiah($car['hargaPerHari']) . "/hari)."); ?>
                                <a href="https://wa.me/6288211542209?text=<?= $waText ?>" target="_blank" class="text-[#2563EB] hover:underline font-bold text-xs uppercase tracking-wide">Reservasi WA</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-10">
                <div class="bg-[#0A192F] text-white px-6 py-4 font-semibold">KATEGORI: 7 SEATER / 3 BARIS</div>
                <table class="w-full text-left border-collapse">
                    <tbody class="text-sm text-gray-700">
                        <?php foreach($cars7Seater as $index => $car): ?>
                        <tr class="border-b border-gray-100 <?php echo $index % 2 == 0 ? 'bg-white' : 'bg-gray-50'; ?> hover:bg-blue-50 transition">
                            <td class="px-6 py-4 font-medium text-[#0A192F]"><?= htmlspecialchars($car['namaMobil']) ?></td>
                            <td class="px-6 py-4 text-right"><?= formatRupiah($car['hargaPerHari']) ?> / Hari</td>
                            <td class="px-6 py-4 text-right">
                                <?php $waText = urlencode("Halo, saya ingin mereservasi unit " . $car['namaMobil'] . " (" . formatRupiah($car['hargaPerHari']) . "/hari)."); ?>
                                <a href="https://wa.me/6288211542209?text=<?= $waText ?>" target="_blank" class="text-[#2563EB] hover:underline font-bold text-xs uppercase tracking-wide">Reservasi WA</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                <h4 class="font-bold text-[#0A192F] mb-4">Syarat & Ketentuan Lepas Kunci:</h4>
                <ul class="list-disc pl-5 space-y-2 text-sm text-gray-600">
                    <li><strong class="text-gray-800">Weekday (Senin - Kamis):</strong> Hitungan 24 jam atau 12 jam sesuai jam pengambilan.</li>
                    <li><strong class="text-gray-800">Weekend (Jumat - Minggu):</strong> Pengambilan di jam 23:00 dan pengembalian unit di jam 23:59 sebelum pergantian tanggal.</li>
                    <li><strong class="text-gray-800">Jaminan:</strong> KTP, KK, Sepeda Motor + STNK / Deposit.</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="informasi" class="py-20 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <div>
                    <h4 class="text-[#2563EB] font-bold tracking-wider text-xs uppercase mb-2">Suara Pelanggan</h4>
                    <h2 class="text-3xl font-extrabold text-[#0A192F] mb-4 tracking-tight">REVIEW JUJUR PELANGGAN<br>AKHASA RENT CAR (GMAPS)</h2>
                    <div class="flex items-center gap-3 mb-8">
                        <div class="flex text-yellow-400 text-lg">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <span class="text-gray-900 font-bold text-sm">5.0 <span class="text-gray-500 font-medium">/ 5.0 Rating</span></span>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-[#F8F9FA] p-6 rounded-xl border border-gray-100">
                            <h4 class="font-bold text-[#0A192F] text-sm">MAZZQU IQI</h4>
                            <p class="text-gray-600 text-sm italic mt-2">"Pelayanan rental oke banget, harga juga murah, pengiriman juga cepett, oke dahh, semoga di tambah unit lagii..."</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col h-full">
                    <h4 class="text-[#2563EB] font-bold tracking-wider text-xs uppercase mb-2">Lokasi Pusat</h4>
                    <h2 class="text-3xl font-extrabold text-[#0A192F] mb-4 tracking-tight">BANTAR GEBANG, BEKASI</h2>
                    <div class="w-full flex-grow min-h-[300px] bg-gray-100 rounded-xl overflow-hidden shadow-sm border border-gray-200">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15860.505298516135!2d106.9733087!3d-6.3236319!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6993ceaaaaaaab%3A0x133866d7bd49962a!2sAkhasa%20Rentcar!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" class="w-full h-full border-0" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>