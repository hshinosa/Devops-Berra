import React from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import Pagination from '@/Components/Pagination';
import { useState } from 'react';
import Sidebar from '@/Components/Dashboard/Sidebar';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader';
import PageTransition from '@/Components/PageTransition';
import FlashMessage from '@/Components/FlashMessage';

export default function Index({ users, filters = {}, auth }) {
    const { flash } = usePage().props || {};
    const [deleting, setDeleting] = useState(null);
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [statusFilter, setStatusFilter] = useState(filters.status || '');

    const confirmDelete = (userId) => {
        setDeleting(userId);
        if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
            router.delete(route('users.destroy', userId));
        }
        setDeleting(null);
    };
    
    const applyFilters = (overrides = {}) => {
        const params = {
            search: searchTerm,
            status: statusFilter,
            ...overrides,
        };
        // Hapus parameter kosong
        Object.keys(params).forEach(key => {
            if (!params[key]) delete params[key];
        });
        router.get(route('users.index'), params, {
            preserveState: true,
            replace: true,
        });
    };

    const handleSearch = (e) => {
        e.preventDefault();
        applyFilters();
    };

    const handleStatusChange = (e) => {
        const value = e.target.value;
        setStatusFilter(value);
        applyFilters({ status: value });
    };

    const handleReset = () => {
        setSearchTerm('');
        setStatusFilter('');
        router.get(route('users.index'));
    };
    
    return (
        <div className="min-h-screen bg-white">
            <Head title="Pengguna" />
            
            {/* Header */}
            <DashboardHeader 
                pageTitle="Pengguna" 
                breadcrumb={[{ label: 'Pengguna' }]}
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
                            <h3 className="text-xl font-semibold text-gray-800">Data Pengguna</h3>
                            <Link
                                href={route('users.create')}
                                className="px-4 py-2 text-white text-sm bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none transition-colors duration-300"
                            >
                                Tambah Pengguna
                            </Link>
                        </div>
                        
                        <div className="mb-6 bg-white p-4 rounded-lg shadow-sm">
                            <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4">
                                <div className="flex-1">
                                    <input
                                        type="text"
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        placeholder="Cari berdasarkan nama, email, telepon, atau toko..."
                                        className="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <select
                                        value={statusFilter}
                                        onChange={handleStatusChange}
                                        className="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                                    >
                                        <option value="">Semua Status</option>
                                        <option value="has_store">Punya Toko</option>
                                        <option value="no_store">Belum Punya Toko</option>
                                    </select>
                                </div>
                                <div className="flex gap-2">
                                    <button 
                                        type="submit"
                                        className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-300"
                                    >
                                        Cari
                                    </button>
                                    {(searchTerm || statusFilter) && (
                                        <button 
                                            type="button"
                                            onClick={handleReset}
                                            className="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-300"
                                        >
                                            Reset
                                        </button>
                                    )}
                                </div>
                            </form>
                        </div>

                        <div className="overflow-x-auto rounded-xl bg-white shadow-md">
                            <table className="min-w-full divide-y divide-gray-200">                                <thead className="bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Toko</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telp</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                            <span className="ml-1 text-xxs text-gray-400 font-normal italic">(Lihat detail untuk edit)</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {users.data.length > 0 ? (
                                        users.data.map((user, index) => (
                                            <tr key={user.id} className="hover:bg-gray-50 transition-colors duration-200">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {(users.current_page - 1) * users.per_page + index + 1}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{user.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{user.storeName || '-'}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{user.phoneNumber || '-'}</td>
                                                <td className="px-6 py-4 text-sm text-gray-500">{user.email}</td>                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <div className="flex space-x-2">
                                                        <Link 
                                                            href={route('users.show', user.id)} 
                                                            className="text-gray-600 hover:text-gray-900 transition-colors duration-200"
                                                            title="Lihat Detail"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </Link>
                                                        <button 
                                                            onClick={() => confirmDelete(user.id)}
                                                            disabled={deleting === user.id}
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
                                            <td colSpan={6} className="px-6 py-4 text-center text-sm text-gray-500">
                                                Tidak ada data pengguna.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>

                            <div className="px-6 py-4 border-t border-gray-200">
                                <Pagination links={users.links} />
                            </div>
                        </div>
                    </PageTransition>
                </main>
            </div>
        </div>
    );
}

