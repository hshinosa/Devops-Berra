<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tanggal_transaksi',
        'total_belanja',
        'total_modal',
        'metode_pembayaran',
        'status_pembayaran',
        'id_pelanggan',
        'detail_items',
        'jumlah_bayar',
        'jumlah_kembali',
        'id_transaksi_hutang',
        'nomor_nota',
        'total_diskon',
        'pajak',
        'kasir',
        'catatan',
        'shift',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_belanja' => 'decimal:2',
        'total_modal' => 'decimal:2',
        'jumlah_bayar' => 'decimal:2',
        'jumlah_kembali' => 'decimal:2',
        'detail_items' => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the customer for the transaction.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_pelanggan');
    }
    
    /**
     * Get the parent transaction if this is a payment for credit.
     */
    public function parentTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi_hutang');
    }
    
    /**
     * Scope a query to only include active transactions.
     */
    public function scopeActive(Builder $query): void
    {
        // No need to add conditions since SoftDeletes is used
    }
}
