import React from 'react';

const IconComponent = ({ iconName }) => {
    let iconChar = '❓';
    let bgColor = 'bg-gray-100';
    let textColor = 'text-gray-600';

    if (iconName === 'UserIcon' || iconName === 'UserPlusIcon' || iconName === 'UsersIcon') {
        iconChar = '👤';
        bgColor = 'bg-indigo-100';
        textColor = 'text-indigo-600';
    }
    if (iconName === 'BoxIcon' || iconName === 'CubeIcon') {
        iconChar = '📦';
        bgColor = 'bg-amber-100';
        textColor = 'text-amber-600';
    }
    if (iconName === 'ShoppingCartIcon' || iconName === 'BanknotesIcon') {
        iconChar = '🛒';
        bgColor = 'bg-emerald-100';
        textColor = 'text-emerald-600';
    }
    if (iconName === 'ActivityIcon' || iconName === 'ClockIcon') {
        iconChar = '⏳';
        bgColor = 'bg-rose-100';
        textColor = 'text-rose-600';
    }
    if (iconName === 'ScanIcon') {
        iconChar = '🔍';
        bgColor = 'bg-sky-100';
        textColor = 'text-sky-600';
    }
    // Tambahkan ikon lain jika perlu

    return (
        <div className={`p-3 rounded-full ${bgColor} ${textColor} text-2xl`}>
            {iconChar}
        </div>
    );
};

export default function StatCard({ title, value, icon }) {
    return (
        <div className="bg-white p-6 rounded-xl shadow-md flex items-center justify-between hover:shadow-lg transition-all">
            <div>
                <p className="text-sm font-medium text-gray-500 mb-1">{title}</p>
                <p className="text-3xl font-bold text-gray-800">{value}</p>
            </div>
            <IconComponent iconName={icon} />
        </div>
    );
}