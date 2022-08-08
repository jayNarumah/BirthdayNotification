<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificationMail;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use App\Models\GroupMember;
use App\Models\GroupAdmin;
use App\Models\Profile;
use App\Models\Group;
use App\Models\User;

class SuperAdminController extends Controller
{
    function createAdmin(Request $request)
    {
        $rules=$request->validate([
            'group_id' => 'required',
            'user_id' => 'required',
        ]);

        $group = GroupAdmin::where('group_id', $request->group_id)->count();

        if ($group > 0)
        {
            return response()->json("Group Admin was already assign to the Group", 403);
        }

        $id = $request->user_id;
        // $admin = GroupAdmin::where('user_id', $id)->first();

        // if ($admin)
        // {
        //     return response()->json("Selected User was already an Admin  to another group !!!", 403);
        // }

        $admin = GroupAdmin::create($rules);
        $user = User::findOrFail($id);

        $group_member = GroupMember::create([
            'group_id' => $request->group_id,
            'profile_id' => $user->profile_id,
        ]);

        //$rules['group_name'] = ucwords($request->group_name);
        try {
            $profile = $user->profile;
            $_group =  Group::findOrFail($request->group_id);

            $details = [
                'name' => $profile->name,
                'email' => $profile->email,
                'dob' => $profile->dob,
                'group_name' =>$_group->group_name,
            ];

            // Mail::to($profile->email)->queue(new SendMail($details));
            Log::alert($profile->email);
        Log::info("Email Sent Successfully!!!");
    } catch (\Throwable $e) {
        throw $e;
    }

        return response()->json($admin->load('group', 'user'), 201);
    }
}
