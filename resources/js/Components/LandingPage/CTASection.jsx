import React from 'react';
import { 
    DevicePhoneMobileIcon, 
    CloudArrowDownIcon, 
    ShieldCheckIcon,
    SparklesIcon 
} from '@heroicons/react/24/outline';

const CTASection = ({ availableVersions = [] }) => {    const benefits = [
        {
            icon: DevicePhoneMobileIcon,
            title: "Instant Setup",
            description: "Siap pakai dalam 5 menit",
            detail: "Tanpa coding, tanpa ribet"
        },
        {
            icon: CloudArrowDownIcon,
            title: "Cloud Backup",
            description: "Data aman tersimpan otomatis",
            detail: "Akses multi-perangkat"
        },
        {
            icon: ShieldCheckIcon,
            title: "100% Gratis untuk UMKM Mikro",
            description: "Tidak ada biaya langganan, tidak ada fitur premium tersembunyi",
            detail: "Didedikasikan untuk warung, toko kelontong, penjual pinggir jalan, sembako, dll"
        },
        {
            icon: SparklesIcon,
            title: "Fitur Bertambah",
            description: "Update fitur secara berkala sesuai kebutuhan UMKM mikro",
            detail: "Prioritas pada masukan pengguna awal"
        }
    ];

    const urgencyStats = [
        { number: "10-30", label: "Pengguna Aktif Saat Ini" },
        { number: "Baru Launching", label: "Aplikasi Resmi Dibuka" },
        { number: "100% Gratis", label: "Tanpa Biaya Setup & Training" },
        { number: "0%", label: "Biaya Langganan Selamanya" }
    ];

    const handleDownload = (platform) => {
        if (platform === 'android' && availableVersions.android) {
            window.location.href = `/download/android/${availableVersions.android.version}`;
        } else if (platform === 'ios' && availableVersions.ios) {
            window.location.href = `/download/ios/${availableVersions.ios.version}`;
        }
    };

    return (
        <section className="py-16 md:py-24 bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 relative overflow-hidden">
            {/* Background decoration */}
            <div className="absolute inset-0 bg-gradient-to-br from-blue-600/90 via-indigo-700/90 to-purple-800/90"></div>
            <div className="absolute top-0 left-0 w-full h-full bg-[url('/images/pattern.svg')] opacity-10"></div>
            
            {/* Floating elements */}
            <div className="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
            <div className="absolute top-40 right-20 w-32 h-32 bg-white/5 rounded-full animate-pulse delay-1000"></div>
            <div className="absolute bottom-20 left-1/4 w-16 h-16 bg-white/10 rounded-full animate-pulse delay-2000"></div>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">                {/* Main CTA Content */}
                <div className="text-center mb-16">
                    <h2 className="text-4xl md:text-6xl font-bold text-white mb-6 animate-fade-in-up">
                        🚀 Mulai Usaha Digitalmu dengan Gratis!
                    </h2>
                    <p className="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto mb-8 animate-fade-in-up-delay-1 leading-relaxed">
                        ApliKasir baru saja diluncurkan dan didedikasikan sepenuhnya untuk UMKM mikro. Jadilah pengguna awal dan dapatkan dukungan langsung dari tim pengembang!
                    </p>
                    
                    {/* Urgency stats */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto mb-8 animate-fade-in-up-delay-1">
                        {urgencyStats.map((stat, index) => (
                            <div key={index} className="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                <div className="text-2xl md:text-3xl font-bold text-yellow-300">{stat.number}</div>
                                <div className="text-sm text-blue-100">{stat.label}</div>
                            </div>
                        ))}
                    </div>
                    
                    {/* Urgency element */}
                    <div className="inline-block bg-gradient-to-r from-green-400 to-blue-500 text-white px-8 py-4 rounded-full font-bold text-lg mb-8 animate-fade-in-up-delay-2 shadow-2xl animate-pulse">
                        🎉 100% GRATIS untuk UMKM Mikro – Tidak ada biaya apapun!
                    </div>
                </div>

                {/* Download buttons */}
                <div className="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16 animate-fade-in-up-delay-2">
                    <a
                        href='#beranda'
                        className="group bg-white hover:bg-gray-100 text-gray-900 px-8 py-4 rounded-2xl font-bold text-lg flex items-center space-x-4 shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105 min-w-[280px]"
                    >
                        <div className="flex items-center space-x-3">
                            <div className="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                <span className="text-white text-2xl">📱</span>
                            </div>
                            <div className="text-left">
                                <div className="text-sm text-gray-600">Download untuk</div>
                                <div className="text-xl font-bold">Android</div>
                                {availableVersions.android && (
                                    <div className="text-xs text-gray-500">
                                        v{availableVersions.android.version} • {availableVersions.android.size}
                                    </div>
                                )}
                            </div>
                        </div>
                        <div className="text-2xl group-hover:translate-x-1 transition-transform">→</div>
                    </a>                    <div className="group bg-gray-600 text-white px-8 py-4 rounded-2xl font-bold text-lg flex items-center space-x-4 shadow-2xl opacity-75 cursor-not-allowed min-w-[280px]">
                        <div className="flex items-center space-x-3">
                            <div className="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center">
                                <span className="text-white text-2xl">🍎</span>
                            </div>
                            <div className="text-left">
                                <div className="text-sm text-gray-400">Coming Soon</div>
                                <div className="text-xl font-bold">iOS</div>
                                <div className="text-xs text-gray-400">
                                    Segera Hadir
                                </div>
                            </div>
                        </div>
                        <div className="text-2xl text-gray-500">⏳</div>
                    </div>
                </div>                {/* Benefits grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16 animate-fade-in-up-delay-3">
                    {benefits.map((benefit, index) => (
                        <div key={index} className="text-center">
                            <div className="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                                <benefit.icon className="w-8 h-8 text-white" />
                            </div>
                            <h3 className="text-lg font-bold text-white mb-2">{benefit.title}</h3>
                            <p className="text-blue-100 text-sm">{benefit.description}</p>
                        </div>
                    ))}
                </div>

                {/* Final urgency CTA */}
                <div className="text-center animate-fade-in-up-delay-5">
                    <div className="bg-gradient-to-r from-green-400 to-blue-500 text-white rounded-2xl p-6 max-w-2xl mx-auto">
                        <div className="flex items-center justify-center space-x-2 mb-4">
                            <span className="text-2xl">⏰</span>
                            <span className="font-bold text-lg">Ayo Coba Sekarang!</span>
                        </div>
                        <p className="font-semibold mb-4">
                            Download gratis, tidak ada biaya apapun. Dapatkan dukungan langsung dari tim pengembang untuk UMKM mikro.
                        </p>
                        <div className="text-sm">
                            *ApliKasir baru diluncurkan, semua fitur utama sudah bisa digunakan gratis.
                        </div>
                    </div>
                </div>

                {/* Trust indicators */}
                <div className="mt-12 text-center animate-fade-in-up-delay-6">
                    <div className="flex flex-wrap justify-center items-center gap-6 text-sm text-blue-100 opacity-80">
                        <div className="flex items-center space-x-2">
                            <span>🔒</span>
                            <span>SSL Encrypted</span>
                        </div>
                        <div className="flex items-center space-x-2">
                            <span>☁️</span>
                            <span>Cloud Backup</span>
                        </div>
                        <div className="flex items-center space-x-2">
                            <span>🛡️</span>
                            <span>Privacy Protected</span>
                        </div>
                        <div className="flex items-center space-x-2">
                            <span>🇮🇩</span>
                            <span>Made in Indonesia</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default CTASection;
