<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Log;
use \App\models\GroupMember;
use \App\Models\Profile;
use \App\Models\Group;
use \App\models\User;

class UserController extends Controller
{
    function index()
    {
        $index = 0;
        $id = [];
        $members = auth()->user()->groupAdmins->groups->groupMembers;

        // foreach($members as $member)
        // {
        //     $profile = Profile::findOrFail($member->profile_id);
        //     if($profile->id > 0)
        //     {
        //         $id[$index] = $profile->id;
        //         $index = $index + 1;
        //     }
        // }

        // $profiles = Profile::whereIn('id', $id)->get();

        return response()->json($members->load('profile', 'group'), 200);
    }

    function count()
    {
        $count = auth()->user()->group->groupMembers;

        return response()->json($count, 200);
    }

    function store(Request $request)
    {
        $rules=$request->validate([
            'name' => 'required|min:3|max:200',
            'email' => 'required|email|min:6',
            'phone_number' => 'required|min:11|max:13',
            'gender' => 'min:4|max:20',
            'dob' => 'required|min:4'
        ]);

        //$rules['name'] = ucwords($request->name);
         $group = auth()->user()->profile;
        //$group = Group::where('admin_id', auth()->user()->id)->first();

        $profile = Profile::create($rules);

        $group_member = GroupMember::create([
            'profile_id' => $profile->id,
            'group_id' => $group->id,
        ]);

        //return new UserResource($profile);
        return response()->json($profile, 201);
    }


    function show(Request $request)
    {
        $id = $request->id;

        $user = Profile::findOrFail($id);

        return response()->json($user, 200);
    }

    function update(Request $request)
    {
        $id = $request->id;

        $rules=$request->validate([
            'name' => 'required|min:3|max:200',
            'email' => 'required|email',
            'phone_number' => 'required|min:11|max:13',
            'gender' => 'min:4|max:20',
            'dob' => 'required|min:5',
        ]);

        //$rules['name'] = ucwords($request->name);
        $profile = Profile::findOrFail($id);


            $profile->name = $request->name;
            $profile->email = $request->email;
            $profile->phone_number = $request->phone_number;
            $profile->dob = $request->dob;
            $profile->gender = $request->gender;
            $profile->save();


        return response()->json( $profile, 201);

    }

    function destroy(Request $request)
    {
        $prfl=Profile::findOrFail($request->id);

        $profile = $prfl->update([
            'is_active' => false,
        ]);

        return response()->json("Profile Member Was Successfully Deleted !!!", 200);
    }
}
