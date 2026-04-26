<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Unified ApliKasir Database Schema
     * Combines both web and mobile requirements into a single comprehensive schema
     * Based on mobile_to_mysql_schema.sql from aplikasir-backend
     */
    public function up(): void
    {
        // Drop existing tables in correct order (foreign key dependencies)
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('sync_logs');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('global_products');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('admins');

        // Drop cache and job tables
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');

        // ===========================================
        // 1. ADMINS TABLE (Web Application Users)
        // ===========================================
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_super_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index('email');
            $table->index('is_super_admin');
        });

        // ===========================================
        // 2. USERS TABLE (Mobile App Users)
        // Maps to mobile users table with sync capabilities
        // ===========================================
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('phoneNumber', 50)->unique();
            $table->string('storeName');
            $table->text('storeAddress');
            $table->string('passwordHash');
            $table->text('profileImagePath')->nullable();
            $table->string('kodeQR', 191)->nullable()->unique()
                ->comment('Kode QRIS untuk pembayaran mobile');
            $table->timestamp('last_sync_time')->nullable()
                ->comment('Timestamp sinkronisasi terakhir dari server');
            $table->boolean('is_active')->default(true);
            $table->decimal('credit_limit', 15, 2)->default(0.00)
                ->comment('Batas kredit untuk transaksi hutang');
            $table->json('sync_settings')->nullable()
                ->comment('Pengaturan sinkronisasi mobile');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for sync optimization
            $table->index('email');
            $table->index('phoneNumber');
            $table->index('kodeQR');
            $table->index(['is_active', 'deleted_at']);
            $table->index('last_sync_time');
        });

        // ===========================================
        // 3. GLOBAL PRODUCTS TABLE
        // Reference catalog for mobile app product selection
        // ===========================================
        Schema::create('global_products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk', 100)->unique();
            $table->string('nama_produk');
            $table->string('kategori')->nullable();
            $table->string('merek')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_produk')->nullable();
            $table->decimal('harga_referensi', 15, 2)->nullable()
                ->comment('Harga referensi dari supplier');
            $table->string('satuan', 50)->default('pcs')
                ->comment('Satuan produk (pcs, kg, liter, dll)');
            $table->string('barcode', 100)->nullable()->unique()
                ->comment('Barcode produk untuk scanning');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('kode_produk');
            $table->index('nama_produk');
            $table->index('kategori');
            $table->index('merek');
            $table->index('barcode');
            $table->index(['is_active', 'deleted_at']);
        });

        // ===========================================
        // 4. PRODUCTS TABLE
        // Maps to mobile products table with sync fields
        // Mobile: id (local), server_id (maps to this id), id_pengguna -> user_id
        // ===========================================
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('global_product_id')->nullable()
                ->constrained('global_products')->nullOnDelete();
            $table->string('nama_produk');
            $table->string('kode_produk', 100)->nullable();
            $table->integer('jumlah_produk')->default(0);
            $table->decimal('harga_modal', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->string('gambar_produk')->nullable();
            $table->string('kategori')->nullable();
            $table->string('merek')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('satuan', 50)->default('pcs');
            $table->string('barcode', 100)->nullable();
            $table->integer('stok_minimum')->default(0)
                ->comment('Batas minimum stok untuk notifikasi');
            $table->boolean('is_active')->default(true);
            $table->decimal('diskon_persen', 5, 2)->default(0.00)
                ->comment('Diskon dalam persen');
            $table->decimal('diskon_nominal', 15, 2)->default(0.00)
                ->comment('Diskon dalam nominal rupiah');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for sync optimization
            $table->index('user_id');
            $table->index('global_product_id');
            $table->index(['user_id', 'kode_produk']);
            $table->index(['user_id', 'nama_produk']);
            $table->index(['user_id', 'is_active', 'deleted_at']);
            $table->index(['user_id', 'updated_at', 'deleted_at']); // For sync queries
            $table->index(['id', 'updated_at']); // For conflict detection
            $table->index('barcode');

            // Unique constraints
            $table->unique(['user_id', 'kode_produk'], 'unique_product_code_user');
            $table->unique(['user_id', 'barcode'], 'unique_product_barcode_user');
        });

        // ===========================================
        // 5. CUSTOMERS TABLE
        // Maps to mobile customers table
        // Mobile: id (local), server_id (maps to this id), id_pengguna -> user_id
        // ===========================================
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_pelanggan');
            $table->string('nomor_telepon', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('email', 191)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0.00)
                ->comment('Batas kredit pelanggan');
            $table->decimal('total_hutang', 15, 2)->default(0.00)
                ->comment('Total hutang saat ini');
            $table->string('kategori_pelanggan', 50)->default('Regular')
                ->comment('VIP, Regular, Wholesale, dll');
            $table->text('catatan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index(['user_id', 'nama_pelanggan']);
            $table->index(['user_id', 'nomor_telepon']);
            $table->index(['user_id', 'is_active', 'deleted_at']);
            $table->index(['user_id', 'updated_at', 'deleted_at']); // For sync queries
            $table->index(['id', 'updated_at']); // For conflict detection
            $table->index('kategori_pelanggan');
        });

        // ===========================================
        // 6. TRANSACTIONS TABLE
        // Maps to mobile transactions table with all fields
        // Mobile: id (local), server_id (maps to this id), id_pengguna -> user_id
        // Mobile: id_pelanggan (local customer id) -> needs mapping to server customer id
        // ===========================================
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('tanggal_transaksi');
            $table->decimal('total_belanja', 15, 2);
            $table->decimal('total_modal', 15, 2);
            $table->string('metode_pembayaran', 50);
            $table->string('status_pembayaran', 50);
            $table->foreignId('id_pelanggan')->nullable()
                ->constrained('customers')->nullOnDelete();
            $table->json('detail_items');
            $table->decimal('jumlah_bayar', 15, 2)->nullable();
            $table->decimal('jumlah_kembali', 15, 2)->nullable();
            $table->unsignedBigInteger('id_transaksi_hutang')->nullable()
                ->comment('Referensi ke ID transaksi hutang yang dilunasi');
            $table->string('nomor_nota', 100)->nullable()
                ->comment('Nomor nota/struk transaksi');
            $table->decimal('total_diskon', 15, 2)->default(0.00)
                ->comment('Total diskon yang diberikan');
            $table->decimal('pajak', 15, 2)->default(0.00)
                ->comment('Pajak yang dikenakan');
            $table->string('kasir', 100)->nullable()
                ->comment('Nama kasir yang melayani');
            $table->text('catatan')->nullable()
                ->comment('Catatan tambahan transaksi');
            $table->string('shift', 50)->nullable()
                ->comment('Shift kerja saat transaksi');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index(['user_id', 'tanggal_transaksi']);
            $table->index(['user_id', 'status_pembayaran']);
            $table->index(['user_id', 'metode_pembayaran']);
            $table->index('id_pelanggan');
            $table->index('nomor_nota');
            $table->index(['user_id', 'updated_at', 'deleted_at']); // For sync queries
            $table->index(['id', 'updated_at']); // For conflict detection
            $table->index('tanggal_transaksi');
            $table->index('kasir');
            $table->index('shift');

            // Self-referencing foreign key
            $table->foreign('id_transaksi_hutang')
                ->references('id')->on('transactions')->nullOnDelete();
        });

        // ===========================================
        // 7. SYNC LOGS TABLE
        // Enhanced sync logging for mobile synchronization
        // ===========================================
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('sync_start_time')->useCurrent();
            $table->dateTime('sync_end_time')->nullable();
            $table->string('direction', 10)
                ->comment('"Up" (Client -> Server) or "Down" (Server -> Client)');
            $table->string('status', 50)
                ->comment('Success, Partial Failure, Failed, Pending');
            $table->integer('items_uploaded')->default(0);
            $table->integer('items_downloaded')->default(0);
            $table->integer('conflicts_detected')->default(0);
            $table->integer('conflicts_resolved')->default(0);
            $table->text('error_message')->nullable();
            $table->json('details')->nullable()
                ->comment('Store counts per table, specific errors, performance metrics');
            $table->dateTime('client_last_sync_time')->nullable()
                ->comment('Timestamp terakhir klien sebelum sync ini');
            $table->dateTime('server_sync_time')->nullable()
                ->comment('Timestamp server saat sync ini selesai');
            $table->integer('duration_ms')->nullable()
                ->comment('Durasi sync dalam milliseconds');
            $table->decimal('data_size_kb', 10, 2)->nullable()
                ->comment('Ukuran data yang disinkronkan dalam KB');
            $table->string('app_version', 50)->nullable()
                ->comment('Versi aplikasi mobile saat sync');
            $table->string('device_info', 255)->nullable()
                ->comment('Informasi device mobile');
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'sync_start_time']);
            $table->index(['status', 'sync_start_time']);
            $table->index('direction');
            $table->index('sync_start_time');
        });

        // ===========================================
        // 8. ADMIN ACTIVITY LOGS TABLE
        // For web application admin activity tracking
        // ===========================================
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('module', 50); // e.g., 'users', 'products', etc.
            $table->string('action', 50); // e.g., 'created', 'updated', 'deleted'
            $table->string('description'); // Brief description of the activity
            $table->text('details')->nullable(); // JSON encoded details of changes
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->unsignedBigInteger('target_id')->nullable()
                ->comment('ID of the affected record');
            $table->string('target_type', 100)->nullable()
                ->comment('Type/model of the affected record');
            $table->timestamps();

            // Indexes
            $table->index(['admin_id', 'created_at']);
            $table->index(['module', 'action']);
            $table->index(['target_type', 'target_id']);
            $table->index('created_at');
        });

        // ===========================================
        // 9. AUTHENTICATION & SESSION TABLES
        // ===========================================
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 191)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // ===========================================
        // 10. CACHE & QUEUE TABLES
        // ===========================================
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key', 191)->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key', 191)->primary();
            $table->string('owner', 191);
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue', 191)->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 191)->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // ===========================================
        // 11. CREATE VIEWS FOR SYNC OPTIMIZATION
        // ===========================================
        $this->createSyncViews();

        // Triggers removed for PostgreSQL compatibility
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        // Drop tables in reverse order (foreign key dependencies)
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('sync_logs');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('global_products');
        Schema::dropIfExists('users');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }

    /**
     * Create enhanced views for sync operations and business intelligence
     */
    private function createSyncViews(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        // View for active products with stock information
        DB::statement(<<<'SQL'
CREATE VIEW active_products_with_stock AS
            SELECT 
                p.*,
                u."storeName",
                u.name as owner_name,
                gp.nama_produk as global_product_name,
                gp.kategori as global_kategori,
                gp.merek as global_merek,
                CASE 
                    WHEN p.jumlah_produk <= p.stok_minimum THEN 'Low Stock'
                    WHEN p.jumlah_produk = 0 THEN 'Out of Stock'
                    ELSE 'In Stock'
                END as stock_status
            FROM products p
            LEFT JOIN global_products gp ON p.global_product_id = gp.id
            JOIN users u ON p.user_id = u.id
            WHERE p.deleted_at IS NULL 
            AND p.is_active = TRUE 
            AND u.deleted_at IS NULL
            AND u.is_active = TRUE
SQL);

        // View for low stock products
        DB::statement(<<<'SQL'
CREATE VIEW low_stock_products AS
            SELECT 
                p.*,
                u."storeName",
                u.name as owner_name,
                u."phoneNumber" as owner_phone
            FROM products p
            JOIN users u ON p.user_id = u.id
            WHERE p.deleted_at IS NULL 
            AND p.is_active = TRUE 
            AND u.deleted_at IS NULL
            AND u.is_active = TRUE
            AND p.jumlah_produk <= p.stok_minimum
            ORDER BY p.jumlah_produk ASC
SQL);

        // View for unpaid transactions (credit tracking)
        DB::statement(<<<'SQL'
CREATE VIEW unpaid_transactions AS
            SELECT 
                t.*,
                c.nama_pelanggan,
                c.nomor_telepon as customer_phone,
                c.credit_limit,
                c.total_hutang as customer_total_debt,
                u."storeName",
                u.name as store_owner,
                EXTRACT(DAY FROM (CURRENT_DATE - t.tanggal_transaksi)) as days_overdue,
                CASE 
                    WHEN EXTRACT(DAY FROM (CURRENT_DATE - t.tanggal_transaksi)) > 30 THEN 'Overdue'
                    WHEN EXTRACT(DAY FROM (CURRENT_DATE - t.tanggal_transaksi)) > 7 THEN 'Due Soon'
                    ELSE 'Current'
                END as payment_status
            FROM transactions t
            JOIN customers c ON t.id_pelanggan = c.id
            JOIN users u ON t.user_id = u.id
            WHERE t.deleted_at IS NULL 
            AND t.status_pembayaran = 'Belum Lunas'
            AND (t.metode_pembayaran LIKE '%Kredit%' OR t.metode_pembayaran LIKE '%Hutang%')
            ORDER BY days_overdue DESC
SQL);

        // View for recent sync activity with performance metrics
        DB::statement(<<<'SQL'
CREATE VIEW recent_sync_activity AS
            SELECT 
                sl.*,
                u.name as user_name,
                u."storeName",
                u."phoneNumber",
                CASE 
                    WHEN sl.status = 'Success' THEN 'success'
                    WHEN sl.status = 'Failed' THEN 'error'
                    WHEN sl.status = 'Partial Failure' THEN 'warning'
                    ELSE 'info'
                END as status_type,
                CASE 
                    WHEN sl.duration_ms IS NOT NULL AND sl.duration_ms > 0 THEN
                        ROUND((sl.items_uploaded + sl.items_downloaded) / (sl.duration_ms / 1000), 2)
                    ELSE 0
                END as items_per_second
            FROM sync_logs sl
            JOIN users u ON sl.user_id = u.id
            WHERE sl.sync_start_time >= (CURRENT_DATE - INTERVAL '30 days')
            ORDER BY sl.sync_start_time DESC
SQL);

        // View for sync statistics per user
        DB::statement(<<<'SQL'
CREATE VIEW user_sync_statistics AS
            SELECT 
                u.id,
                u.name,
                u."storeName",
                u."phoneNumber",
                u.last_sync_time,
                COUNT(sl.id) as total_syncs,
                SUM(CASE WHEN sl.status = 'Success' THEN 1 ELSE 0 END) as successful_syncs,
                SUM(CASE WHEN sl.status = 'Failed' THEN 1 ELSE 0 END) as failed_syncs,
                ROUND(
                    (SUM(CASE WHEN sl.status = 'Success' THEN 1 ELSE 0 END) * 100.0) / 
                    NULLIF(COUNT(sl.id), 0), 2
                ) as success_rate,
                AVG(sl.duration_ms) as avg_sync_duration_ms,
                SUM(sl.items_uploaded) as total_items_uploaded,
                SUM(sl.items_downloaded) as total_items_downloaded,
                MAX(sl.sync_start_time) as last_sync_attempt
            FROM users u
            LEFT JOIN sync_logs sl ON u.id = sl.user_id
            WHERE u.deleted_at IS NULL
            GROUP BY u.id, u.name, u."storeName", u."phoneNumber", u.last_sync_time
SQL);

        // View for top selling products (simplified)
        DB::statement(<<<'SQL'
CREATE VIEW top_selling_products AS
            SELECT 
                p.id,
                p.nama_produk,
                p.kode_produk,
                p.harga_jual,
                p.jumlah_produk as current_stock,
                u."storeName",
                COUNT(t.id) as transaction_count
            FROM products p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN transactions t ON t.user_id = p.user_id AND t.deleted_at IS NULL
            WHERE p.deleted_at IS NULL 
            GROUP BY p.id, p.nama_produk, p.kode_produk, p.harga_jual, p.jumlah_produk, u."storeName"
            ORDER BY transaction_count DESC
SQL);

        // View for customer transaction summary
        DB::statement(<<<'SQL'
CREATE VIEW customer_transaction_summary AS
            SELECT 
                c.id,
                c.nama_pelanggan,
                c.nomor_telepon,
                c.credit_limit,
                c.total_hutang,
                u."storeName",
                COUNT(t.id) as total_transactions,
                SUM(t.total_belanja) as total_spent,
                AVG(t.total_belanja) as avg_transaction_value,
                MAX(t.tanggal_transaksi) as last_transaction_date,
                SUM(CASE WHEN t.status_pembayaran = 'Belum Lunas' THEN t.total_belanja ELSE 0 END) as outstanding_debt,
                COUNT(CASE WHEN t.status_pembayaran = 'Belum Lunas' THEN 1 END) as unpaid_transactions
            FROM customers c
            JOIN users u ON c.user_id = u.id
            LEFT JOIN transactions t ON c.id = t.id_pelanggan AND t.deleted_at IS NULL
            WHERE c.deleted_at IS NULL
            GROUP BY c.id, c.nama_pelanggan, c.nomor_telepon, c.credit_limit, c.total_hutang, u."storeName"
SQL);
    }

    // Triggers removed
};
