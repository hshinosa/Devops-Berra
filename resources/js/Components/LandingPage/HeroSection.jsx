import React from "react";
import { Link } from "@inertiajs/react";
import "./IconAnimations.css";
import "./SectionAnimations.css";

// Hero Section Component with geometric elements and subtle shadows
const HeroSection = ({ availableVersions = [] }) => {
    // Definisikan keyframes dan animasi langsung dalam inline style
    const animationStyles = {
        "@keyframes floatAnim": {
            "0%, 100%": { transform: "translateY(0px)" },
            "50%": { transform: "translateY(-15px)" },
        },
        "@keyframes pulseAnim": {
            "0%, 100%": { transform: "scale(1)" },
            "50%": { transform: "scale(1.1)" },
        },
    };

    // Function to format file size
    const formatFileSize = (bytes) => {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    };

    // Function to format date
    const formatDate = (timestamp) => {
        return new Date(timestamp * 1000).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    };

    // Style untuk animasi ikon
    const iconAnimations = {
        float: {
            animation: "floatAnim 5s ease-in-out infinite",
            transform: "translateY(0px)",
        },
        floatDelay1: {
            animation: "floatAnim 5s ease-in-out infinite 0.8s",
            transform: "translateY(0px)",
        },
        floatDelay2: {
            animation: "floatAnim 5s ease-in-out infinite 1.6s",
            transform: "translateY(0px)",
        },
        pulse: {
            animation: "pulseAnim 4s cubic-bezier(0.4, 0, 0.6, 1) infinite",
            transform: "scale(1)",
        },
        pulseDelay1: {
            animation:
                "pulseAnim 4s cubic-bezier(0.4, 0, 0.6, 1) infinite 1.5s",
            transform: "scale(1)",
        },
        pulseDelay2: {
            animation:
                "pulseAnim 4s cubic-bezier(0.4, 0, 0.6, 1) infinite 2.7s",
            transform: "scale(1)",
        },
    };

    return (
        <section
            className="pt-24 pb-16 md:pt-32 md:pb-24 min-h-screen flex items-center relative overflow-hidden bg-white"
            id="beranda"
        >
            {" "}
            {/* Background with grid pattern */}
            <div className="absolute inset-0 bg-gray-50 -z-20"></div>
            {/* Grid lines - using direct CSS instead of components */}
            <div
                className="absolute inset-0 z-0"
                style={{
                    backgroundImage: `
                    linear-gradient(to right, rgba(59, 130, 246, 0.08) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(59, 130, 246, 0.08) 1px, transparent 1px),
                    radial-gradient(circle, rgba(59, 130, 246, 0.1) 1px, transparent 1px)
                `,
                    backgroundSize: "80px 80px, 80px 80px, 40px 40px",
                    backgroundPosition: "0px 0px",
                }}
            ></div>
            {/* Overlay with faded grid for more depth */}
            <div
                className="absolute inset-0 z-0"
                style={{
                    backgroundImage: `
                    linear-gradient(to right, rgba(59, 130, 246, 0.05) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(59, 130, 246, 0.05) 1px, transparent 1px)
                `,
                    backgroundSize: "40px 40px",
                    backgroundPosition: "0px 0px",
                }}
            ></div>
            <div className="container mx-auto px-6 flex flex-col md:flex-row items-center relative">
                {" "}
                <div className="md:w-1/2 lg:w-3/5 text-center md:text-left mb-12 md:mb-0 z-10">
                    <h1 className="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-800 mb-6 leading-tight animate-fade-in-up">
                        Jadikan Kegiatan Transaksi Anda Menjadi Lebih Mudah
                    </h1>
                    <p className="text-lg text-gray-600 mb-8 max-w-xl mx-auto md:mx-0 animate-fade-in-up-delay-1">
                        Memulai hal kecil dalam membantu mengelola pendapatan
                        dan pengeluaran usahamu agar lebih mudah, praktis dan
                        terjaga. Untuk langkah yang lebih cerah
                    </p>
                    {/* Download Section */}
                    <div className="space-y-4 animate-fade-in-up-delay-2">
                        <div className="space-y-3">
                            <h3 className="text-xl font-semibold text-gray-800 mb-4">
                                Download Aplikasi Mobile
                            </h3>
                            {availableVersions.length > 0 ? (
                                <a
                                    href={availableVersions[0].download_url}
                                    className="inline-flex items-center px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold text-lg rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                                    download
                                >
                                    <div className="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-3">
                                        <svg
                                            className="w-5 h-5 text-green-600"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993.0001.5511-.4482.9997-.9993.9997m-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1518-.5703.416.416 0 00-.5703.1518l-2.0223 3.503C15.5484 8.6845 13.8273 8.3516 12 8.3516s-3.5484.3329-5.1851.7987L4.7926 5.6473a.4161.4161 0 00-.5703-.1518.416.416 0 00-.1518.5703l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.761h24c-.3435-4.1021-2.6892-7.5743-6.1185-9.4396" />
                                        </svg>
                                    </div>
                                    <div className="text-left">
                                        <div className="font-bold">
                                            Download Aplikasi Android
                                        </div>
                                        <div className="text-sm text-green-100 font-normal">
                                            {availableVersions[0]
                                                ? `${formatFileSize(availableVersions[0].size)} • ${formatDate(availableVersions[0].last_modified)}`
                                                : "Aplikasir Mobile"}
                                        </div>
                                    </div>
                                    <svg
                                        className="w-5 h-5 ml-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </a>
                            ) : (
                                <a
                                    href="#"
                                    className="inline-flex items-center px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold text-lg rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                                    onClick={(e) => {
                                        e.preventDefault();
                                        alert(
                                            "File APK sedang tidak tersedia. Silakan coba lagi nanti.",
                                        );
                                    }}
                                >
                                    <div className="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-3">
                                        <svg
                                            className="w-5 h-5 text-green-600"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993.0001.5511-.4482.9997-.9993.9997m-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1518-.5703.416.416 0 00-.5703.1518l-2.0223 3.503C15.5484 8.6845 13.8273 8.3516 12 8.3516s-3.5484.3329-5.1851.7987L4.7926 5.6473a.4161.4161 0 00-.5703-.1518.416.416 0 00-.1518.5703l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.761h24c-.3435-4.1021-2.6892-7.5743-6.1185-9.4396" />
                                        </svg>
                                    </div>
                                    <div className="text-left">
                                        <div className="font-bold">
                                            Download Aplikasi Android
                                        </div>
                                        <div className="text-sm text-green-100 font-normal">
                                            Aplikasir Mobile
                                        </div>
                                    </div>
                                    <svg
                                        className="w-5 h-5 ml-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </a>
                            )}
                        </div>
                    </div>
                </div>
                <div className="md:w-1/2 lg:w-2/5 flex flex-col items-center justify-center z-10">
                    {" "}
                    {/* Main image dengan ikon menyebar di sekitarnya */}{" "}
                    <div className="relative w-[400px] h-[400px] md:w-[450px] md:h-[450px] flex items-center justify-center mb-4">
                        {/* Main image (central) */}
                        <div className="bg-blue-500 rounded-full w-[280px] h-[280px] md:w-[320px] md:h-[320px] flex items-center justify-center shadow-xl z-10 animate-blob">
                            <img
                                src="/images/hero-section.svg"
                                alt="E-commerce solution"
                                className="w-[80%] h-[80%] object-contain"
                            />
                        </div>
                        {/* Ikon tersebar di sekitar gambar utama */}
                        {/* Kasir Icon - top left */}
                        <div className="absolute top-0 left-5 bg-white rounded-full p-3 shadow-xl flex items-center justify-center w-16 h-16 hover:shadow-blue-300/70 hover:scale-110 transition-all duration-300 icon-float animate-icon">
                            <div className="bg-blue-500 w-8 h-6 rounded-sm flex items-center justify-center">
                                <div className="bg-white w-4 h-2 rounded-sm"></div>
                            </div>
                            <span className="absolute -bottom-6 text-xs font-medium text-gray-600">
                                Kasir
                            </span>
                        </div>

                        {/* Keranjang Icon - top right */}
                        <div className="absolute top-10 right-0 bg-white rounded-full p-3 shadow-xl flex items-center justify-center w-16 h-16 hover:shadow-orange-300/70 hover:scale-110 transition-all duration-300 icon-float-delay1 animate-icon-delay-1">
                            <div className="bg-orange-400 w-8 h-7 rounded-md relative">
                                <div className="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-6 h-3 border-2 border-blue-500 rounded-t-full"></div>
                            </div>
                            <span className="absolute -bottom-6 text-xs font-medium text-gray-600">
                                Keranjang
                            </span>
                        </div>
                        {/* Scan QR Icon - mid left */}
                        <div className="absolute top-1/2 -translate-y-1/2 left-0 bg-white rounded-full p-3 shadow-xl flex items-center justify-center w-16 h-16 hover:shadow-green-300/70 hover:scale-110 transition-all duration-300 icon-pulse animate-icon-delay-2">
                            <div className="grid grid-cols-2 gap-1 w-8 h-8">
                                <div className="bg-green-500 rounded-sm"></div>
                                <div className="bg-green-500 rounded-sm"></div>
                                <div className="bg-green-500 rounded-sm"></div>
                                <div className="bg-green-500 rounded-sm"></div>
                            </div>
                            <span className="absolute -bottom-6 text-xs font-medium text-gray-600">
                                Scan QR
                            </span>
                        </div>
                        {/* Kredit Icon - mid right */}
                        <div className="absolute top-1/2 -translate-y-1/2 right-0 bg-white rounded-full p-3 shadow-xl flex items-center justify-center w-16 h-16 hover:shadow-purple-300/70 hover:scale-110 transition-all duration-300 icon-pulse-delay1 animate-icon-delay-3">
                            <div className="bg-purple-500 w-8 h-8 rounded-full flex items-center justify-center font-bold text-white">
                                <div className="flex items-center justify-center">
                                    <span className="text-xl">₹</span>
                                </div>
                            </div>
                            <span className="absolute -bottom-6 text-xs font-medium text-gray-600">
                                Kredit
                            </span>
                        </div>

                        {/* Stok Icon - bottom left */}
                        <div className="absolute bottom-10 left-10 bg-white rounded-full p-3 shadow-xl flex items-center justify-center w-16 h-16 hover:shadow-yellow-300/70 hover:scale-110 transition-all duration-300 icon-pulse-delay2 animate-icon-delay-4">
                            <div className="w-8 h-8">
                                <div className="bg-yellow-400 w-8 h-2 mb-1 rounded-sm"></div>
                                <div className="bg-yellow-400 w-6 h-2 mb-1 rounded-sm"></div>
                                <div className="bg-yellow-400 w-4 h-2 rounded-sm"></div>
                            </div>
                            <span className="absolute -bottom-6 text-xs font-medium text-gray-600">
                                Stok
                            </span>
                        </div>
                        {/* Pelanggan Icon - bottom right */}
                        <div className="absolute bottom-0 right-10 bg-white rounded-full p-3 shadow-xl flex items-center justify-center w-16 h-16 hover:shadow-cyan-300/70 hover:scale-110 transition-all duration-300 icon-float-delay2 animate-icon-delay-5">
                            <div className="w-8 h-8 flex flex-col items-center">
                                <div className="bg-cyan-500 w-4 h-4 rounded-full"></div>
                                <div className="bg-cyan-500 w-6 h-3 mt-1 rounded-t-md"></div>
                            </div>
                            <span className="absolute -bottom-6 text-xs font-medium text-gray-600">
                                Pelanggan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default HeroSection;
