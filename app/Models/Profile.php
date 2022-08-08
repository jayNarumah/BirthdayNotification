<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\GroupMember;
use \App\Models\UserType;
use \App\Models\Group;
use \App\Models\User;



class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'phone_number',
        'dob',
        'gender'
    ];

    protected $cast = [
        'dob' => 'date'
    ];

    /**
     * Get the user associated with the Profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'profile_id', 'id');
    }

}
