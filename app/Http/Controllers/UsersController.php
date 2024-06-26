<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

    public function __construct()
    {
        // 不需要 auth 身份认证的动作
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);

        // 只允许未登录用户访问注册页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);

        // 注册限流 一个小时内只能提交 10 次请求；
        $this->middleware('throttle:10,60', [
            'only' => ['store']
        ]);
    }

    // 列出所有用户
    public function index()
    {
        $users = User::paginate(6);
        return view('users.index', compact('users'));
    }

    // 返回注册页面
    public function create()
    {
        return view('users.create');
    }
    // 返回用户个人页面
    // public function show(User $user)
    // {
    //     return view('users.show', compact('user'));
    // }

    public function show(User $user)
    {
        $statuses = $user->statuses()
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        return view('users.show', compact('user', 'statuses'));
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

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');

        // 用户注册成功后即可登录不需要验证邮件的代码
        // Auth::login($user);
        // session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        // return redirect()->route('users.show', [$user]);
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

    // 以管理员权限删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('sussess','用户删除成功！');
        return back();

    }

    // 发送验证邮件
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        // $data = compact('user');
        // $from = 'summer@example.com';
        // $name = 'Summer';
        // $to = $user->email;
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        // Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
        //     $message->from($from, $name)->to($to)->subject($subject);
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    // 1
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }



}
