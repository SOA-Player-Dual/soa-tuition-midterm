<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuition extends Model
{
    use HasFactory;

    protected $table = 'tbl_tuition';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'student_id',
        'full_name',
        'tuition_fee',
        'status',
        'created_at',
        'updated_at',
    ];
}
