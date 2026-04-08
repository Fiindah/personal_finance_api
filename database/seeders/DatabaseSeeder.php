<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pot;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data dari file json
        $json = File::get(database_path("data.json"));
        $data = json_decode($json);

        // 2. Bersihkan tabel terlebih dahulu (opsional tapi bagus untuk testing)
        Pot::truncate();
        Budget::truncate();
        Transaction::truncate();

        // 3. Masukkan data Pots
        foreach ($data->pots as $p) {
            Pot::create([
                'name' => $p->name,
                'target' => $p->target,
                'total' => $p->total,
                'theme' => $p->theme,
            ]);
        }

        // 4. Masukkan data Budgets
        foreach ($data->budgets as $b) {
            Budget::create([
                'category' => $b->category,
                'maximum' => $b->maximum,
                'theme' => $b->theme,
            ]);
        }

        // 5. Masukkan data Transactions
        foreach ($data->transactions as $t) {
            Transaction::create([
                'avatar' => $t->avatar,
                'name' => $t->name,
                'category' => $t->category,
                'date' => $t->date,
                'amount' => $t->amount,
                'recurring' => $t->recurring,
            ]);
        }
    }
}