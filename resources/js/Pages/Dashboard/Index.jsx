import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import Sidebar from '@/Components/Dashboard/Sidebar';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader';
import StatCard from '@/Components/Dashboard/StatCard';
import ActivityTable from '@/Components/Dashboard/ActivityTable';
import PageTransition from '@/Components/PageTransition';

export default function Index({ auth, stats, activities, filters = {}, admins = [] }) {
    const [date, setDate] = useState(filters.date || '');
    const [user, setUser] = useState(filters.user || '');
    const [action, setAction] = useState(filters.action || '');

    const handleFilter = (e) => {
        e.preventDefault();
        router.get('/dashboard', { date, user, action }, { preserveState: true });
    };

    const handleReset = () => {
        setDate('');
        setUser('');
        setAction('');
        router.get('/dashboard', {}, { preserveState: true });
    };

    return (
        <div className="min-h-screen bg-white">
            <Head title="Dashboard Aplikasir" />

            {/* Header */}
            <DashboardHeader
                pageTitle="Dashboard Beranda"
                pageSubtitle={`Selamat datang kembali, ${auth.user.name}!`}
            />

            {/* Layout Utama */}
            <div className="flex min-h-screen pt-20"> {/* pt-20 untuk memberikan ruang yang cukup pada header */}
                <Sidebar />

                {/* Konten Utama Dashboard */}
                <main className="flex-1 ml-64 p-6 lg:p-8 overflow-y-auto bg-white">
                    <PageTransition>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            {stats && stats.map((stat, index) => (
                                <StatCard
                                    key={index}
                                    title={stat.title}
                                    value={stat.value}
                                    icon={stat.icon}
                                />
                            ))}
                        </div>

                        {/* Filter Section */}
                        <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 mt-8 mx-auto">
                            <h3 className="text-lg font-medium text-gray-800 mb-4">Filter Log Aktivitas</h3>
                            <form onSubmit={handleFilter} className="flex flex-wrap gap-4 items-end">
                                <div className="flex-1 min-w-[200px]">
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                    <input
                                        type="date"
                                        className="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        value={date}
                                        onChange={(e) => setDate(e.target.value)}
                                    />
                                </div>
                                <div className="flex-1 min-w-[200px]">
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Admin</label>
                                    <select
                                        className="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        value={user}
                                        onChange={(e) => setUser(e.target.value)}
                                    >
                                        <option value="">Semua Admin</option>
                                        {admins.map(admin => (
                                            <option key={admin.id} value={admin.id}>{admin.name}</option>
                                        ))}
                                    </select>
                                </div>
                                <div className="flex-1 min-w-[200px]">
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                                    <select
                                        className="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        value={action}
                                        onChange={(e) => setAction(e.target.value)}
                                    >
                                        <option value="">Semua Aksi</option>
                                        <option value="created">Dibuat</option>
                                        <option value="updated">Diperbarui</option>
                                        <option value="deleted">Dihapus</option>
                                    </select>
                                </div>
                                <div className="flex gap-2">
                                    <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium">Terapkan</button>
                                    <button type="button" onClick={handleReset} className="px-4 py-2 bg-gray-100 text-gray-700 rounded-md border border-gray-300 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 text-sm font-medium">Reset</button>
                                </div>
                            </form>
                        </div>

                        <div className="-mt-8">
                            <ActivityTable activities={activities || []} />
                        </div>
                    </PageTransition>
                </main>
            </div>
        </div>
    );
}
