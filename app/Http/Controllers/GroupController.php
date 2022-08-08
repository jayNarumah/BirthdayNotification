<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Resources\GroupResource;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;


class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Group::where('is_active', true)
                         ->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupRequest $request)
    {
        $group = Group::create([
            'group_name' => $request->group_name,
            'is_active' => true,
        ]);

        // if($request->admin_id > 0)
        // {
        //     $admin = User::findOrFail($request->admin_id);
        //     $admin = update([
        //         'group_id' => $group->id,
        //     ]);

        //     return response()->json([
        //         'group' => $group,
        //         'admin' => $admin,
        //     ], 201);
        // }
        return new GroupResource($group, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return new GroupResource($group, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update([
            'group_name' => $request->group_name
        ]);

        return new GroupResource($group, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->is_active = false;
        $group->save();

        return new GroupResource($group, 200);
    }

    function count()
    {
        $groups = Group::where('is_active', true)->count();

        return response()->json($groups, 201);
    }

}
