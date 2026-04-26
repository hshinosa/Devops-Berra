<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of customer transaction summaries.
     */
    public function index(Request $request)
    {
        $query = Customer::query()
            ->withCount('transactions')
            ->withSum('transactions', 'total_belanja')
            ->withMax('transactions', 'tanggal_transaksi');

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where('nama_pelanggan', 'like', "%{$searchTerm}%");
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Transactions/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search']),
        ]);
    }
}
