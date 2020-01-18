<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\UserCreated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UsersController extends BaseApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        $this->middleware('transform.input:' . UserResource::class)
            ->only(['store', 'update']);

        $this->middleware('scope:manage-accounts')->only(['show', 'update']);

         $this->middleware('can:view,user')->only(['show']);
         $this->middleware('can:update,user')->only(['update']);
         $this->middleware('can:delete,user')->only(['destroy']);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();

        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:6'
        ]);

        $data = $request->all();

        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

       return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
       return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if($request->has('admin')) {

            $this->allowedAdminAction();

            $this->validate($request, [
                'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER
            ]);

            if(!$user->isVerified()) {
                return $this->errorResponse('Only verified users can modify the admin field', 409);
            }

            $user->admin= $request->admin;
        }

        if($request->has('name')) {
            $this->validate($request, [
                'name' => 'required'
            ]);
            $user->name = $request->name;
        }

        if($request->has('email')
        && $user->email != $request->email) {

            $this->validate($request, [
                'email' => 'required|email|unique:users,email,' . $user->id
            ]);

            $user->verified = USER::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password')) {
            $this->validate($request, [
                'password' => 'required|min:6|confirmed',
            ]);
            $user->password = bcrypt($request->password);
        }

        if(!$user->isDirty()) {
            return $this->errorResponse('You need to specifiy a different value to update', 422);
        }

        $user->save();

        return $this->showOne($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)
            ->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_token =  null;
        $user->save();

        return $this->showMessage('The account has been verified successfully.');

    }


    public function resend(User $user)
    {
        if($user->isVerified()) {
            return $this->errorResponse('This user is already verified', 409);
        }

        Mail::to($user->email)->send(new UserCreated($user));

        return $this->showMessage('The verification email has been resent.');
    }
}
