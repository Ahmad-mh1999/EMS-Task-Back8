<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function employess(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class , 'employee_project');
    }
}
