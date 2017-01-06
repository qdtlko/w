<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Form\UserForm;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(25);

        return view('backend.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        return view('backend.user.create');

        $roles = Role::all();
        return view('backend.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserForm $request)
    {
        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ];

//        try {
//            if (User::create($data)) {
//                return redirect()->back()->withSuccess('新增用户成功');
//            }
//        } catch (\Exception $e) {
//            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
//        }


        try {
            $roles = Role::whereIn('id', $request->get('role_id'))->get();
            if (empty($roles->toArray())) {
                return redirect()->back()->withErrors("用户角色不存在，请刷新页面并选择其他用户角色")->withInput();
            }

            $user = User::create($data);
            if ($user) {
                foreach ($roles as $role) {
                    $user->attachRole($role);
                }
                return redirect()->route('user.index')->withSuccess('新增用户成功');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
//        return view('backend.user.edit', compact('user'));
        $roles = Role::all();
        $userRoles = $user->roles->toArray();
        $displayNames = array_map(function ($value) {
            return $value['display_name'];
        }, $userRoles);
        return view('backend.user.edit', compact('user', 'roles', 'displayNames'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserForm $request, $id)
    {
//        $data = [
//            'name' => $request['name'],
//            'email' => $request['email'],
//            'password' => bcrypt($request['password']),
//        ];
//
//        try {
//            if (User::where('id', $id)->update($data)) {
//                return redirect()->back()->withSuccess('编辑用户成功');
//            }
//        } catch (\Exception $e) {
//            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
//        }


        $user = User::find($id);
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);

        try {
            $roles = Role::whereIn('id', $request->get('role_id'))->get();
            if (empty($roles->toArray())) {
                return redirect()->back()->withErrors("用户角色不存在,请刷新页面并选择其他用户角色")->withInput();
            } else {
                if ($user->save()) {
                    foreach ($roles as $role) {
                        $user->attachRole($role);
                    }
                    return redirect()->route('user.index')->withSuccess('编辑用户成功');
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (User::destroy($id)) {
                return redirect()->back()->withSuccess('删除用户成功');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()));
        }

    }
}
