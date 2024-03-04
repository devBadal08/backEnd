<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class changeoldpasswordcontroller extends Controller
{
//   public function reset(UpdatePasswordRequest $request){
//     return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
//   }

//   // Verify if token is valid
//   private function updatePasswordRow($request){
//      return DB::table('password_reset_tokens')->where([
//          'email' => $request->email,
//          'token' => $request->resetToken
//      ]);
//   }

//   // Token not found response  
//   private function tokenNotFoundError() {
//       return response()->json([
//         'error' => 'Either your email or token is wrong.'
//       ],Response::HTTP_UNPROCESSABLE_ENTITY);
//   }

//   // Reset password
//   private function resetPassword($request) {
//       // find email
//       $userData = User::whereEmail($request->email)->first();
//       // update password
//       $userData->update([
//         'password'=>bcrypt($request->password)
//       ]);
//       // remove verification data from db
//       $this->updatePasswordRow($request)->delete();

//       // reset password response
//       return response()->json([
//         'data'=>'Password has been updated.'
//       ],Response::HTTP_CREATED);
//   }    
// }
    public function reset(Request $request)
    {
      // print_r($request);exit;
    
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
          echo "here";exit;
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
}