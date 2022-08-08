<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\GroupMember;
use \App\Models\User;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'is_active'
    ];

    protected $cast = ['is_active' => 'boolean'];

    /**
     * Get all of the groupMember for the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class, 'group_id', 'id');
    }

    /**
     * Get the groupAdmin associated with the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupAdmins()//: HasOne
    {
        return $this->hasMany(GroupAdmin::class, 'group_id');
    }

}
