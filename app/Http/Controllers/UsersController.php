<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    // use App\Models\User;
    public function create()
    {
        return view('users.create');
    }
    //
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    //
    public function store(Request $request)
    {
        // Step 1 数据过滤
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        // Step 2 输入数据
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

        return redirect()->route('users.show', [$user]);
    }

}
