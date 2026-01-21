<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return Account::with('company')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string',
            'type' => 'required|in:cash,bank,wallet',
            'balance' => 'nullable|numeric',
        ]);

        return Account::create($data);
    }

    public function show(Account $account)
    {
        return $account->load('company');
    }

    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'company_id' => 'sometimes|required|exists:companies,id',
            'name' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:cash,bank,wallet',
            'balance' => 'sometimes|numeric',
        ]);

        $account->update($data);
        return $account;
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return response()->noContent();
    }
}
