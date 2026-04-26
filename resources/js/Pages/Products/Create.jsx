import React, { useState, useEffect } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import Sidebar from '@/Components/Dashboard/Sidebar';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader';
import PageTransition from '@/Components/PageTransition';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import TextArea from '@/Components/TextArea';
import FlashMessage from '@/Components/FlashMessage';

export default function Create({ users = [], catalogProducts = [] }) {
    const { auth, errors } = usePage().props;
    const user = auth?.user;

    const [previewImage, setPreviewImage] = useState(null);
    const [imageError, setImageError] = useState(null);
    const [verifying, setVerifying] = useState(false);
    const [verificationStatus, setVerificationStatus] = useState(null);

    const { data, setData, post, processing } = useForm({
        // Required user selection (store)
        user_id: '',
        // Optional reference to global product
        global_product_id: '',
        // Fields
        kode_produk: '',
        nama_produk: '',
        kategori: '',
        merek: '',
        deskripsi: '',
        jumlah_produk: '',
        harga_modal: '',
        harga_jual: '',
        gambar_produk: null,
    });

    // When a global product is selected, auto-fill fields
    const handleGlobalProductChange = (e) => {
        const productId = e.target.value;
        setData('global_product_id', productId);
        setData('kode_produk', '');
        setData('kategori', '');
        setData('merek', '');
        setData('deskripsi', '');
        setPreviewImage(null);

        if (productId) {
            const selected = catalogProducts.find(p => p.id == productId);
            if (selected) {
                setData(prev => ({
                    ...prev,
                    nama_produk: selected.nama_produk,
                }));
                // Auto-fill others if not manually edited later? Let's set them now
                setData('kode_produk', selected.kode_produk);
                setData('kategori', selected.kategori || '');
                setData('merek', selected.merek || '');
                setData('deskripsi', selected.deskripsi || '');
                if (selected.gambar_produk) {
                    setPreviewImage(`/storage/${selected.gambar_produk}`);
                }
            }
        }
    };

    const verifyProductCode = async () => {
        if (!data.kode_produk || data.kode_produk.trim() === '') {
            setVerificationStatus({ status: 'error', message: 'Kode produk tidak boleh kosong' });
            return;
        }
        setVerifying(true);
        setVerificationStatus(null);

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const response = await fetch(route('products.verify-code'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
                credentials: 'same-origin',
                body: JSON.stringify({ kode_produk: data.kode_produk }),
            });

            const payload = await response.json().catch(() => null);

            if (!response.ok) {
                const message = payload?.message || 'Terjadi kesalahan saat verifikasi kode produk';
                setVerificationStatus({ status: 'error', message });
                return;
            }

            if (payload?.success) {
                setVerificationStatus({ status: 'success', message: payload.message, product: payload.product });
                // Set global product reference, copy fields if needed
                if (payload.product) {
                    setData('global_product_id', payload.product.id);
                    // Pre-fill other fields only if empty (manual inputs may take precedence)
                    if (!data.nama_produk) setData('nama_produk', payload.product.nama_produk);
                    if (!data.kategori) setData('kategori', payload.product.kategori || '');
                    if (!data.merek) setData('merek', payload.product.merek || '');
                    if (!data.deskripsi) setData('deskripsi', payload.product.deskripsi || '');
                    if (payload.product.gambar_produk) {
                        setPreviewImage(`/storage/${payload.product.gambar_produk}`);
                    }
                }
            } else {
                setVerificationStatus({ status: 'error', message: payload?.message || 'Kode produk tidak valid' });
            }
        } catch {
            setVerificationStatus({ status: 'error', message: 'Terjadi kesalahan saat verifikasi kode produk' });
        } finally {
            setVerifying(false);
        }
    };

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            setData('gambar_produk', null);
            setImageError('File harus berupa gambar (JPG, PNG, GIF, atau WEBP)');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            setData('gambar_produk', null);
            setImageError('Ukuran gambar maksimal 2MB');
            return;
        }
        setImageError(null);
        setData('gambar_produk', file);
        const reader = new FileReader();
        reader.onloadend = () => setPreviewImage(reader.result);
        reader.readAsDataURL(file);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('products.store'), { forceFormData: true });
    };

    return (
        <div className="min-h-screen bg-white">
            <Head title="Tambah Produk ke Inventory" />
            
            <DashboardHeader 
                pageTitle="Tambah Produk ke Inventory" 
                breadcrumb={[
                    { label: 'Produk', url: route('products.index') },
                    { label: 'Tambah Produk' }
                ]}
            />
            
            <div className="flex min-h-screen pt-20">
                <Sidebar />
                <main className="flex-1 ml-64 p-6 lg:p-8 overflow-y-auto bg-white">
                    <PageTransition>
                        <div className="bg-white p-6 rounded-lg shadow-md">
                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    {/* Store selection (User) */}
                                    <div>
                                        <InputLabel htmlFor="user_id" value="Pilih Toko / Store" required />
                                        <select
                                            id="user_id"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            value={data.user_id}
                                            onChange={(e) => setData('user_id', e.target.value)}
                                            required
                                        >
                                            <option value="">-- Pilih Toko --</option>
                                            {users.map(u => (
                                                <option key={u.id} value={u.id}>
                                                    {u.storeName} ({u.name})
                                                </option>
                                            ))}
                                        </select>
                                        <InputError message={errors.user_id} className="mt-2" />
                                    </div>

                                    {/* Global Product selection */}
                                    <div>
                                        <InputLabel htmlFor="global_product_id" value="Pilih Produk Global (Opsional)" />
                                        <select
                                            id="global_product_id"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            value={data.global_product_id}
                                            onChange={handleGlobalProductChange}
                                        >
                                            <option value="">-- Pilih Produk Global --</option>
                                            {catalogProducts.map(p => (
                                                <option key={p.id} value={p.id}>
                                                    {p.kode_produk} - {p.nama_produk}
                                                </option>
                                            ))}
                                        </select>
                                        <InputError message={errors.global_product_id} className="mt-2" />
                                    </div>

                                    {/* Product code with verification */}
                                    <div>
                                        <InputLabel htmlFor="kode_produk" value="Kode Produk" />
                                        <div className="flex mt-1">
                                            <TextInput
                                                id="kode_produk"
                                                type="text"
                                                className="block w-full rounded-r-none"
                                                value={data.kode_produk}
                                                onChange={(e) => {
                                                    setData('kode_produk', e.target.value);
                                                    setVerificationStatus(null);
                                                }}
                                            />
                                            <button 
                                                type="button" 
                                                className="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition-colors duration-300 flex items-center"
                                                onClick={verifyProductCode}
                                                disabled={verifying || !data.kode_produk}
                                            >
                                                {verifying ? 'Memverifikasi...' : 'Verifikasi'}
                                            </button>
                                        </div>
                                        {verificationStatus && (
                                            <div className={`mt-2 p-2 text-sm rounded-md ${
                                                verificationStatus.status === 'success' 
                                                    ? 'bg-green-50 text-green-700 border border-green-200' 
                                                    : 'bg-red-50 text-red-700 border border-red-200'
                                            }`}>
                                                {verificationStatus.message}
                                            </div>
                                        )}
                                        <InputError message={errors.kode_produk} className="mt-2" />
                                        <p className="mt-1 text-sm text-gray-500">
                                            Verifikasi kode produk dengan repositori global.
                                        </p>
                                    </div>

                                    {/* Product name */}
                                    <div>
                                        <InputLabel htmlFor="nama_produk" value="Nama Produk" required />
                                        <TextInput
                                            id="nama_produk"
                                            type="text"
                                            className="mt-1 block w-full"
                                            value={data.nama_produk}
                                            onChange={(e) => setData('nama_produk', e.target.value)}
                                        />
                                        <InputError message={errors.nama_produk} className="mt-2" />
                                    </div>

                                    {/* Category */}
                                    <div>
                                        <InputLabel htmlFor="kategori" value="Kategori" />
                                        <TextInput
                                            id="kategori"
                                            type="text"
                                            className="mt-1 block w-full"
                                            value={data.kategori}
                                            onChange={(e) => setData('kategori', e.target.value)}
                                        />
                                        <InputError message={errors.kategori} className="mt-2" />
                                    </div>

                                    {/* Brand */}
                                    <div>
                                        <InputLabel htmlFor="merek" value="Merek" />
                                        <TextInput
                                            id="merek"
                                            type="text"
                                            className="mt-1 block w-full"
                                            value={data.merek}
                                            onChange={(e) => setData('merek', e.target.value)}
                                        />
                                        <InputError message={errors.merek} className="mt-2" />
                                    </div>

                                    {/* Stock quantity */}
                                    <div>
                                        <InputLabel htmlFor="jumlah_produk" value="Jumlah Produk (Stok)" required />
                                        <TextInput
                                            id="jumlah_produk"
                                            type="number"
                                            min="0"
                                            className="mt-1 block w-full"
                                            value={data.jumlah_produk}
                                            onChange={(e) => setData('jumlah_produk', e.target.value)}
                                        />
                                        <InputError message={errors.jumlah_produk} className="mt-2" />
                                    </div>

                                    {/* Cost price */}
                                    <div>
                                        <InputLabel htmlFor="harga_modal" value="Harga Modal" required />
                                        <TextInput
                                            id="harga_modal"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            className="mt-1 block w-full"
                                            value={data.harga_modal}
                                            onChange={(e) => setData('harga_modal', e.target.value)}
                                        />
                                        <InputError message={errors.harga_modal} className="mt-2" />
                                    </div>

                                    {/* Selling price */}
                                    <div>
                                        <InputLabel htmlFor="harga_jual" value="Harga Jual" required />
                                        <TextInput
                                            id="harga_jual"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            className="mt-1 block w-full"
                                            value={data.harga_jual}
                                            onChange={(e) => setData('harga_jual', e.target.value)}
                                        />
                                        <InputError message={errors.harga_jual} className="mt-2" />
                                    </div>

                                    {/* Description */}
                                    <div className="md:col-span-2">
                                        <InputLabel htmlFor="deskripsi" value="Deskripsi" />
                                        <TextArea
                                            id="deskripsi"
                                            className="mt-1 block w-full"
                                            value={data.deskripsi}
                                            onChange={(e) => setData('deskripsi', e.target.value)}
                                        />
                                        <InputError message={errors.deskripsi} className="mt-2" />
                                    </div>

                                    {/* Image upload */}
                                    <div className="md:col-span-2">
                                        <InputLabel htmlFor="gambar_produk" value="Gambar Produk (Opsional)" />
                                        <input
                                            id="gambar_produk"
                                            type="file"
                                            className="mt-1 block w-full text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                            onChange={handleImageChange}
                                            accept="image/*"
                                        />
                                        <InputError message={imageError || errors.gambar_produk} className="mt-2" />
                                        
                                        {(previewImage || (data.gambar_produk && typeof data.gambar_produk === 'string')) && (
                                            <div className="mt-4">
                                                <p className="text-sm text-gray-600 mb-2">Preview:</p>
                                                <img 
                                                    src={previewImage || `/storage/${data.gambar_produk}`} 
                                                    alt="Preview" 
                                                    className="max-w-xs rounded-md border border-gray-200" 
                                                />
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="flex items-center justify-end space-x-3">
                                    <Link
                                        href={route('products.index')}
                                        className="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none transition-colors duration-300"
                                    >
                                        Batal
                                    </Link>
                                    <button
                                        type="submit"
                                        className="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none transition-colors duration-300"
                                        disabled={processing}
                                    >
                                        {processing ? 'Menyimpan...' : 'Tambah ke Inventory'}
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
