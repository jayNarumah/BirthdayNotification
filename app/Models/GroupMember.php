<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Group;
use \App\Models\Profile;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'group_id',
        'is_active',
    ];

    // protected $with = [
    //     'profiles'
    // ];

    protected $cast = [
        'is_active' => 'boolean',
        'group_id' => 'integer',
        'profile_id' => 'integer',
    ];

    /**
     * Get the profile that owns the GroupMember
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(User::class, 'id', 'profile_id');
    }

    /**
     * Get the group that owns the GroupMember
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
