<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalProduct extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'global_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori',
        'merek',
        'deskripsi',
        'gambar_produk',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all the transactions that reference this product.
     *
     * Produk berfungsi sebagai repositori produk yang bisa direferensikan dalam transaksi.
     * Kode produk digunakan untuk verifikasi dan untuk katalog produk pada aplikasi pengguna.
     * Data produk ini disinkronkan dengan aplikasi mobile untuk keperluan pencarian dan transaksi.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'product_id');
    }

    /**
     * Verify a product code against global products
     *
     * Metode ini digunakan untuk memverifikasi apakah kode produk yang diberikan
     * cocok dengan kode produk dalam repositori global. Hanya kode produk yang
     * dicocokkan, bukan katalog lengkap.
     *
     * @param  string  $productCode  Kode produk yang akan diverifikasi
     * @return bool|GlobalProduct False jika tidak ditemukan, object GlobalProduct jika ditemukan
     */
    public static function verifyProductCode(string $productCode)
    {
        $globalProduct = self::where('kode_produk', $productCode)
            ->where('is_active', true)
            ->first();

        return $globalProduct ?: false;
    }
}
