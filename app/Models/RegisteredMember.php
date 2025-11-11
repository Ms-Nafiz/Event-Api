<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RegisteredMember extends Model
{
    protected $fillable = ['registration_id', 'member_name', 'gender', 't_shirt_size', 'age'];

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class);
    }
}