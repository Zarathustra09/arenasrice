<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = User::where('role', 1)->get();
        return view('admin.account.index', compact('accounts'));
    }

    public function data()
    {
        $accounts = User::where('role', 1)->get();
        return response()->json(['data' => $accounts]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:155',
            'email' => 'required|string|email|max:155|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 1,
        ]);

        return response()->json(['message' => 'Admin account created successfully.']);
    }

    public function show($id)
    {
        $account = User::findOrFail($id);
        return response()->json(['data' => $account]);
    }

    public function update(Request $request, $id)
    {
        $account = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:155',
            'email' => 'required|string|email|max:155|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $account->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $account->password,
        ]);

        return response()->json(['message' => 'Admin account updated successfully.']);
    }

    public function destroy($id)
    {
        $account = User::findOrFail($id);
        $account->delete();

        return response()->json(['message' => 'Admin account deleted successfully.']);
    }
}
