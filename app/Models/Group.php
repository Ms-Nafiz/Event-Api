<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'group_id');
    }
}