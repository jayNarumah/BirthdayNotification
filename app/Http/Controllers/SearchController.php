<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\profile;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        //$user = Profile::all();

        $profiles = Profile::where('name', 'like', '%' . $request->search . '%')
                       ->orWhere('phone_number', 'like', '%' . $request->search . '%')
                       ->orWhere('gender', 'like', '%' . $request->search . '%')
                       ->orWhere('email', 'like', '%' . $request->search . '%')
                       ->orWhere('dob', 'like', '%' . $request->search . '%')->get();

        return response()->json($profiles, 200);

    }
}
