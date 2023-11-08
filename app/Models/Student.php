<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "students";
    protected $fillable = ['name', 'email', 'class_room_id'];

    public function class_room(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
