<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\RegistrationRequest;
use Amir\Permission\Models\Role;
use App\Models\OneTimeCode;
use App\Http\Requests\VerifyOtpRequest;

class AuthenticationService {

    public static function registerUser(RegistrationRequest $request){
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $userRole = Role::where('name', 'customer')->pluck('id')->first();
        $user = User::create($request->toArray());
        $user->role_id = $userRole;
        $user->save();
        $onetime = OneTimeCode::create([
            'code'=>Str::random(6),
            'type'=>'EMAIL VERIFICATION',
            'expiry'=>1,
            'user_id'=>$user->id,
        ]);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        return $response;
    }

    public static function regCompany(RegistrationRequest $request) {
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $userRole = Role::where('name', 'dispatch rider')->pluck('id')->first();
        $user = User::create($request->toArray());
        $user->role_id = $userRole;
        $user->save();
        $onetime = OneTimeCode::create([
            'code'=>Str::random(6),
            'type'=>'EMAIL VERIFICATION',
            'expiry'=>1,
            'user_id'=>$user->id,
        ]);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        return $response;
    }

    public static function verifyOtp(VerifyOtpRequest $request) {
        $user = User::where('email', $request->email)->first();
        $onetime = OneTimeCode::where('code', $request->code)->first();
        $date_created = $onetime->created_at;
        function checkdate($date_created){
            
        };
        
        if($onetime === null) {
            return "invalid OTP";
        }
        else {
            
            return $checkexpiry;
        }
    }

}
