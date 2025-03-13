<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'email',
        'otp',
        'verified',
        'expires_at'
    ];
    
    protected $casts = [
        'verified' => 'boolean',
        'expires_at' => 'datetime'
    ];
}