<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'name',
        'mobile',
        'email',
        'group_id', // <--- নতুন কলাম
        'total_members',
        'transaction_id',
        'payment_status',
    ];

    /**
     * Get the group associated with the registration.
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Get the members registered under this registration.
     */
    public function members()
    {
        return $this->hasMany(RegisteredMember::class, 'registration_id');
    }
}