<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show($username)
    {
        $user = User::where('username', $username)
            ->first();

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($username)
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
    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'bio' => 'nullable|max:100',
            'private' => 'nullable',
        ]);

        $this->authorize('update', $user);

        // Strip symbols from new username
        $desiredUsername = str_replace(['~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '|', '{', '}', ',', '.', '?', ' '], '', $request->username);

        // If the username is already taken, throw an error
        $user2 = User::where('username', $desiredUsername)
            ->first();

        if (!is_null($user2) && $user->id !== $user2->id) {
            Session::flash('error', 'That username is already taken. Choose a unique username.');
            return redirect()->back();
        }

        /**
         * If the user changes their username, remove "Verified" status from their account
         * to prevent verified users from pretending to be other people.
         * If an admin causes this change, don't change the verified status.
         */
        if ($desiredUsername !== $user->username && $user->verified == 1 && !Auth::user()->hasRole('admin')) {
            $user->verified = 0;
        }

        $user->name = $request->name;
        $user->username = $desiredUsername;
        $user->bio = preg_replace('/\r|\n/', '', $request->bio);
        $user->private = $request->private ?? 0;
        $user->save();
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

    public function followers($username)
    {
        $user = User::where('username', $username)
            ->first();

        $followers = User::whereIn('id', $user->followers())
            ->simplePaginate(20);

        return view('users.followers', compact('user', 'followers'));
    }

    public function following($username)
    {
        $user = User::where('username', $username)
            ->first();

        $follows = User::whereIn('id', $user->following())
            ->simplePaginate(20);

        return view('users.following', compact('user', 'follows'));
    }
}
