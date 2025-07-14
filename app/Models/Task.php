<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'deadline',
        'user_id',
    ];

    // Relationship to assigned user
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}