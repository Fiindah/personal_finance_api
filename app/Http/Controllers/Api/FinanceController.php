<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pot;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function overview()
    {
        // 1. Ambil 5 transaksi terbaru (sesuai desain)
        $transactions = Transaction::orderBy('date', 'desc')->take(5)->get();

        // 2. Ambil semua Pots (maksimal 4 untuk ringkasan di UI)
        $pots = Pot::all();

        // 3. Ambil semua Budgets
        $budgets = Budget::all();

        // 4. Hitung Ringkasan Saldo (Logika sederhana)
        $income = Transaction::where('amount', '>', 0)->sum('amount');
        $expenses = Transaction::where('amount', '<', 0)->sum('amount');
        $currentBalance = $income + $expenses; // Karena expense nilainya negatif di data kita

        return response()->json([
            'status' => 'success',
            'data' => [
                'balance' => [
                    'current' => (float)$currentBalance,
                    'income' => (float)$income,
                    'expenses' => (float)abs($expenses),
                ],
                'pots' => $pots,
                'budgets' => $budgets,
                'latest_transactions' => $transactions
            ]
        ]);
    }

    public function transactions(Request $request)
    {
        // Kita mulai dengan Query Builder agar bisa ditambah filter secara dinamis
        $query = Transaction::query();

        // 1. Logika PENCARIAN (Search)
        // Jika user mengetik di kolom search Flutter, filter nama transaksi
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Logika FILTER KATEGORI
        // Jika user memilih kategori (misal: 'Dining Out'), filter datanya
        if ($request->has('category') && $request->category != 'All Transactions') {
            $query->where('category', $request->category);
        }

        // 3. Logika PENGURUTAN (Sorting)
        // Kita ambil parameter 'sort' dari URL, jika tidak ada, defaultnya 'latest' (terbaru)
        $sort = $request->get('sort', 'latest');

        switch ($sort) {
            case 'oldest':
                $query->orderBy('date', 'asc');
                break;
            case 'a-z':
                $query->orderBy('name', 'asc');
                break;
            case 'z-a':
                $query->orderBy('name', 'desc');
                break;
            case 'highest':
                $query->orderBy('amount', 'desc');
                break;
            case 'lowest':
                $query->orderBy('amount', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('date', 'desc');
                break;
        }

        // 4. PAGINATION (Paling penting untuk RAM 4GB)
        // Kita ambil 10 data saja per halaman. 
        // Laravel otomatis membaca parameter ?page= di URL
        $transactions = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }

// ... di dalam class FinanceController ...

// 1. Mengambil semua daftar Pots
    public function getPots() {
        return response()->json(['status' => 'success', 'data' => Pot::all()]);
    }

    // 2. Membuat Pot Baru (Create)
    public function storePot(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target' => 'required|numeric|min:0',
            'theme' => 'required|string',
        ]);

        $pot = Pot::create($validated);
        return response()->json(['status' => 'success', 'data' => $pot], 201);
    }

    // 3. Update Saldo Pot (Add / Withdraw Money)
    public function updateBalance(Request $request, $id) {
        $pot = Pot::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric', // Positif untuk tambah, negatif untuk tarik
        ]);

        $newTotal = $pot->total + $request->amount;

        if ($newTotal < 0) {
            return response()->json(['status' => 'error', 'message' => 'Saldo tidak mencukupi'], 400);
        }

        $pot->update(['total' => $newTotal]);

        return response()->json(['status' => 'success', 'data' => $pot]);
    }

    // 4. Hapus Pot
    public function destroyPot($id) {
        Pot::destroy($id);
        return response()->json(['status' => 'success', 'message' => 'Pot berhasil dihapus']);
    }

    public function recurringBills(Request $request)
    {
        // 1. Ambil semua transaksi yang bersifat recurring
        $query = Transaction::where('recurring', true);

        // 2. Tambahkan fitur Search & Sort (Sama seperti halaman transaksi)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest': $query->orderBy('date', 'asc'); break;
            case 'a-z': $query->orderBy('name', 'asc'); break;
            case 'z-a': $query->orderBy('name', 'desc'); break;
            case 'highest': $query->orderBy('amount', 'desc'); break;
            case 'lowest': $query->orderBy('amount', 'asc'); break;
            default: $query->orderBy('date', 'desc'); break;
        }

        $bills = $query->get();

        // 3. Logika Ringkasan (Summary)
        // Anggap saja hari ini adalah tanggal 20 (sesuai contoh data 2024-08)
        $today = 20; 
        
        $paidBills = $bills->filter(function($bill) use ($today) {
            return date('d', strtotime($bill->date)) <= $today;
        });

        $upcomingBills = $bills->filter(function($bill) use ($today) {
            return date('d', strtotime($bill->date)) > $today;
        });

        $dueSoonBills = $bills->filter(function($bill) use ($today) {
            $day = (int)date('d', strtotime($bill->date));
            return $day > $today && $day <= ($today + 5);
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'bills' => $bills,
                'summary' => [
                    'paid_count' => $paidBills->count(),
                    'paid_total' => abs($paidBills->sum('amount')),
                    'upcoming_count' => $upcomingBills->count(),
                    'upcoming_total' => abs($upcomingBills->sum('amount')),
                    'due_soon_count' => $dueSoonBills->count(),
                    'due_soon_total' => abs($dueSoonBills->sum('amount')),
                ]
            ]
        ]);
    }
}