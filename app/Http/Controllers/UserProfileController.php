<?php
/**
 * Picha App is un application where user can upload pictures 
 * for printing and will be deliverd to there location
 *
 * PHP version 7
 *
 * @category Application
 * @package  Picha App
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Http\Controllers;

use Auth;
use Validator;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserProfileController extends \App\Http\Controllers\Controller
{
    /**
     * This function run first automatic before any other function
     */
    public function __construct() {
        // Run middleware for Packages controller
        $this->middleware(['auth']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function details(User $user)
    {
        $data = [];
        $data['model'] = $user->where('user_id', user('user_id'))->first();

        return view('user-profile.details', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $body = [];
        if($request->first_name) $body['first_name'] = $request->first_name;
        if($request->second_name) $body['second_name'] = $request->second_name;
        if($request->last_name) $body['last_name'] = $request->last_name;
        if($request->email) $body['email'] = $request->email;
        if($request->phone) $body['phone'] = $request->phone;
        if($request->status) $body['status'] = $request->status;

        if(count($body)){
            $user->where('user_id', user('user_id'))->update($body);
        }
        
        return redirect()->back()->with(['alert-success'=>'Data saved']);
    }

    public function image_update(Request $request) {
        $request->validate([
            'profile' => 'required|max:65000|mimes:jpg,jpeg,png',
        ]);

        try {
            $uploadedFile = $request->file('profile');
            $original_name = $uploadedFile->getClientOriginalName();
            $filename_explode = explode('.', $original_name);

            $ext = ($original_name)? array_pop($filename_explode): 'jpg'; 
            $filename = time() . "-" . Str::slug(str_replace('.'.$ext, '', $original_name)).'.'.$ext;

            $file_directory = public_path('uploaded');
            $uploadedFile->move($file_directory, $filename);
            $user = User::where('user_id', user('user_id'));

            if ($user->update(['profile_url'=>$filename])) {
                $this->messages['alert-success'] = "Profile upload was successful";
                return redirect()->back()->with($this->messages);
            }
        } catch (\Exception $exception) {
            // Log::error(Helper::logException($exception));

            $this->messages['alert-error'] = "Failed to upload file " . $exception->getMessage();
            return redirect()->back()->with($this->messages);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function change_password(User $user)
    {
        $data = [];
        $token = bcrypt(time().user('email'));
        $user->where('user_id', user('user_id'))->update([
            'remember_token'=>$token
        ]);
        $data['token'] = $token;

        return view('user-profile.change-password', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function change_password_store(Request $request, User $user)
    {
        $data = [];

        $rules = [
            'token' => ['required', 'exists:users,remember_token'],
            'password_old' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // validate request data before continue
        $validator = Validator::make($request->all(), $rules);

        // if request data is valid it continue 
        // if is invalid return back with errors
        if ($validator->fails()) { 
            return redirect()->back()->withErrors($validator);
        } elseif(!Auth::attempt(['email'=>user('email'), 'password'=>$request->password_old])) {
            return redirect()->back()->with(['alert-fail'=>'Wrong old password']);
        }

        $user->where('user_id', user('user_id'))->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->back()->with(['alert-success'=>'Your password changed']);
    }
} 