<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\User;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $profile = $user->profile;

        
        $validator = Validator::make($request->all(), [
            'avatar' => ['image','mimes:jpeg,png,jpg,gif', 'max:2084'],
        ]);

        if($validator->fails()) {
            return response()->json(['message', 'error'], 500);
        }

        // $user = $profile->user()
        //  ->update([
        //      'name'=> $request->name,
        //      'email'=> $request->email,
        // ]);

        // $profile->speciality=$request->speciality;
        // $profile->university=$request->university;
        // $profile->about=$request->about;

        $user->name = $request->name;

        if($request->hasfile('avatar')){

            $file = $request->file('avatar');

            $extension = $file->getClientOriginalExtension();

            $filename = time() . '.' . $extension;


            $file->move('uploads/profiles/', $filename);

            $profile->image = $filename;
        }

        $profile->update();
        $user->update();

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $profile->image
        ];

        return response()->json($response, 200);

    }
}
