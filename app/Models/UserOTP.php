<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOTP extends Model
{
    use HasFactory;

    protected $table = 'user_otps';
    public $timestamps = false;
    protected $fillable = ['user_id','otp', 'attempts', 'otp_expires_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
