<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'recruiter',
        'location',
        'salary',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
