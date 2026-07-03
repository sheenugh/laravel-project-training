<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'created_by',
        'assigned_by',
        'title',
        'description',
        'status',
        'due_date',
        'due_time',
        'is_completed',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function assigner()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_by');
    }
}

