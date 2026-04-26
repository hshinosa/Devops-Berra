<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AdminActivityLogger;
use App\Services\LocalStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        // Validasi kolom sort yang diizinkan
        $allowedSorts = ['name', 'email', 'storeName', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';

        $query = User::select('id', 'name', 'email', 'phoneNumber', 'storeName', 'created_at');

        // Apply search if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phoneNumber', 'like', "%{$search}%")
                    ->orWhere('storeName', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status === 'has_store') {
            $query->whereNotNull('storeName')->where('storeName', '!=', '');
        } elseif ($status === 'no_store') {
            $query->where(function ($q) {
                $q->whereNull('storeName')->orWhere('storeName', '');
            });
        }

        $users = $query->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'sort_by' => $sortBy,
                'sort_dir' => $sortDir,
            ],
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created user in storage.
     *
     * Membuat pengguna baru dengan semua informasi yang diperlukan,
     * termasuk menghasilkan kode QRIS (Quick Response Code Indonesian Standard)
     * yang akan digunakan untuk pembayaran di aplikasi mobile.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phoneNumber' => ['required', 'string', 'regex:/^[0-9+\-\s]+$/', 'min:10', 'max:15', 'unique:users,phoneNumber'],
            'storeName' => 'required|string|max:255',
            'storeAddress' => 'required|string|max:500',
            'password' => 'required|string|min:8|confirmed',
            'profileImage' => 'nullable|image|max:2048', // Max 2MB
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phoneNumber.required' => 'Nomor telepon wajib diisi.',
            'phoneNumber.regex' => 'Format nomor telepon tidak valid.',
            'phoneNumber.min' => 'Nomor telepon minimal 10 digit.',
            'phoneNumber.max' => 'Nomor telepon maksimal 15 digit.',
            'phoneNumber.unique' => 'Nomor telepon sudah terdaftar.',
            'storeName.required' => 'Nama toko wajib diisi.',
            'storeAddress.required' => 'Alamat toko wajib diisi.',
            'storeAddress.max' => 'Alamat toko maksimal 500 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'profileImage.image' => 'File harus berupa gambar.',
            'profileImage.max' => 'Ukuran gambar maksimal 2MB.',
        ]);
        $validated['passwordHash'] = bcrypt($request->password);

        // Generate kode QR untuk pengguna baru
        // Kode QR mentah ini disimpan di database dan digunakan untuk
        // membuat representasi visual kode QRIS untuk pembayaran
        $validated['kodeQR'] = 'QR-'.strtoupper(substr(md5(uniqid()), 0, 8));

        // Default profile image path
        $validated['profileImagePath'] = asset('images/default_avatar.png');

        // Handle profileImage upload ke local storage jika ada
        if ($request->hasFile('profileImage')) {
            $profileImage = $request->file('profileImage');
            $filename = 'profile_'.time().'-'.rand(100000000, 999999999).'.'.$profileImage->getClientOriginalExtension();

            try {
                $storage = new LocalStorageService;
                $url = $storage->uploadImage($profileImage->getRealPath(), $filename);

                $validated['profileImagePath'] = $url;
            } catch (\Exception $e) {
                \Log::error('Upload error: '.$e->getMessage());

                return redirect()->back()->withErrors(['profileImage' => 'Gagal mengupload gambar: '.$e->getMessage()]);
            }
        }

        // Hapus key yang tidak dibutuhkan di model
        unset($validated['password']);
        unset($validated['profileImage']);

        $user = User::create($validated);

        // Log admin activity
        AdminActivityLogger::log(
            'users',
            'created',
            "Membuat pengguna baru: {$user->name}",
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'store_name' => $user->storeName,
            ]
        );

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified user.
     *
     * Menampilkan detail pengguna, termasuk visualisasi kode QRIS
     * yang diambil dari nilai kodeQR dalam database.
     *
     * @return Response
     */
    public function show(User $user)
    {
        $user->load([
            'customers' => function ($query) {
                $query->orderBy('nama_pelanggan');
            },
            'transactions' => function ($query) {
                $query->with('customer')  // Load the customer relationship
                    ->orderBy('tanggal_transaksi', 'desc')
                    ->limit(10);
            },
            'products' => function ($query) {
                $query->orderBy('nama_produk');
            },
        ]);

        // Get all unpaid transactions (debts)
        $customerDebts = Transaction::where('user_id', $user->id)
            ->where('status_pembayaran', 'Belum Lunas')
            ->with('customer')
            ->get();

        // Generate QR code data URI for display
        $qrCodeDataUri = null;
        if ($user->kodeQR) {
            $qrCodeDataUri = ImageHelper::generateQrCodeDataUri($user->kodeQR);
        }

        return Inertia::render('Users/Show', [
            'user' => $user,
            'customerDebts' => $customerDebts,
            'qrCodeDataUri' => $qrCodeDataUri,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Generate QR code preview if needed
        $qrCodeDataUri = null;
        if (! empty($user->kodeQR)) {
            $qrCodeDataUri = ImageHelper::generateQrCodeDataUri($user->kodeQR);
        }

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'qrCodeDataUri' => $qrCodeDataUri,
        ]);
    }

    /**
     * Update the specified user in storage.
     *
     * Mengupdate informasi pengguna, termasuk kode QRIS jika diubah.
     * Kode QRIS dipertahankan dalam database dan penting untuk proses
     * pembayaran di aplikasi mobile.
     *
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Log request lebih detail untuk debug
        \Log::info('User update request for user '.$user->id, [
            'request_data' => $request->except(['profileImage']),
            'has_password' => $request->filled('password'),
            'content_type' => $request->header('Content-Type'),
            'request_method' => $request->method(),
            'x_file_upload' => $request->header('X-File-Upload'),
            'x_requested_with' => $request->header('X-Requested-With'),
            'has_file_upload_attempt' => $request->has('_file_upload_attempt'),
            'has_profile_image_flag' => $request->has('has_profile_image'),
            '_FILES' => ! empty($_FILES) ? array_keys($_FILES) : [],
            'file_data' => ! empty($_FILES['profileImage']) ? [
                'name' => $_FILES['profileImage']['name'] ?? 'N/A',
                'type' => $_FILES['profileImage']['type'] ?? 'N/A',
                'size' => $_FILES['profileImage']['size'] ?? 0,
                'error' => $_FILES['profileImage']['error'] ?? 'N/A',
                'tmp_name_exists' => ! empty($_FILES['profileImage']['tmp_name']) ? 'Yes' : 'No',
            ] : 'No profileImage in $_FILES',
        ]);        // Improved file detection and handling
        $hasProfileImage = false;

        // Debug logging
        \Log::info('User update file detection checks', [
            'has_profile_image_flag' => $request->has('has_profile_image') ? 'Yes ('.$request->input('has_profile_image').')' : 'No',
            'x_file_upload_header' => $request->header('X-File-Upload'),
            'x_has_image_header' => $request->header('X-Has-Image'),
            'content_type' => $request->header('Content-Type'),
            'has_file_method' => $request->hasFile('profileImage') ? 'Yes' : 'No',
            'has_files_in_request' => count($request->allFiles()) > 0 ? 'Yes' : 'No',
            'has_files_in_globals' => ! empty($_FILES) ? 'Yes ('.implode(', ', array_keys($_FILES)).')' : 'No',
            'request_keys' => implode(', ', array_keys($request->all())),
        ]);

        // Multi-method approach to detect file uploads properly

        // Method 1: Standard Laravel file detection
        if ($request->hasFile('profileImage')) {
            $file = $request->file('profileImage');
            if ($file && $file->isValid()) {
                $hasProfileImage = true;
                \Log::info('File detected with standard Laravel hasFile method', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);
            }
        }

        // Method 2: Check if the file exists in request but with different key
        if (! $hasProfileImage && count($request->allFiles()) > 0) {
            foreach ($request->allFiles() as $key => $file) {
                if ($file->isValid()) {
                    // Copy to profileImage key for consistency
                    if ($key !== 'profileImage') {
                        $request->files->set('profileImage', $file);
                    }
                    $hasProfileImage = true;
                    \Log::info('File found in request with different key', [
                        'original_key' => $key,
                        'name' => $file->getClientOriginalName(),
                    ]);
                    break;
                }
            }
        }

        // Method 3: Check globals if has_profile_image flag is set but file not detected yet
        if (! $hasProfileImage && $request->has('has_profile_image') && $request->input('has_profile_image') === 'true') {
            if (! empty($_FILES)) {
                foreach ($_FILES as $key => $fileData) {
                    // Check if this is a valid file upload
                    if (! empty($fileData['tmp_name']) && file_exists($fileData['tmp_name']) && $fileData['error'] === UPLOAD_ERR_OK) {
                        try {
                            $uploadedFile = new \Illuminate\Http\UploadedFile(
                                $fileData['tmp_name'],
                                $fileData['name'],
                                $fileData['type'],
                                $fileData['error'],
                                true
                            );

                            $request->files->set('profileImage', $uploadedFile);
                            $hasProfileImage = true;

                            \Log::info('File recovered from $_FILES globals', [
                                'key' => $key,
                                'name' => $fileData['name'],
                                'type' => $fileData['type'],
                                'size' => $fileData['size'],
                            ]);
                            break;
                        } catch (\Exception $e) {
                            \Log::error('Error creating UploadedFile from $_FILES', [
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            }
        }

        // Jika multipart/form-data dan data kosong, gunakan data user yang ada
        if (strpos($request->header('Content-Type'), 'multipart/form-data') !== false) {
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phoneNumber' => $user->phoneNumber,
                'storeName' => $user->storeName,
                'storeAddress' => $user->storeAddress,
                'kodeQR' => $user->kodeQR,
            ];

            // Hanya merge jika field yang diperlukan tidak ada dalam request
            foreach ($userData as $key => $value) {
                if (empty($request->input($key))) {
                    $request->merge([$key => $value]);
                }
            }

            \Log::info('Merged existing user data to request for image-only update', [
                'merged_data' => $userData,
                'actual_data' => $request->only(array_keys($userData)),
            ]);
        }

        // Validasi data
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phoneNumber' => ['required', 'string', 'regex:/^[0-9+\-\s]+$/', 'min:10', 'max:15', 'unique:users,phoneNumber,'.$user->id],
            'storeName' => 'required|string|max:255',
            'storeAddress' => 'required|string|max:500',
            'kodeQR' => 'nullable|string|unique:users,kodeQR,'.$user->id,
        ];

        if ($hasProfileImage) {
            $rules['profileImage'] = 'nullable|file|image|max:5120'; // Max 5MB
        }

        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan pengguna lain.',
            'phoneNumber.required' => 'Nomor telepon wajib diisi.',
            'phoneNumber.regex' => 'Format nomor telepon tidak valid.',
            'phoneNumber.min' => 'Nomor telepon minimal 10 digit.',
            'phoneNumber.max' => 'Nomor telepon maksimal 15 digit.',
            'phoneNumber.unique' => 'Nomor telepon sudah digunakan pengguna lain.',
            'storeName.required' => 'Nama toko wajib diisi.',
            'storeAddress.required' => 'Alamat toko wajib diisi.',
            'storeAddress.max' => 'Alamat toko maksimal 500 karakter.',
            'profileImage.image' => 'File harus berupa gambar.',
            'profileImage.max' => 'Ukuran gambar maksimal 5MB.',
        ];

        \Log::info('Validating rules', ['rules' => $rules]);

        // Validasi request
        $validated = $request->validate($rules, $messages);

        \Log::info('Validation passed', ['validated_fields' => array_keys($validated)]);

        // Proses data yang akan diupdate
        $dataToUpdate = collect($validated)
            ->except(['profileImage'])
            ->toArray();        // Upload dan proses file jika ada
        if ($hasProfileImage) {
            try {
                $profileImage = $request->file('profileImage');

                // Validate file once more
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/jpg'];
                if (! in_array($profileImage->getMimeType(), $allowedTypes)) {
                    return redirect()->back()->withErrors([
                        'profileImage' => 'Format file tidak didukung. Gunakan JPG, PNG, GIF, WEBP, atau BMP.',
                    ])->withInput();
                }

                if ($profileImage->getSize() > 5 * 1024 * 1024) {
                    return redirect()->back()->withErrors([
                        'profileImage' => 'File terlalu besar. Maksimal 5MB.',
                    ])->withInput();
                }

                // Debug file information
                \Log::info('Processing profile image', [
                    'original_name' => $profileImage->getClientOriginalName(),
                    'mime_type' => $profileImage->getMimeType(),
                    'size' => $profileImage->getSize().' bytes ('.round($profileImage->getSize() / 1024, 2).' KB)',
                    'extension' => $profileImage->getClientOriginalExtension(),
                    'is_valid' => $profileImage->isValid() ? 'Yes' : 'No',
                ]);

                // Generate unique filename
                $timestamp = time();
                $random = rand(100000000, 999999999);
                $extension = $profileImage->getClientOriginalExtension() ?: 'jpg';
                $filename = "profile_{$user->id}_{$timestamp}-{$random}.{$extension}";

                // Upload to local storage
                try {
                    $storage = new LocalStorageService;
                    $url = $storage->uploadImage($profileImage->getRealPath(), $filename);

                    $dataToUpdate['profileImagePath'] = $url;

                    \Log::info('Profile image uploaded successfully', [
                        'url' => $url,
                        'filename' => $filename,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Upload error', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    throw new \Exception('Gagal mengupload gambar: '.$e->getMessage());
                }
            } catch (\Exception $e) {
                \Log::error('Failed to process profile image', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return redirect()->back()->withErrors([
                    'profileImage' => 'Gagal mengupload gambar: '.$e->getMessage(),
                ])->withInput();
            }
        }

        // Update user data
        $user->update($dataToUpdate);

        // Add success response
        \Log::info('User updated successfully', [
            'user_id' => $user->id,
            'has_profile_image' => $hasProfileImage ? 'Yes' : 'No',
            'updated_fields' => array_keys($dataToUpdate),
        ]);

        // Redirect with success message
        return redirect()->route('users.show', $user->id)
            ->with('success', 'Pengguna berhasil diperbarui');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $userName = $user->name;
        $userId = $user->id;

        // Hapus foto profil dari local storage jika bukan default
        if (! empty($user->profileImagePath) && ! str_contains($user->profileImagePath, 'default_avatar')) {
            try {
                $storage = new LocalStorageService;
                $oldFilename = basename($user->profileImagePath);

                if ($oldFilename) {
                    $oldPath = 'profile_images/'.$oldFilename;
                    $storage->deleteFile($oldPath);
                }
            } catch (\Exception $e) {
                \Log::error("Delete error on user {$userId}: ".$e->getMessage());
            }
        }

        $user->delete();

        // Log admin activity
        AdminActivityLogger::log(
            'users',
            'deleted',
            "Menghapus pengguna: {$userName}",
            [
                'user_id' => $userId,
                'user_email' => $user->email,
                'store_name' => $user->storeName,
            ]
        );

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    /**
     * Helper function to get upload error message
     */
    private function getUploadErrorMessage($errorCode)
    {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi upload_max_filesize pada php.ini ('.ini_get('upload_max_filesize').')',
            UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi MAX_FILE_SIZE pada form HTML',
            UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian. Coba lagi dengan koneksi yang lebih stabil.',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary server tidak ditemukan. Hubungi administrator.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk server. Hubungi administrator.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP. Hubungi administrator.',
        ];

        return $errorMessages[$errorCode] ?? "Unknown upload error (code: {$errorCode})";
    }

    /**
     * Memvalidasi file gambar dan mengembalikan pesan error jika ada
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string|null Pesan error atau null jika valid
     */
    private function validateImageFile($file)
    {
        if (! $file->isValid()) {
            return 'File tidak valid: '.$this->getUploadErrorMessage($file->getError());
        }

        // Validasi ukuran (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            return 'Ukuran file terlalu besar. Maksimal 2MB.';
        }

        // Validasi mime type
        $mime = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/jpg'];

        if (! in_array($mime, $allowedMimes)) {
            return 'Format file tidak didukung. Gunakan JPG, PNG, GIF, WEBP, atau BMP. Format terdeteksi: '.$mime;
        }

        return null;
    }

    private function hasProfileImage(Request $request)
    {
        // Enhanced detection using multiple methods
        if ($request->hasFile('profileImage')) {
            return true;
        }

        // Check raw request files array
        if (isset($_FILES['profileImage']) && ! empty($_FILES['profileImage']['tmp_name'])) {
            return true;
        }

        // Check in the input bag
        if ($request->input('profileImage') instanceof UploadedFile) {
            return true;
        }

        // For AJAX-based uploads with FormData
        $headers = $request->headers->all();
        $contentType = $request->header('Content-Type', '');

        if (strpos($contentType, 'multipart/form-data') !== false) {
            // Manual check of request content when dealing with multipart
            foreach ($request->all() as $key => $value) {
                if ($key === 'profileImage' && ! empty($value)) {
                    return true;
                }
            }
        }

        return false;
    }

    // Add this to your controller to add better logging
    private function debugUploadInfo(Request $request)
    {
        \Log::info('Request files structure', [
            'files' => $request->allFiles(),
            'request_content' => $request->getContent(),
            'has_file_method' => $request->hasFile('profileImage'),
            'input_keys' => array_keys($request->all()),
            'file_input_content' => $request->input('profileImage'),
            'request_headers' => [
                'content_type' => $request->header('Content-Type'),
                'x_file_upload' => $request->header('X-File-Upload'),
                'x_requested_with' => $request->header('X-Requested-With'),
            ],
            'post_vars' => $_POST,
            'request_method' => $request->method(),
            'form_flags' => [
                '_has_image_upload' => $request->has('_has_image_upload') ? $request->input('_has_image_upload') : 'not set',
                '_is_edit_form' => $request->has('_is_edit_form') ? $request->input('_is_edit_form') : 'not set',
            ],
        ]);
    }

    /**
     * Helper method to update or keep existing value
     *
     * @param  mixed  $new  Nilai baru dari request
     * @param  mixed  $old  Nilai lama dari database
     * @return mixed Nilai baru jika ada, nilai lama jika tidak ada
     */
    private function updateOrKeep($new, $old)
    {
        return ($new !== null && $new !== '') ? $new : $old;
    }
}
