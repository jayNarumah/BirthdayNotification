<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\GroupAdmin;
use App\Models\User;

class AdminController extends Controller
{
    function index()
    {
        $admins=User::where('is_active', true)
                    ->skip(1)->take(User::all()
                    ->count())->get();

        return response()->json($admins->load('groupAdmins', 'profile'), 200);
    }

    function store(Request $request)
    {
        $rules=$request->validate([
            'name' => 'required|min:3|max:200',
            'email' => 'required|email|unique:profiles,email',
            'password' => 'required|min:6',
            'phone_number' => 'required|min:11|max:13',
            'gender' => 'min:4|max:20',
            'dob' => 'required|min:5',
        ]);

        $rules['password'] = bcrypt($request->password);
        //$rules['name'] = ucwords($request->name);

        $profile = Profile::create($rules);

        // if(!$request->password =="")
        // {

            $user = User::create([
                'email' => $profile->email,
                'password' => bcrypt($request->password),
                'user_type_id' => 2,
                'profile_id' => $profile->id,
                // 'group_id' => $request->group_id
            ]);

            try {
                // $group = Group::find($request->group_id);

                $details = [
                    'name' => $profile->name,
                    'email' => $profile->email,
                    'dob' => $profile->dob,
                    'password' => $request->password,
                ];

                // Mail::to($request->email)->queue(new \App\Mail\NotificationMail($details));

             Log::info("Email Sent Successfully!!!");
            } catch (\Throwable $e) {
                throw $e;
            }
            return response()->json([
                'profile' => $profile,
                'user' => $user,
            ], 201);
      //  }
      //  return response()->json($profile, 201);
    }

    function show(Request $request)
    {
        $user = User::findOrFail($request->id);

        return response()->json($user->load('profile', 'groupAdmins'), 200);
    }

    function update(Request $request)
    {

        $rules=$request->validate([
            'name' => 'required|min:3|max:200',
            'email' => 'required|email',
            'phone_number' => 'required|min:11|max:13',
            'gender' => 'required|min:4|max:20',
            'dob' => 'required|min:5',
            'group_id' => 'required',
        ]);

        //$rules['name'] = ucwords($request->name);
        $user = User::findOrFail($request->id);

        $profile = $user->profile;
        $group_admin = GroupAdmin::where('group_id', $request->group_id)->first();

        if ($group_admin AND $group_admin->user_id != $user->id) {
            return response()->json('Group Selected already have an Admin', 403);
        }

        if ($group_admin) {
            $group_admin->group_id = $request->group_id;
            $group_admin->save();
        }
        else {
            $group_admin = GroupAdmin::create([
                'group_id' => $request->group_id,
                'user_id' => $user->id,
            ]);
        }

        $profile->name = $request->name;
        $profile->email = $request->email;
        $profile->phone_number = $request->phone_number;
        $profile->dob = $request->dob;
        $profile->gender = $request->gender;
        $profile->save();

        $user->email = $request->email;
        // $user->group_id = $request->group_id;
        $user->save();

        return response()->json($user->load('groupAdmin'), 200);

    }

    function destroy(Request $request)
    {
        $user=User::findOrFail($request->id);

        $user->is_active = false;
        $user->save();

        return response()->json("Admin Was Successfully Deleted !!!", 201);

    }

}
