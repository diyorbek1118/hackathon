<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::with(['company','account','category'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'transaction_date' => 'required|date',
        ]);

        return Transaction::create($data);
    }

    public function show(Transaction $transaction)
    {
        return $transaction->load(['company','account','category']);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'company_id' => 'sometimes|required|exists:companies,id',
            'account_id' => 'sometimes|required|exists:accounts,id',
            'type' => 'sometimes|required|in:income,expense',
            'amount' => 'sometimes|required|numeric',
            'description' => 'sometimes|nullable|string',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'transaction_date' => 'sometimes|required|date',
        ]);

        $transaction->update($data);
        return $transaction;
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->noContent();
    }
}
