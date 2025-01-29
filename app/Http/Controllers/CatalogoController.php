<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index()
    {
        // Obtener todos los productos desde la base de datos del otro proyecto
        $productos = \DB::connection('mysql_proyecto_antiguo')->table('productos')->get();
        
        // Pasar los productos a la vista
        return view('catalogo.index', compact('productos'));
    }

    public function show($id)
    {
        // Obtener un producto específico por su ID
        $producto = \DB::connection('mysql_proyecto_antiguo')->table('productos')->where('id', $id)->first();
        
        // Pasar el producto a la vista
        return view('catalogo.show', compact('producto'));
    }
}
