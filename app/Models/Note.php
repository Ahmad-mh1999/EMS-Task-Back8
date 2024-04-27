<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'notes',
        'noteable_id',
        'note_type',
    ];

    /**
     * morph relation function
     *
     * @return void
     */
    public function noteable()
    {
        return $this->morphTo();
    }
}
