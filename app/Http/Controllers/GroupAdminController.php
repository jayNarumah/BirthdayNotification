<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\GroupMember;
use App\Models\Profile;

class GroupAdminController extends Controller
{
    function myGroups()
    {
        $admins = auth()->user()->groupAdmins;

        return response()->json($admins);
    }
    function myGroup()
    {
        $admin = auth()->user()->groupAdmins->first();

        return response()->json($admin->group->group_name, 200);
    }

    function admin()
    {
        $admin = auth()->user()->profile;
        return response()->json($admin->name, 200);
    }

    function addMember(Request $request)
    {
        $rules = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
            'group_id' =>'required|exists:groups,id',
        ]);

        $member = GroupMember::where('group_id', $rules['group_id'])
                            ->where('profile_id', $rules['profile_id'])->first();

        if ($member) {
            return response()->json("Sorry, User already exist in the group", 403);
        }else {
            $member = GroupMember::create($rules);
        }
        return response()->json($member, 201);
    }
}
