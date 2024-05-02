<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        // 不需要 auth 身份认证的动作
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store',"index"]
        ]);

        // 只允许未登录用户访问注册页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    // 列出所有用户
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // 返回注册页面
    public function create()
    {
        return view('users.create');
    }
    // 返回用户个人页面
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    // 新建用户会话
    public function store(Request $request)
    {
        // Step 1 数据过滤（注册）
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        // Step 2 存储数据（注册）
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

        return redirect()->route('users.show', [$user]);
    }

    // 编辑用户个人信息
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // 更新用户个人信息
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        // 存在逻辑错误，密码会被随机更改，修改密码前需要鉴权！！！
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user);
    }

}
