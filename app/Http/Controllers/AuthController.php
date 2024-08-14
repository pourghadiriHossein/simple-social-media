<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login;
use App\Http\Requests\Register;
use App\Http\Requests\UpdateProfile;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Login $request)
    {
        $data = $request->safe([
            'email',
            'password',
        ]);
        $user = User::where('email', $data['email'])->first();
        $admin = Admin::where('email', $data['email'])->first();
        if ($user) {
            $token = auth()->guard('api')->attempt([
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            return $this->successResponse([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 200);
        }
        if ($admin) {
            $token = auth()->guard('admin')->attempt([
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            return $this->successResponse([
                'status' => 'success',
                'admin' => $admin,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 200);
        } else {
            return $this->failResponse([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    public function register(Register $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return $this->successResponse([
            'status' => 'success',
        ], 200);
    }

    public function logout()
    {
        if (auth()->guard('api')->user()) {
            return auth()->guard('api')->logout();
        }
        if (auth()->guard('admin')->user()) {
            return auth()->guard('admin')->logout();
        }
        return $this->failResponse([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function refresh()
    {
        return $this->successResponse([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ], 200);
    }
    public function profile()
    {
        if (auth()->guard('api')->user()) {
            return $this->successResponse([
                'status' => 'success',
                'profile' => auth()->guard('api')->user()
            ], 200);
        }
        if (auth()->guard('admin')->user()) {
            return $this->successResponse([
                'status' => 'success',
                'profile' => auth()->guard('admin')->user()
            ], 200);
        }
    }
    public function updateProfile(UpdateProfile $request)
    {
        $data = $request->safe([
            'name',
            'email',
            'password'
        ]);
        if (auth()->guard('api')->user()) {
            $user = User::where('id', auth()->guard('api')->id())->first();
            if ($data['name'])
                $user->name = $data['name'];

            if ($data['email'] != $user->email) {
                if (
                    User::where('email', $data['email'])->first() ||
                    Admin::where('email', $data['email'])->first()
                ) {
                    return $this->failResponse([
                        'errors' => ['email' => 'This E-Mail Already Exist'],
                    ]);
                } else {
                    $user->email = $data['email'];
                }
            }
            if ($request->input('password'))
                $user->name = Hash::make($data['password']);
            $user->update();
            return $this->successResponse([
                'status' => 'update success',
            ], 200);
        }
        if (auth()->guard('admin')->user()) {
            $admin = Admin::where('id', auth()->guard('admin')->id())->first();
            if ($data['name'])
                $admin->name = $data['name'];

            if ($data['email'] != $admin->email) {
                if (
                    User::where('email', $data['email'])->first() ||
                    Admin::where('email', $data['email'])->first()
                ) {
                    return $this->failResponse([
                        'errors' => ['email' => 'This E-Mail Already Exist'],
                    ]);
                } else {
                    $admin->email = $data['email'];
                }
            }
            if ($request->input('password'))
                $admin->name = Hash::make($data['password']);
            $admin->update();
            return $this->successResponse([
                'status' => 'update success',
            ], 200);
        }
    }
}
