import React, { useState } from "react";
import { ChevronDownIcon, ChevronUpIcon } from "@heroicons/react/24/outline";

const FAQSection = () => {
    const [openItem, setOpenItem] = useState(null);
    const [activeCategory, setActiveCategory] = useState("All");
    const toggleItem = (index) => {
        setOpenItem((prev) => (prev === index ? null : index));
    };

    const handleCategoryChange = (category) => {
        setActiveCategory(category);
        setOpenItem(null); // Reset open item when category changes
    };
    const faqs = [
        {
            id: "pricing-gratis",
            question: "Apakah ApliKasir benar-benar gratis selamanya?",
            answer: "Ya! ApliKasir menawarkan paket Starter yang 100% gratis selamanya tanpa biaya tersembunyi. Anda bisa melakukan transaksi unlimited, mengelola hingga 100 produk, dan mendapatkan laporan dasar tanpa biaya. Upgrade ke paket berbayar hanya jika Anda membutuhkan fitur advanced seperti multi-device, barcode scanner, analytics mendalam, atau support prioritas.",
            category: "Pricing",
            popular: true,
        },
        {
            id: "features-barcode",
            question:
                "Bagaimana cara menggunakan fitur barcode scanner untuk transaksi?",
            answer: "Fitur barcode scanner terintegrasi langsung di aplikasi mobile ApliKasir. Dalam menu Kelola Produk, tap tombol scan untuk menambah produk baru dengan barcode. Saat checkout, gunakan mode 'Scan to Cart' untuk menambah produk ke keranjang dengan cepat. Scanner mendukung berbagai format: EAN13, EAN8, UPCA, Code128, Code39, QR Code. Dilengkapi flashlight toggle dan haptic feedback untuk scanning yang lebih mudah.",
            category: "Features",
            popular: true,
        },
        {
            id: "features-qris",
            question: "Bagaimana sistem pembayaran QRIS bekerja di ApliKasir?",
            answer: "Sistem QRIS di ApliKasir bersifat statis: pelanggan perlu memasukkan nominal sesuai total transaksi secara manual di aplikasi wallet atau bank digital mereka. QR code menampilkan data merchant, namun jumlah harus diinput oleh pengguna sesuai harga saat transaksi.",
            category: "Features",
            popular: true,
        },
        {
            id: "features-kredit",
            question:
                "Apakah ApliKasir memiliki sistem manajemen kredit/hutang pelanggan?",
            answer: "Ya! ApliKasir dilengkapi sistem kredit yang komprehensif. Anda bisa mencatat penjualan kredit dengan memilih customer dan metode 'Kredit' saat checkout. Sistem akan otomatis mencatat hutang pelanggan, menampilkan daftar hutang aktif, dan memungkinkan pembayaran cicilan. Fitur ini sangat cocok untuk warung, toko kelontong, atau bisnis dengan sistem bon/tab pelanggan tetap.",
            category: "Features",
            popular: true,
        },
        {
            id: "features-inventory",
            question: "Bagaimana cara mengelola stok produk dan inventory?",
            answer: "ApliKasir menyediakan sistem inventory yang powerful. Tambah produk dengan input manual atau scan barcode langsung. Sistem otomatis mengurangi stok saat transaksi penjualan. Fitur filter stok (stok rendah, habis, semua) membantu monitoring inventory. Setiap produk bisa memiliki foto, barcode, harga modal, harga jual, dan tracking stok real-time. Perfect untuk retail, minimarket, atau toko dengan banyak produk.",
            category: "Features",
            popular: true,
        },
        {
            id: "security-data",
            question: "Apakah data saya aman di ApliKasir?",
            answer: "Sangat aman! Data tersimpan local di device dengan enkripsi SQLite, dan tersinkronisasi ke cloud server dengan standar keamanan tinggi. Sistem backup otomatis memastikan data tidak hilang. Aplikasi mobile bekerja offline, jadi tetap bisa bertransaksi meski internet terputus. Data akan sync otomatis saat koneksi kembali normal.",
            category: "Security",
            popular: true,
        },
        {
            id: "reports-penjualan",
            question:
                "Bagaimana sistem laporan penjualan dan history transaksi?",
            answer: "ApliKasir menyediakan laporan lengkap dengan filter berdasarkan tanggal, metode pembayaran, dan customer. History transaksi menampilkan detail lengkap: produk terjual, quantity, total, profit margin, dan metode pembayaran. Setiap transaksi tersimpan dengan receipt digital yang bisa dilihat ulang. Laporan real-time membantu analisis penjualan harian, mingguan, atau bulanan untuk decision making yang better.",
            category: "Reports",
        },
        {
            id: "features-customer",
            question: "Apakah bisa mengelola data customer dan membership?",
            answer: "Absolutely! Fitur Customer Management memungkinkan Anda menyimpan data pelanggan lengkap: nama, kontak, alamat, dan history pembelian. Sistem otomatis mencatat customer yang sering berbelanja. Saat checkout, pilih customer untuk transaksi kredit atau untuk tracking loyalty. Data customer terintegrasi dengan sistem kredit, sehingga mudah monitoring hutang dan payment history per customer.",
            category: "Features",
        },
        {
            id: "features-checkout",
            question: "Bagaimana proses checkout dan pembayaran bekerja?",
            answer: "Proses checkout di ApliKasir sangat streamlined. Scan produk atau pilih dari daftar untuk menambah ke cart, atur quantity dengan tombol +/-, pilih customer (jika perlu), pilih metode pembayaran (Tunai/QRIS/Kredit). Untuk tunai, masukkan jumlah bayar dan sistem hitung kembalian otomatis. Untuk QRIS, scan QR code yang muncul. Semua transaksi tersimpan dengan receipt lengkap dan update stok otomatis.",
            category: "Features",
        },
        {
            id: "technical-offline",
            question: "Apakah ApliKasir bisa digunakan offline tanpa internet?",
            answer: "Ya! Ini keunggulan utama ApliKasir. Aplikasi mobile bekerja full offline menggunakan database local SQLite. Anda bisa scan barcode, checkout, manage produk, lihat laporan tanpa koneksi internet. Data akan tersinkronisasi otomatis ke cloud saat internet tersedia. Perfect untuk lokasi dengan koneksi tidak stabil atau untuk emergency backup saat wifi down.",
            category: "Technical",
            popular: true,
        },
        {
            id: "setup-konfigurasi",
            question: "Bagaimana cara setup dan konfigurasi awal ApliKasir?",
            answer: "Setup ApliKasir sangat mudah! Download aplikasi mobile, register akun baru, dan Anda langsung bisa mulai. Setup QRIS melalui menu Kelola > QRIS dengan memasukkan kode merchant. Tambah produk pertama dengan scan barcode atau input manual. Setup customer favorit, dan Anda siap beroperasi. Onboarding guide terintegrasi di aplikasi memandu step-by-step. Total setup time: 15-30 menit saja!",
            category: "Setup",
        },
        {
            id: "enterprise-franchise",
            question:
                "Apakah ada fitur untuk bisnis franchise atau multi-cabang?",
            answer: "Saat ini ApliKasir mobile fokus untuk single outlet/cabang dengan database local yang powerful. Untuk multi-cabang, setiap cabang menggunakan aplikasi terpisah dengan akun masing-masing. Data tiap cabang tersinkronisasi ke dashboard web admin untuk consolidated reporting. Fitur multi-outlet dengan shared inventory sedang dalam development untuk release mendatang.",
            category: "Enterprise",
        },
        {
            id: "features-receipt",
            question: "Bagaimana sistem receipt dan struk digital?",
            answer: "ApliKasir menghasilkan receipt digital lengkap untuk setiap transaksi. Receipt menampilkan: detail produk, quantity, harga, total, metode pembayaran, tanggal/waktu, dan informasi merchant. Receipt tersimpan permanent dan bisa diakses kembali melalui History Transaksi. Format receipt bersih dan professional, cocok untuk dicetak atau dibagikan digital ke customer.",
            category: "Features",
        },
        {
            id: "support-training",
            question: "Apakah ada training atau panduan menggunakan aplikasi?",
            answer: "Ya! ApliKasir dilengkapi onboarding flow yang user-friendly di dalam aplikasi. Tersedia tutorial interaktif untuk fitur utama: scanning barcode, checkout flow, setup QRIS, dan management produk. UI/UX dirancang intuitif dengan Bahasa Indonesia, sehingga mudah dipahami tanpa training khusus. Plus, dokumentasi lengkap dan customer support available untuk bantuan advanced.",
            category: "Support",
        },
        {
            id: "technical-spesifikasi",
            question:
                "Berapa storage yang dibutuhkan dan spesifikasi minimum device?",
            answer: "ApliKasir mobile sangat ringan! Storage requirement: 50-100MB untuk aplikasi + database. Berjalan smooth di Android 6.0+ dengan RAM minimal 2GB. Aplikasi optimized untuk performa tinggi bahkan di device entry-level. Database SQLite local memastikan response time cepat. Camera permission diperlukan untuk barcode scanning. Kompatibel dengan hampir semua smartphone Android modern.",
            category: "Technical",
        },
        {
            id: "security-transaksi",
            question:
                "Bagaimana sistem keamanan transaksi dan data protection?",
            answer: "ApliKasir menerapkan multiple layer security: (1) Data encryption di local database, (2) Secure API communication untuk sync, (3) User authentication dengan password protection, (4) Transaction logging dengan timestamp yang tidak bisa diedit, (5) Regular backup ke cloud storage. Tidak ada data sensitive yang tersimpan di plain text. Compliance dengan regulasi data protection Indonesia.",
            category: "Security",
        },
        {
            id: "features-pricing",
            question: "Apakah bisa custom pricing atau discount per customer?",
            answer: "Saat ini ApliKasir menggunakan fixed pricing per produk. Namun, sistem customer management memungkinkan tracking khusus per pelanggan. Untuk discount atau harga khusus, bisa diatur manual saat checkout. Fitur customer-specific pricing dan automated discount rules sedang dalam roadmap development untuk memberikan fleksibilitas pricing yang lebih advanced di update mendatang.",
            category: "Features",
        },
        {
            id: "technical-backup",
            question:
                "Bagaimana cara backup dan restore data jika ganti device?",
            answer: "Data backup di ApliKasir berlapis: (1) Auto sync ke cloud server secara regular, (2) Local backup di device storage, (3) Export manual ke file CSV/Excel kapan saja. Saat ganti device, login dengan akun yang sama dan data akan otomatis ter-restore dari cloud. Proses restoration aman dan cepat. Tidak ada resiko kehilangan data transaction history, produk, atau customer data.",
            category: "Technical",
        },
        {
            id: "technical-currency",
            question: "Apakah mendukung multiple mata uang atau hanya Rupiah?",
            answer: "Saat ini ApliKasir dioptimasi untuk market Indonesia dengan format Rupiah (Rp). Semua pricing, calculation, dan reporting menggunakan format currency Indonesia dengan separator ribuan dan tanpa desimal. Sistem QRIS juga khusus untuk standar pembayaran Indonesia. Support untuk multiple currency sedang dipertimbangkan untuk expansion ke market regional ASEAN.",
            category: "Technical",
        },
        {
            id: "general-pembeda",
            question:
                "Apa yang membedakan ApliKasir dengan aplikasi kasir lainnya?",
            answer: "ApliKasir unggul di: (1) Full offline capability dengan sync otomatis, (2) Barcode scanner terintegrasi untuk product management, (3) QRIS integration sesuai standar Bank Indonesia, (4) Sistem kredit/hutang pelanggan yang lengkap, (5) UI/UX modern dengan Bahasa Indonesia, (6) Gratis tanpa limit transaksi, (7) Database local yang cepat dan reliable, (8) Made specifically untuk UMKM Indonesia dengan fitur yang sesuai kebutuhan lokal.",
            category: "General",
            popular: true,
        },
    ];
    const categories = [
        { name: "All", icon: "🏠", count: faqs.length },
        {
            name: "Pricing",
            icon: "💰",
            count: faqs.filter((faq) => faq.category === "Pricing").length,
        },
        {
            name: "Features",
            icon: "⚡",
            count: faqs.filter((faq) => faq.category === "Features").length,
        },
        {
            name: "Security",
            icon: "🔒",
            count: faqs.filter((faq) => faq.category === "Security").length,
        },
        {
            name: "Technical",
            icon: "🛠️",
            count: faqs.filter((faq) => faq.category === "Technical").length,
        },
        {
            name: "Reports",
            icon: "📊",
            count: faqs.filter((faq) => faq.category === "Reports").length,
        },
        {
            name: "Support",
            icon: "🎧",
            count: faqs.filter((faq) => faq.category === "Support").length,
        },
        {
            name: "Setup",
            icon: "⚙️",
            count: faqs.filter((faq) => faq.category === "Setup").length,
        },
        {
            name: "Enterprise",
            icon: "🏢",
            count: faqs.filter((faq) => faq.category === "Enterprise").length,
        },
        {
            name: "General",
            icon: "📱",
            count: faqs.filter((faq) => faq.category === "General").length,
        },
    ];

    const filteredFaqs =
        activeCategory === "All"
            ? faqs
            : faqs.filter((faq) => faq.category === activeCategory);
    // Prepare two separate columns
    const indexedFaqs = filteredFaqs.map((faq, idx) => ({ faq, idx }));
    const half = Math.ceil(indexedFaqs.length / 2);
    const leftFaqs = indexedFaqs.slice(0, half);
    const rightFaqs = indexedFaqs.slice(half);

    return (
        <section
            className="py-16 md:py-24 bg-gradient-to-br from-gray-50 to-blue-50 relative overflow-hidden"
            id="faq"
        >
            <div className="absolute top-0 left-0 w-64 h-64 bg-blue-100 rounded-full -translate-x-32 -translate-y-32 opacity-50"></div>
            <div className="absolute bottom-0 right-0 w-80 h-80 bg-indigo-100 rounded-full translate-x-40 translate-y-40 opacity-50"></div>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div className="text-center mb-16">
                    {" "}
                    <div className="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full mb-6 animate-fade-in-up">
                        <span className="text-blue-700 font-semibold text-sm">
                            💡 Jawaban untuk 20 pertanyaan paling sering
                            ditanyakan
                        </span>
                    </div>
                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6 animate-fade-in-up">
                        Pertanyaan yang Sering Ditanyakan
                    </h2>
                    <p className="text-lg text-gray-600 max-w-3xl mx-auto animate-fade-in-up-delay-1 mb-8">
                        Temukan jawaban lengkap untuk semua keraguan Anda
                        tentang ApliKasir. Tim ahli kami telah menyiapkan
                        panduan komprehensif berdasarkan pengalaman 50,000+
                        bisnis yang telah bergabung. Tidak menemukan jawaban
                        yang Anda cari?
                        <a
                            href="#contact"
                            className="text-blue-600 hover:text-blue-700 font-semibold ml-1"
                        >
                            Hubungi kami langsung
                        </a>
                    </p>
                    <div className="flex flex-wrap gap-3 justify-center mb-8 animate-fade-in-up-delay-2">
                        {" "}
                        {categories.map((category) => (
                            <button
                                key={category.name}
                                onClick={() =>
                                    handleCategoryChange(category.name)
                                }
                                className={`px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 transform hover:scale-105 ${
                                    activeCategory === category.name
                                        ? "bg-blue-500 text-white shadow-lg shadow-blue-500/25"
                                        : "bg-white text-gray-600 hover:bg-blue-50 hover:text-blue-600 shadow-md"
                                }`}
                            >
                                <span className="mr-2">{category.icon}</span>
                                {category.name}
                                <span className="ml-2 text-xs opacity-75">
                                    ({category.count})
                                </span>
                            </button>
                        ))}
                    </div>
                    {activeCategory === "All" && (
                        <div className="mb-6 animate-fade-in-up-delay-3">
                            <div className="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-100 to-yellow-100 rounded-full">
                                <span className="text-amber-600 font-medium text-sm">
                                    ⭐ Menampilkan{" "}
                                    {
                                        filteredFaqs.filter(
                                            (faq) => faq.popular,
                                        ).length
                                    }{" "}
                                    pertanyaan paling populer dari business
                                    owner
                                </span>
                            </div>
                        </div>
                    )}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-2xl mx-auto mb-8 animate-fade-in-up-delay-3">
                        <div className="text-center">
                            <div className="text-2xl font-bold text-blue-600">
                                20
                            </div>
                            <div className="text-sm text-gray-600">
                                FAQ Lengkap
                            </div>
                        </div>
                        <div className="text-center">
                            <div className="text-2xl font-bold text-green-600">
                                &lt; 5 min
                            </div>
                            <div className="text-sm text-gray-600">
                                Response Time
                            </div>
                        </div>
                        <div className="text-center">
                            <div className="text-2xl font-bold text-purple-600">
                                98%
                            </div>
                            <div className="text-sm text-gray-600">
                                Solved Rate
                            </div>
                        </div>
                    </div>
                </div>{" "}
                {/* Two separate columns for FAQs */}
                <div
                    className={` gap-5 ${
                        leftFaqs.length > 1 ? "lg:flex lg:space-x-6" : ""
                    }`}
                >
                    <div
                        className={
                            leftFaqs.length > 1
                                ? "flex-1 space-y-4"
                                : "w-full space-y-4"
                        }
                    >
                        {leftFaqs.map(({ faq, idx }) => (
                            <div
                                key={idx}
                                className={`bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border-l-4 ${
                                    faq.popular
                                        ? "border-l-amber-400 ring-2 ring-amber-100"
                                        : "border-l-blue-400"
                                }`}
                            >
                                <button
                                    onClick={() => toggleItem(idx)}
                                    className="w-full px-6 py-6 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200"
                                    aria-expanded={openItem === idx}
                                >
                                    <div className="flex items-start space-x-3 pr-4">
                                        {faq.popular && (
                                            <div className="flex-shrink-0 mt-1">
                                                <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    ⭐ Popular
                                                </span>
                                            </div>
                                        )}
                                        <span className="text-lg font-semibold text-gray-900">
                                            {faq.question}
                                        </span>
                                    </div>
                                    <div className="flex-shrink-0">
                                        {openItem === idx ? (
                                            <ChevronUpIcon className="w-6 h-6 text-blue-500" />
                                        ) : (
                                            <ChevronDownIcon className="w-6 h-6 text-gray-400" />
                                        )}
                                    </div>
                                </button>
                                {openItem === idx && (
                                    <div className="transition-all duration-300 ease-in-out max-h-96 opacity-100 overflow-hidden">
                                        <div className="px-6 pb-6">
                                            <div className="border-t border-gray-100 pt-4">
                                                <div className="flex items-start space-x-3">
                                                    <div className="flex-shrink-0 mt-1">
                                                        <div className="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <span className="text-blue-600 text-sm">
                                                                💡
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p className="text-gray-600 leading-relaxed">
                                                        {faq.answer}
                                                    </p>
                                                </div>

                                                <div className="mt-4 flex items-center justify-between">
                                                    <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                        📁 {faq.category}
                                                    </span>
                                                    <div className="text-xs text-gray-400">
                                                        Helpful?
                                                        <button className="ml-2 text-green-500 hover:text-green-600">
                                                            👍
                                                        </button>
                                                        <button className="ml-1 text-red-500 hover:text-red-600">
                                                            👎
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>

                    {rightFaqs?.length > 0 && (
                        <div className="flex-1 space-y-4">
                            {rightFaqs.map(({ faq, idx }) => (
                                <div
                                    key={idx}
                                    className={`bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border-l-4 ${
                                        faq.popular
                                            ? "border-l-amber-400 ring-2 ring-amber-100"
                                            : "border-l-blue-400"
                                    }`}
                                >
                                    <button
                                        onClick={() => toggleItem(idx)}
                                        className="w-full px-6 py-6 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200"
                                        aria-expanded={openItem === idx}
                                    >
                                        <div className="flex items-start space-x-3 pr-4">
                                            {faq.popular && (
                                                <div className="flex-shrink-0 mt-1">
                                                    <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                        ⭐ Popular
                                                    </span>
                                                </div>
                                            )}
                                            <span className="text-lg font-semibold text-gray-900">
                                                {faq.question}
                                            </span>
                                        </div>
                                        <div className="flex-shrink-0">
                                            {openItem === idx ? (
                                                <ChevronUpIcon className="w-6 h-6 text-blue-500" />
                                            ) : (
                                                <ChevronDownIcon className="w-6 h-6 text-gray-400" />
                                            )}
                                        </div>
                                    </button>
                                    {openItem === idx && (
                                        <div className="transition-all duration-300 ease-in-out max-h-96 opacity-100 overflow-hidden">
                                            <div className="px-6 pb-6">
                                                <div className="border-t border-gray-100 pt-4">
                                                    <div className="flex items-start space-x-3">
                                                        <div className="flex-shrink-0 mt-1">
                                                            <div className="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                                                <span className="text-blue-600 text-sm">
                                                                    💡
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <p className="text-gray-600 leading-relaxed">
                                                            {faq.answer}
                                                        </p>
                                                    </div>

                                                    <div className="mt-4 flex items-center justify-between">
                                                        <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                            📁 {faq.category}
                                                        </span>
                                                        <div className="text-xs text-gray-400">
                                                            Helpful?
                                                            <button className="ml-2 text-green-500 hover:text-green-600">
                                                                👍
                                                            </button>
                                                            <button className="ml-1 text-red-500 hover:text-red-600">
                                                                👎
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    )}
                </div>
                <div className="mt-16 text-center animate-fade-in-up-delay-3">
                    <div className="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-3xl p-8 md:p-12 text-white">
                        <h3 className="text-2xl md:text-3xl font-bold mb-4">
                            Masih Ada Pertanyaan?
                        </h3>
                        <p className="text-xl opacity-90 mb-8 max-w-2xl mx-auto">
                            Tim customer success kami siap membantu Anda 24/7.
                            Dapatkan jawaban langsung dari ahlinya!
                        </p>

                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <a
                                href="https://wa.me/6281234567890?text=Halo%2C%20saya%20ingin%20tanya%20tentang%20ApliKasir"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center justify-center px-8 py-4 bg-green-500 hover:bg-green-600 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg"
                            >
                                <span className="mr-2">💬</span>
                                Chat WhatsApp
                            </a>

                            <a
                                href="mailto:support@aplikasir.com"
                                className="inline-flex items-center justify-center px-8 py-4 bg-red-500 text-white hover:bg-red-600 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg"
                            >
                                <span className="mr-2">📧</span>
                                Email Support
                            </a>
                        </div>

                        <div className="mt-6 flex flex-col sm:flex-row items-center justify-center text-sm opacity-80 space-y-2 sm:space-y-0 sm:space-x-6">
                            <div className="flex items-center">
                                <span className="mr-2">⚡</span>
                                Response time: &lt; 5 menit
                            </div>
                            <div className="flex items-center">
                                <span className="mr-2">🌟</span>
                                Satisfaction rate: 98%
                            </div>
                            <div className="flex items-center">
                                <span className="mr-2">🔒</span>
                                Keamanan data terjamin
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default FAQSection;
