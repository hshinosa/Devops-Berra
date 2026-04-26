<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\AdminActivityLog;
use App\Models\Admin;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get statistics
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalTransactions = Transaction::count();
        $totalActivities = AdminActivityLog::count(); 
        
        $statsData = [
            ['title' => 'Total Produk', 'value' => (string)$totalProducts, 'icon' => 'BoxIcon'],
            ['title' => 'Total Pelanggan', 'value' => (string)$totalCustomers, 'icon' => 'UsersIcon'],
            ['title' => 'Total Transaksi', 'value' => (string)$totalTransactions, 'icon' => 'ShoppingCartIcon'],
            ['title' => 'Total Aktivitas Admin', 'value' => (string)$totalActivities, 'icon' => 'ActivityIcon'],
        ];

        // Filtering parameters
        $dateFilter = $request->input('date');
        $userFilter = $request->input('user');
        $actionFilter = $request->input('action');

        // Query activity logs with filters
        $activitiesQuery = AdminActivityLog::with('admin')
            ->orderBy('created_at', 'desc');

        if ($dateFilter) {
            // date filter format is 'YYYY-MM-DD'
            $activitiesQuery->whereDate('created_at', $dateFilter);
        }

        if ($userFilter) {
            // filter by admin name or id. Given dropdown, using admin id
            $activitiesQuery->where('admin_id', $userFilter);
        }

        if ($actionFilter) {
            $activitiesQuery->where('action', $actionFilter);
        }

        // paginate, limit to 50 for the recent list
        $activityLogs = $activitiesQuery->limit(50)->get()->map(function($log) {
            return [
                'id' => $log->id,
                'user' => $log->admin ? $log->admin->name : 'Admin',
                'action' => $log->action,
                'module' => $log->module,
                'description' => $log->description,
                'timestamp' => $log->created_at->format('d/m/Y, H:i:s'),
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'details' => $log->details,
            ];
        });

        // Get admins for filter dropdown
        $admins = Admin::select('id', 'name')->get();

        return Inertia::render('Dashboard/Index', [
            'stats' => $statsData,
            'activities' => $activityLogs,
            'filters' => [
                'date' => $dateFilter,
                'user' => $userFilter,
                'action' => $actionFilter,
            ],
            'admins' => $admins,
        ]);
    }
}
