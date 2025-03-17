<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = User::all();
        return view('admin.account.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:155',
            'email' => 'required|string|email|max:155|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:255',
            'billing_phone' => 'required|string|max:255',
            'billing_email' => 'required|string|email|max:255',
            'role' => 'required|integer|in:0,1,2',
        ]);

        $data = $request->all();
        if ($request->hasFile('profilepicture')) {
            $data['profilepicture'] = $request->file('profilepicture')->store('profilepictures', 'public');
        }
        $data['password'] = bcrypt($request->password);

        User::create($data);

        return response()->json(['message' => 'Admin account created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $account = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:155',
            'email' => 'required|string|email|max:155|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:255',
            'billing_phone' => 'required|string|max:255',
            'billing_email' => 'required|string|email|max:255',
            'role' => 'required|integer|in:0,1,2',
        ]);

        $data = $request->all();
        if ($request->hasFile('profilepicture')) {
            $data['profilepicture'] = $request->file('profilepicture')->store('profilepictures', 'public');
        }
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $account->update($data);

        return response()->json(['message' => 'Admin account updated successfully.']);
    }

    public function data()
    {
        $accounts = User::where('role', 1)->get();
        return response()->json(['data' => $accounts]);
    }

    public function show($id)
    {
        $account = User::findOrFail($id);
        return response()->json(['data' => $account]);
    }

    public function destroy($id)
    {
        $account = User::findOrFail($id);
        $account->delete();

        return response()->json(['message' => 'Admin account deleted successfully.']);
    }
}
