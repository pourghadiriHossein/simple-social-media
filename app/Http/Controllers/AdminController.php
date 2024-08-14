<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdmin;
use App\Http\Requests\UpdateAdmin;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter = request()->input('filter');
        $query = Admin::query()
            ->select([
                'id',
                'name',
                'email'
            ])
            ->when($filter, function (Builder $limit, string $filter) {
                $limit->where(DB::raw('lower(name)'), 'like', '%' . strtolower($filter) . '%');
            })
            ->orderBy('id', 'desc');
        $admins = $query->paginate(5);
        return $this->paginatedSuccessResponse($admins, 'admins');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAdmin $request)
    {
        $data = $request->safe([
            'name',
            'email',
            'password'
        ]);
        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        if ($admin) {
            return $this->successResponse([
                'status' => 'success',
                'message' => 'admin created',
            ], 200);
        }
        return $this->failResponse([
            'status' => 'fail',
            'message' => 'admin create failed',
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
    public function update(UpdateAdmin $request, Admin $admin)
    {
        $data = $request->safe([
            'name',
            'email',
            'password'
        ]);
        if ($data['name']) {
            $admin->name = $data['name'];
        }
        if ($data['email'] && $data['email'] != $admin->email) {
            if (
                User::where('email',$data['email'])->first() ||
                Admin::where('email',$data['email'])->first()
                ){
                return $this->failResponse([
                    'status' => 'fail',
                    'message' => 'email already exist',
                ],401);
            }
            $admin->email = $data['email'];
        }
        if (array_key_exists('password', $data)) {
            $admin->password = Hash::make($data['password']);
        }
        if ($admin->update()) {
            return $this->successResponse([
                'status' => 'success',
                'message' => 'admin updated',
            ], 200);
        }
        return $this->failResponse([
            'status' => 'fail',
            'message' => 'admin update failed',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        if ($admin->delete()) {
            return $this->successResponse([
                'status' => 'success',
                'message' => 'admin deleted',
            ], 200);
        }
        return $this->failResponse([
            'status' => 'fail',
            'message' => 'admin delete failed',
        ], 500);
    }
}
