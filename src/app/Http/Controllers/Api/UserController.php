<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        return response()->json(User::all());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6',
            'role'=>'required|in:owner,bank,leasing,investor,admin',
            'company_id'=>'nullable|exists:companies,id'
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function show($id) {
        return response()->json(User::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name'=>'sometimes|string|max:255',
            'email'=>'sometimes|email|unique:users,email,'.$id,
            'password'=>'sometimes|string|min:6',
            'role'=>'sometimes|in:owner,bank,leasing,investor,admin',
            'company_id'=>'nullable|exists:companies,id'
        ]);
        if(isset($data['password'])) $data['password'] = bcrypt($data['password']);
        $user->update($data);
        return response()->json($user);
    }

    public function destroy($id) {
        User::findOrFail($id)->delete();
        return response()->json(['message'=>'User deleted']);
    }
}
