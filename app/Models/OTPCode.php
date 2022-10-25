<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTPCode extends Model
{
    use HasFactory;

    protected $table = 'tbl_otp_code';
    protected $primaryKey = 'id';

    protected $fillable = [
        'otp_code',
        'student_id',
        'user_id',
        'expired_at',
        'created_at',
        'updated_at',
    ];
}
