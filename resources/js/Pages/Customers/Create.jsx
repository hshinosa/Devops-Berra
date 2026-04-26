import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import Sidebar from '@/Components/Dashboard/Sidebar';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader';
import PageTransition from '@/Components/PageTransition';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        nama_pelanggan: '',
        nomor_telepon: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('customers.store'));
    };

    return (
        <div className="min-h-screen bg-white">
            <Head title="Tambah Pelanggan" />
            
            <DashboardHeader 
                pageTitle="Tambah Pelanggan" 
                breadcrumb={[
                    { label: 'Pelanggan', url: route('customers.index') },
                    { label: 'Tambah' }
                ]}
            />
            
            <div className="flex min-h-screen pt-20"> 
                <Sidebar />

                <main className="flex-1 ml-64 p-6 lg:p-8 overflow-y-auto bg-white">
                    <PageTransition>
                        <div className="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
                            <div className="mb-6 border-b border-gray-200 pb-4">
                                <h3 className="text-xl font-bold text-gray-800">Informasi Pelanggan Baru</h3>
                                <p className="text-sm text-gray-500 mt-1">Lengkapi data pelanggan di bawah ini.</p>
                            </div>

                            <form onSubmit={submit} className="space-y-6">
                                <div>
                                    <label htmlFor="nama_pelanggan" className="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Pelanggan <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="nama_pelanggan"
                                        type="text"
                                        name="nama_pelanggan"
                                        value={data.nama_pelanggan}
                                        onChange={(e) => setData('nama_pelanggan', e.target.value)}
                                        className={`w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 ${errors.nama_pelanggan ? 'border-red-500' : 'border-gray-300'}`}
                                        placeholder="Masukkan nama pelanggan"
                                    />
                                    {errors.nama_pelanggan && <div className="text-red-500 text-sm mt-1">{errors.nama_pelanggan}</div>}
                                </div>

                                <div>
                                    <label htmlFor="nomor_telepon" className="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor Telepon <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="nomor_telepon"
                                        type="text"
                                        name="nomor_telepon"
                                        value={data.nomor_telepon}
                                        onChange={(e) => setData('nomor_telepon', e.target.value)}
                                        className={`w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 ${errors.nomor_telepon ? 'border-red-500' : 'border-gray-300'}`}
                                        placeholder="Contoh: 08123456789"
                                    />
                                    {errors.nomor_telepon && <div className="text-red-500 text-sm mt-1">{errors.nomor_telepon}</div>}
                                </div>

                                <div className="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <Link
                                        href={route('customers.index')}
                                        className="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200"
                                    >
                                        Batal
                                    </Link>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none transition-colors duration-200 disabled:opacity-50"
                                    >
                                        {processing ? 'Menyimpan...' : 'Simpan Pelanggan'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </PageTransition>
                </main>
            </div>
        </div>
    );
}
