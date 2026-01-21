<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['company', 'account', 'category'])->get();
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'transaction_date' => 'required|date',
        ]);

        $transaction = Transaction::create($request->all());

        $account = Account::find($request->account_id);
        if ($request->type === 'income') {
            $account->balance += $request->amount;
        } else {
            $account->balance -= $request->amount;
        }
        $account->save();

        return response()->json($transaction, 201);
    }

    public function show($id)
    {
        $transaction = Transaction::with(['company', 'account', 'category'])->findOrFail($id);
        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());
        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        $account = $transaction->account;
        if ($transaction->type === 'income') {
            $account->balance -= $transaction->amount;
        } else {
            $account->balance += $transaction->amount;
        }
        $account->save();

        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
