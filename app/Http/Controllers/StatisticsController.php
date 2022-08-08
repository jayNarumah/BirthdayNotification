<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\GroupMember;

class StatisticsController extends Controller
{
    function count()
    {
        $count=User::where('is_active', true)->count() - 1;

        return response()->json($count, 200);
    }

    function membersCount()
    {
        $index = 0;
        $id = [];
        $admins = auth()->user()->groupAdmins;

        foreach ($admins as $admin)
         {
            $members = GroupMember::where('group_id', $admin->group->id)->get();

            foreach($members as $member)
            {
                $profile = Profile::findOrFail($member->profile_id);

                $id[$index] = $profile->id;
                $index = $index + 1;

            }
        }

        return response()->json(Profile::whereIn('id', $id)->count(), 200);
    }

    function profileCount()
    {
        $count=Profile::where('is_active', true)->count() -1;

        return response()->json($count, 200);
    }
}
