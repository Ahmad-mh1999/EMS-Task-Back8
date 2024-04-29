<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Note;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];
    /**
     * Undocumented function
     *
     * @return void
     */
    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class);
    }


/**
 *  function that spacific morph relationship
 *
 * @return MorphMany
 */
    public function notes():MorphMany
    {
        return $this->MorphMany(Note::class, 'noteable');
    }
}
