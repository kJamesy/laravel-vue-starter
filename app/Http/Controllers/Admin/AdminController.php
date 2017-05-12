<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Http\JsonResponse|null
     */
    public function show($id)
    {
        if ( $profile = Auth::user() )
            return $profile;
        else
            return response()->json(['error' => 'User does not exist. Please login and try again'], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::guard('web')->user() ? User::find(Auth::guard('web')->user()->id) : null;

        if ( $user && $user->id == $id ) {

            if ( request()->has('password_update') ) {
                $rules = ['password' => User::$rules['password']];

                if ( ! Hash::check($request->current_password, $user->password) )
                    return response()->json(['current_password' => ['Incorrect password.']], 422);

                $this->validate($request, $rules);

                $user->password = bcrypt($request->password_confirmation);
                $user->save();

                return $user;
            }

            $rules = User::$rules;
            unset($rules['password']);

            if ( strtolower(trim($request->email)) == strtolower($user->email) )
                $rules['email'] = str_replace("|unique:users|unique:members", '', $rules['email']);
            if ( strtolower(trim($request->username)) == strtolower($user->username) )
                $rules['username'] = str_replace("|unique:users|unique:members", '', $rules['username']);

            $this->validate($request, $rules);

            $user->first_name = trim($request->first_name);
            $user->last_name = trim($request->last_name);
            $user->email = strtolower(trim($request->email));
            $user->username = trim($request->username);
            $user->save();

            return $user;
        }
        else
            return response()->json(['error' => 'User not found. Please refresh the page and try again'], 404);
    }


}
