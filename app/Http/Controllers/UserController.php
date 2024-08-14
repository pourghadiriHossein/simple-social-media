<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUser;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter = request()->input('filter');
        $query = User::query()
            ->select([
                'id',
                'name',
                'email'
            ])
            ->when($filter, function (Builder $limit, string $filter) {
                $limit->where(DB::raw('lower(name)'), 'like', '%' . strtolower($filter) . '%');
            })
            ->orderBy('id', 'desc');
        $users = $query->paginate(5);
        return $this->paginatedSuccessResponse($users, 'users');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUser $request)
    {
        $data = $request->safe([
            'name',
            'email',
            'password'
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        if ($user) {
            return $this->successResponse([
                'status' => 'success',
                'message' => 'user created',
            ], 200);
        }
        return $this->failResponse([
            'status' => 'fail',
            'message' => 'user create failed',
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUser $request, User $user)
    {
        $data = $request->safe([
            'name',
            'email',
            'password'
        ]);
        if ($data['name']) {
            $user->name = $data['name'];
        }
        if ($data['email'] && $data['email'] != $user->email) {
            if (
                User::where('email', $data['email'])->first() ||
                Admin::where('email', $data['email'])->first()
            ) {
                return $this->failResponse([
                    'status' => 'fail',
                    'message' => 'email already exist',
                ], 401);
            }
            $user->email = $data['email'];
        }
        if (array_key_exists('password', $data)) {
            $user->password = Hash::make($data['password']);
        }
        if ($user->update()) {
            return $this->successResponse([
                'status' => 'success',
                'message' => 'user updated',
            ], 200);
        }
        return $this->failResponse([
            'status' => 'fail',
            'message' => 'user update failed',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return $this->successResponse([
                'status' => 'success',
                'message' => 'user deleted',
            ], 200);
        }
        return $this->failResponse([
            'status' => 'fail',
            'message' => 'user delete failed',
        ], 500);
    }
}
