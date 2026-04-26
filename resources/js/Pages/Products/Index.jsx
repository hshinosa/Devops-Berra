import React, { useState, useEffect, useRef } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import Pagination from '@/Components/Pagination';
import Sidebar from '@/Components/Dashboard/Sidebar';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader';
import PageTransition from '@/Components/PageTransition';
import FlashMessage from '@/Components/FlashMessage';

export default function Index({ products, filters = {} }) {
    const { flash } = usePage().props || {};
    const [deleting, setDeleting] = useState(null);
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [activeFilter, setActiveFilter] = useState(filters.filter || '');
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);

    // Close dropdown when clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setDropdownOpen(false);
            }
        };

        if (dropdownOpen) {
            document.addEventListener('mousedown', handleClickOutside);
            return () => document.removeEventListener('mousedown', handleClickOutside);
        }
    }, [dropdownOpen]);    const confirmDelete = (productId) => {
        setDeleting(productId);
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            router.delete(route('products.destroy', productId));
        }
        setDeleting(null);
    };
    
    const handleSearch = (e) => {
        e.preventDefault();        router.get(route('products.index'), {
            search: searchTerm,
            filter: activeFilter,
        }, {
            preserveState: true,
            replace: true,
        });
    };
    
    const handleFilterChange = (filter) => {
        setActiveFilter(filter);
        router.get(route('products.index'), {
            search: searchTerm,
            filter: filter,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <div className="min-h-screen bg-white">
            <Head title="Produk" />
            
            {/* Header */}            <DashboardHeader 
                pageTitle="Produk" 
                breadcrumb={[{ label: 'Produk' }]}
            />
            
            {/* Layout Utama */}
            <div className="flex min-h-screen pt-20">
                <Sidebar />

                {/* Konten Utama */}                <main className="flex-1 ml-64 p-6 lg:p-8 overflow-y-auto bg-white">
                    <PageTransition>
                        {flash && flash.success && (
                            <FlashMessage message={flash.success} type="success" />
                        )}

                        {flash && flash.error && (
                            <FlashMessage message={flash.error} type="error" />
                        )}                        <div className="flex justify-between items-center mb-6">
                            <h3 className="text-xl font-semibold text-gray-800">Data Produk</h3>
                            <div ref={dropdownRef} className="relative">
                                <button
                                    type="button"
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        setDropdownOpen(!dropdownOpen);
                                    }}
                                    className="px-4 py-2 text-white text-sm bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none transition-colors duration-300 inline-flex items-center gap-2"
                                >
                                    Tambah Produk
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                {dropdownOpen && (
                                    <div className="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                        <div className="py-1">
                                            <Link
                                                href={route('products.create')}
                                                onClick={() => setDropdownOpen(false)}
                                                className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            >
                                                Tambah Produk ke Inventori
                                            </Link>
                                            <Link
                                                href={route('global-products.create')}
                                                onClick={() => setDropdownOpen(false)}
                                                className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            >
                                                Tambah Produk Global
                                            </Link>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                        
                        <div className="mb-6 bg-white p-4 rounded-lg shadow-sm">
                            <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4">
                                <div className="flex-1">
                                    <input
                                        type="text"
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        placeholder="Cari berdasarkan nama, kode, kategori, atau merek..."
                                        className="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                </div>
                                <div className="flex gap-2">
                                    <button 
                                        type="submit"
                                        className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-300"
                                    >
                                        Cari
                                    </button>
                                    {searchTerm && (
                                        <button 
                                            type="button"
                                            onClick={() => {
                                                setSearchTerm('');
                                                router.get(route('products.index'), { filter: activeFilter });
                                            }}
                                            className="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-300"
                                        >
                                            Reset
                                        </button>
                                    )}
                                </div>
                            </form>
                            
                            <div className="mt-3 flex flex-wrap gap-2">
                                <button 
                                    onClick={() => handleFilterChange('')}
                                    className={`px-3 py-1 text-xs rounded-full ${activeFilter === '' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}`}
                                >
                                    Semua
                                </button>
                                <button 
                                    onClick={() => handleFilterChange('active')}
                                    className={`px-3 py-1 text-xs rounded-full ${activeFilter === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`}
                                >
                                    Aktif
                                </button>
                                <button 
                                    onClick={() => handleFilterChange('inactive')}
                                    className={`px-3 py-1 text-xs rounded-full ${activeFilter === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'}`}
                                >
                                    Tidak Aktif
                                </button>
                                <button 
                                    onClick={() => handleFilterChange('kategori')}
                                    className={`px-3 py-1 text-xs rounded-full ${activeFilter === 'kategori' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}`}
                                >
                                    Berdasarkan Kategori
                                </button>
                                <button 
                                    onClick={() => handleFilterChange('merek')}
                                    className={`px-3 py-1 text-xs rounded-full ${activeFilter === 'merek' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}`}
                                >
                                    Berdasarkan Merek
                                </button>
                            </div>
                        </div>

                        <div className="overflow-x-auto rounded-xl bg-white shadow-md">
                            <table className="min-w-full divide-y divide-gray-200">                                <thead className="bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merek</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                            <span className="ml-1 text-xxs text-gray-400 font-normal italic">(Lihat detail untuk edit)</span>
                                        </th>
                                    </tr>
                                </thead>                                    <tbody className="bg-white divide-y divide-gray-200">
                                    {products.data.length > 0 ? (
                                        products.data.map((product, index) => (
                                            <tr key={product.id} className="hover:bg-gray-50 transition-colors duration-200">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {(products.current_page - 1) * products.per_page + index + 1}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {product.kode_produk}
                                                    {product.global_product_id && (
                                                        <span className="ml-2 px-1 py-0.5 text-xs bg-blue-100 text-blue-700 rounded">
                                                            Terverifikasi
                                                        </span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{product.nama_produk}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{product.kategori || '-'}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{product.merek || '-'}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                                        {product.is_active ? 'Aktif' : 'Tidak Aktif'}
                                                    </span>
                                                </td><td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">                                                    <div className="flex space-x-2">                                                        <Link 
                                                            href={route('products.show', product.id)} 
                                                            className="text-gray-600 hover:text-gray-900 transition-colors duration-200"
                                                            title="Lihat Detail"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </Link>
                                                        <button 
                                                            onClick={() => confirmDelete(product.id)}
                                                            disabled={deleting === product.id}
                                                            className="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                            title="Hapus"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan={7} className="px-6 py-4 text-center text-sm text-gray-500">
                                                Tidak ada data produk.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>

                            <div className="px-6 py-4 border-t border-gray-200">
                                <Pagination links={products.links} />
                            </div>
                        </div>
                    </PageTransition>
                </main>
            </div>
        </div>
    );
}
