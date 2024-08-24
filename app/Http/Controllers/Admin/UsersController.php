<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function getAll()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.list', ['users' => $users]);
    }

    public function sendVerification($id)
    {
        $user = User::where('id', $id)->first();

        $user->sendEmailVerificationNotification();

        session()->flash('success_msg', 'Verification resent successful');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 400);
        }

        $user->delete();

        session()->flash('success_msg', 'User deleted successfully');

        return redirect()->back();
    }

    public function changePassword()
    {
        if (!request()->has('change_password_user_id')) {
            session()->flash('error_msg', 'Invalid User Account');
            return redirect()->back();
        }

        $user = User::where('id', request()->input('change_password_user_id'))->first();

        if (!request()->has('password') || !request()->has('confirm_password')) {
            session()->flash('error_msg', 'Password & confirm password is required');
            return redirect()->back();
        }

        if (request()->input('password') != request()->input('confirm_password')) {
            session()->flash('error_msg', 'Password & confirm password should match');
            return redirect()->back();
        }

        if (!$user) {
            session()->flash('error_msg', 'User not found');
            return redirect()->back();
        }

        $user->password = Hash::make(request()->input('password'));
        $user->save();

        session()->flash('success_msg', 'Password changed successfully');
        return redirect()->back();
    }

    public function changeStatus($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            session()->flash('error_msg', 'User not found');
            return redirect()->back();
        }

        $user->activated = !$user->activated;
        $user->save();

        session()->flash('success_msg', 'Status changed successfully');
        return redirect()->back();
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            session()->flash('error_msg', 'User not found');
            return redirect()->back();
        }

        return view('admin.users.edit', compact('user'));
    }

    public function save()
    {
        $name = request('name');
        $email = request('email');
        $password = request('password');
        $verified = request('verified', 0);
        $activated = request('activated', 0);
        $role = request('role');
        $avatar = URL::to('/') . "/assets/img/default-avatar.png";

        $v = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ],
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        if (count(User::where('email', $email)->get()) > 0) {
            session()->flash('error_msg', 'Account with this email already exists');
            return redirect()->back()->withInput(request()->all());
        }

        if (request()->hasFile('avatar')) {
            $rules = array('avatar' => 'required|image|mimes:jpeg,jpg,png,gif');
            $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = time() . '.' . request()->avatar->getClientOriginalExtension();

            request()->avatar->move(public_path('images'), $imageName);

            $avatar = '/images/' . $imageName;
        }

        try {
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->avatar = $avatar;
            $user->activated = intval($activated);

            if (intval($verified)) {
                $user->email_verified_at = Carbon::now();
            }

            $user->language = 'en';
            $user->timezone = 'America/New_York';
            $user->role = $role;
            $user->save();

            session()->flash('success_msg', 'User created successfully');
            return redirect('/admin/users/list');
        } catch (\Exception $e) {
            session()->flash('error_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function impersonate($id){
        $user = User::where('id', $id)->first();

        if (!$user) {
            session()->flash('error_msg', 'User not found');
            return redirect()->back();
        }

        auth()->user()->impersonate($user);

        return redirect('/dashboard');
    }

    public function update($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            session()->flash('error_msg', 'User not found');
            return redirect()->back();
        }

        $name = request('name');
        $email = request('email');
        $verified = request('verified');
        $activated = request('activated');
        $role = request('role');
        $avatar = request('avatar_url');

        $v = Validator::make(
            [
                'name' => $name,
                'email' => $email
            ],
            [
                'name' => 'required',
                'email' => 'required|email'
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        if (count(User::where('email', $email)->where('id', '!=', $id)->get()) > 0) {
            session()->flash('error_msg', 'Account with this email already exists');
            return redirect()->back()->withInput(request()->all());
        }

        if (request()->hasFile('avatar')) {
            $rules = array('avatar' => 'required|image|mimes:jpeg,jpg,png,gif');
            $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = time() . '.' . request()->avatar->getClientOriginalExtension();

            request()->avatar->move(public_path('images'), $imageName);

            $avatar = '/images/' . $imageName;
        }

        try {
            $user->name = $name;
            $user->email = $email;
            $user->avatar = $avatar;
            $user->activated = $activated ? 1 : 0;

            if ($verified) {
                $user->email_verified_at = Carbon::now();
            }

            $user->role = $role;
            $user->save();

            session()->flash('success_msg', 'User updated successfully');
            return redirect('/admin/users/list');
        } catch (\Exception $e) {
            session()->flash('success_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }
}
