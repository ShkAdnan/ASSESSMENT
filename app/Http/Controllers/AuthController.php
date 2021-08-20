<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\Invitation;
use App\Mail\Pin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct(){
    }

    public function sendEmail(Request $request){

        $validator = \Validator::make($request->all(),[  
            'email'            => 'required|email',  
        ]);
    
        if ($validator->fails()) {
            return $validator->errors();
        }

        $details = [
            'link' => "http://".request()->getHttpHost()."/register"
        ];

        Mail::to($request->email)->send(new Invitation($details));

        return $this->response('success', 'Email Sent', '200', 'Successfully Sent');

    }

    public function registerForm(){
        return view('auth.register');
    }

    public function registerUser(Request $request){
    
        $validator = \Validator::make($request->all(),[  
            'user_name'        => 'required|min:2|max:10',
            'email'            => 'required|email',
            'password'         => 'required',
        ]);
    
        if ($validator->fails()) {
            return $validator->errors();
        }

        $exist = User::where('email', $request->email)->count();

        if($exist > 0){
            return $this->response('error', 'Email already exist', '422', 'This email is already registered with us please check your mail');
        }

        $pin = rand(100000,500000);

        $user = User::create([
            'user_name'     => $request->user_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'avatar'        => "",
            'user_role'     => "user",
            'register_at'   => Carbon::now()->toDateTimeString(),
            'pin'           => $pin,
            'active'        => 0
        ]);

        $details = [
            'pin'    => $pin,
            'email'  => $request->email
        ];

        Mail::to($request->email)->send(new Pin($details));

        return $this->response('success', 'Register Successful', '200', 'Please check your email and verify pin');

    }

    public function loginUser(Request $request){

        $validator = \Validator::make($request->all(),[ 
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $count = User::where('email' , $request->email)->where('active', '1')->get();
     
        if($count->count() > 0){
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      
                return $this->response('success', 'Login Successful', '200', 'You are logged in to your account your user id is '.$count[0]->id);

             }
        }else{

        return $this->response('error', 'Account not verified', '422', '');

        
        }
        
    }

    public function verifyForm($email){

        return view('auth.verify', [ 'email' => $email ]);

    }

    public function verifyUser(Request $request){
       
        $validator = \Validator::make($request->all(),[ 
            'email'     => 'required|email',
            'pin'  => 'required'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        User::where('email' , $request->email)
        ->where('pin' , $request->pin)->update([
            'active' => '1'
        ]);
        return $this->response('success', 'Verified', '200', 'Your account is verified please login');

    }

    public function updateUser(Request $request){
        
        $validator = \Validator::make($request->all(),[ 
            'avatar'     => 'required',
            'password'  => 'required'
        ]);

        User::where('id' , $request->id)->update([
            'avatar'        => $req->avatar ? $this->avatar($req->image) : "",
            'password'      => Hash::make($request->password),
        ]);

        return $this->response('success', 'Information Updated', '200', 'You have successfully updated your information');

    }

    public function avatar($image){

        $filenameWithExt = $image->getClientOriginalName();
        //get just filename
        $filename = pathinfo($filenameWithExt);
        //get just extension
        $extension = $image->extension();
        $nameToStore = $filename['filename'] . "_".time().".".$extension;
        //Move to folder
        $path = $image->storeAs('uploads/avatar/' , $nameToStore);
        return $nameToStore;
    }

    public function response($status, $statusMessage, $httpCode, $response){
        return response()->json([
            'status'        => $status,
            'statusMessage' => $statusMessage,
            'httpCode'      => $httpCode,
            'errorCode'     => '',
            'response'      => $response
        ], 200);
    }
}
