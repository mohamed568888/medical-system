<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function users()
    {
        $users = User::all();
        return view('user.users', ['users' => $users]);
    }
    function index()
    {
        $user = User::all();
        return view("user",  ["user" => $user]);
    }
    function show($id)
    {
        $users = User::findOrFail($id);
        return view('user.show', ['user' => $users]);
    }
    public function delete($id)
    {
        $users = User::findOrFail($id);
        $users->delete();
        return redirect()->route('user.users')->with("message", "Deleted successfully");
    }
    function create()
    {
        return view("user.create");
    }
    function store(Request $request)
    {
        $request->validate([
            'id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ], [
            'name.required' => 'Enter Your Name',
            'email.required' => 'Enter Your Email',
            'email.unique' => 'The email format is incorrect.',
            'password.required' => 'Enter Your Password',
            'password.min' => 'The password must contain at least 6 characters.',
            'role.required' => 'Please select the role.'
        ]);


        $data = [
            'id' => $request->id, // لو بتستخدم ID مخصص
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // تشفير كلمة المرور
            'role' => $request->role,
        ];
        User::create($data);

        return redirect()->route("user.users")->with('message', 'User Add successfully.');
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->old_id,
            'role' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $user = User::findOrFail($request->old_id);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // لو المستخدم كتب كلمة مرور جديدة، شفرها وأضفها
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.users')->with('message', 'User updated successfully.');
    }
    public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('user.edit', ['user' => $users]);
    }
}
