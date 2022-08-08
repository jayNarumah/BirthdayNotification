<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\Profile;

class GroupAdmin extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'group_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'group_id' => 'integer',
    ];

    protected $with = [
        // 'user',
        'group',
    ];

    /**
     * Get the user that owns the GroupAdmin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()//: BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the group that owns the GroupAdmin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()//: BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
