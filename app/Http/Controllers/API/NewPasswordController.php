<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;
use App\Models\User;
// use App\Http\Controllers\Api\DB;
use Illuminate\Support\Facades\DB;
class NewPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function reset(Request $request)
  {
   
      $request->validate([
          'password' => 'required|string|min:6|confirmed',
          'password_confirmation' => 'required'
      ],[
          'password_confirmation.required' => 'confirmation password is required'
      ]);

      $updatePassword = DB::table('password_reset_tokens')
        ->where([ 
          'token' => $request->token
        ])
        ->first();  
      if(!$updatePassword){
        // echo "here";exit;
        // return back()->withInput()->with('error', 'You already created password or the link is expired.');
      return response()->json(['error', 'You already created password or the link is expired.']);

      }
    //   echo "her123e";exit;
    //   print_r($updatePassword->email);die();
      $user = User::where('email', $updatePassword->email)
                  ->update(['password' => Hash::make($request->password)]);

      DB::table('password_reset_tokens')->where(['email'=> $updatePassword->email])->delete();

    //   return back()->withInput()->with('success', 'Your password has been changed!');
      return response()->json(['success', 'Your password has been changed!']);
  }

   /* public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }*/


}