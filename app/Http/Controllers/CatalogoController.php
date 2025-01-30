<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


    class CatalogoController extends Controller
    {
        public function index()
        {
            // Obtener todos los productos desde la base de datos del otro proyecto (usando la conexión configurada)
            $productos = \DB::table('products')->get();
            
            // Pasar los productos a la vista
            return view('catalogo.index', compact('productos'));
        }
    
        public function show($id)
        {
            // Obtener un producto específico por su UUID
            $producto = \DB::table('products')->where('id', $id)->first();
        
            // Verificar si el producto existe
            if (!$producto) {
                return redirect()->route('catalogo.index')->with('error', 'Producto no encontrado');
            }
        
            // Depuración: Mostrar los datos del producto
        
            // Pasar el producto a la vista
            return view('catalogo.show', compact('producto'));
        }
        
    }
    

