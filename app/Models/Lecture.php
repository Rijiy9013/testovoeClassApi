<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecture extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = "lectures";
    protected $fillable = ['topic', 'description'];

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class)->withPivot('order');
    }
}
