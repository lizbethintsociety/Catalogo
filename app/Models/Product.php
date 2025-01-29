<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
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

    // Relación con categorías
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // // Relación con el carrito de compras (opcional)
    // public function orderItems()
    // {
    //     return $this->hasMany(OrderItem::class);
    // }
}

