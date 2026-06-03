<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    //user create and validate lemareg
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:1',
            'role'     => 'required|in:admin,employee',
        ]);

        User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password), // ← was hardcoded 'password'
            'role'     => $request->role,             
        ]);

        return redirect('/admin/users')->with('success', 'User created successfully.');
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/admin/users')->with('success', 'user deleted susccesfully');
    }
    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'  => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'role'  => 'required|in:admin,employee',
    ]);

    $user->name  = $request->name;
    $user->phone = $request->phone;
    $user->role  = $request->role;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    return redirect('/admin/users')->with('success', 'User updated successfully.');
}

    
}