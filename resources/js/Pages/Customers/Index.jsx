import React, { useState } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import Pagination from '@/Components/Pagination';
import Sidebar from '@/Components/Dashboard/Sidebar';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader';
import PageTransition from '@/Components/PageTransition';
import FlashMessage from '@/Components/FlashMessage';

export default function Index({ customers, filters = {} }) {
    const { flash } = usePage().props || {};
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [deleting, setDeleting] = useState(null);

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(route('customers.index'), {
            search: searchTerm,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    const confirmDelete = (customerId) => {
        setDeleting(customerId);
        if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')) {
            router.delete(route('customers.destroy', customerId));
        }
        setDeleting(null);
    };

    return (
        <div className="min-h-screen bg-white">
            <Head title="Pelanggan" />
            
            <DashboardHeader 
                pageTitle="Pelanggan" 
                breadcrumb={[{ label: 'Pelanggan' }]}
            />
            
            <div className="flex min-h-screen pt-20"> 
                <Sidebar />

                <main className="flex-1 ml-64 p-6 lg:p-8 overflow-y-auto bg-white">
                    <PageTransition>
                        {flash && flash.success && (
                            <FlashMessage message={flash.success} type="success" />
                        )}
                        
                        {flash && flash.error && (
                            <FlashMessage message={flash.error} type="error" />
                        )}

                        <div className="flex justify-between items-center mb-6">
                            <h3 className="text-xl font-semibold text-gray-800">Data Pelanggan</h3>
                            <Link
                                href={route('customers.create')}
                                className="px-4 py-2 text-white text-sm bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none transition-colors duration-300"
                            >
                                Tambah Pelanggan
                            </Link>
                        </div>
                        
                        <div className="mb-6 bg-white p-4 rounded-lg shadow-sm">
                            <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4">
                                <div className="flex-1">
                                    <input
                                        type="text"
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        placeholder="Cari berdasarkan nama atau nomor telepon..."
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
                                                router.get(route('customers.index'));
                                            }}
                                            className="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-300"
                                        >
                                            Reset
                                        </button>
                                    )}
                                </div>
                            </form>
                        </div>

                        <div className="overflow-x-auto rounded-xl bg-white shadow-md">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pelanggan</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {customers.data.length > 0 ? (
                                        customers.data.map((customer, index) => (
                                            <tr key={customer.id} className="hover:bg-gray-50 transition-colors duration-200">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {(customers.current_page - 1) * customers.per_page + index + 1}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{customer.nama_pelanggan}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{customer.nomor_telepon || '-'}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {customer.transactions_count || 0} Transaksi
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <div className="flex space-x-2">
                                                        <Link 
                                                            href={route('customers.edit', customer.id)} 
                                                            className="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                                            title="Edit"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </Link>
                                                        <button 
                                                            onClick={() => confirmDelete(customer.id)}
                                                            disabled={deleting === customer.id}
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
                                            <td colSpan={5} className="px-6 py-4 text-center text-sm text-gray-500">
                                                Tidak ada data pelanggan.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>

                            <div className="px-6 py-4 border-t border-gray-200">
                                <Pagination links={customers.links} />
                            </div>
                        </div>
                    </PageTransition>
                </main>
            </div>
        </div>
    );
}
