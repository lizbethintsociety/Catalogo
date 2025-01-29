<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Establecemos la conexión dinámica a la base de datos
    protected $connection = null;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Asignamos la conexión correcta basada en el DB_SYSTEM
        $this->setConnection('mysql_' . env('DB_SYSTEM'));
    }

    // Relación con productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
