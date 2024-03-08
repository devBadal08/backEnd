<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class NewPasswordController extends Controller
{
        public function reset(Request $request)
    {

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password_confirmation.required' => 'confirmation password is required'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'token' => $request->token
            ])
            ->first();
        if (!$updatePassword) {
            // echo "here";exit;
            // return back()->withInput()->with('error', 'You already created password or the link is expired.');
            return response()->json(['error', 'You already created password or the link is expired.']);
        }
        //   echo "her123e";exit;
        //   print_r($updatePassword->email);die();
        $user = User::where('email', $updatePassword->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $updatePassword->email])->delete();

        //   return back()->withInput()->with('success', 'Your password has been changed!');
        return response()->json(['success', 'Your password has been changed!']);
    }

    
}
