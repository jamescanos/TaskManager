<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion'];

    // una categoría puede tener muchas tareas
    public function tasks() { 
        return $this->hasMany(Task::class);
    }
}
