<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( str_contains(request()->path(), 'settings') )
            return view('dashboard.dashboard_settings');
        else
            return view('dashboard.dashboard_home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ( $profile = Auth::user() )
            return $profile;
        else
            return response()->json(['error' => 'User does not exist. Please login and try again'], 403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $user = Auth::user() ? User::find(Auth::user()->id) : null;

        if ( $user && $user->id == $id ) {
            $rules = User::$rules;
            unset($rules['password']);

            if ( strtolower(trim($request->email)) == strtolower($user->email) )
                $rules['email'] = str_replace("|unique:users", '', $rules['email']);
            if ( strtolower(trim($request->username)) == strtolower($user->username) )
                $rules['username'] = str_replace("|unique:users", '', $rules['username']);

            $this->validate($request, $rules);

            $user->first_name = trim($request->first_name);
            $user->last_name = trim($request->last_name);
            $user->email = strtolower(trim($request->email));
            $user->username = trim($request->username);
            $user->save();

            return $user;
        }
        else
            return response()->json(['error' => 'User not logged in. Please refresh the page and try again'], 404);
    }

    /**
     * Update logged in user's password
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_password(Request $request)
    {
        $user = Auth::user();

        if ( $user ) {
            $rules = ['password' => User::$rules['password']];

            if ( ! Hash::check($request->current_password, $user->password) )
                return response()->json(['current_password' => ['Incorrect password.']], 422);

            $this->validate($request, $rules);

            $user->password = bcrypt($request->password_confirmation);
            $user->save();

            return $user;
        }
        else
            return response()->json(['error' => 'User not logged in. Please refresh the page and try again'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
