<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


    class CatalogoController extends Controller
    {
        public function index()
        {
            $productos = \DB::table('products')->get();
            
            return view('catalogo.index', compact('productos'));
        }
    
        public function show($id)
        {
            $producto = \DB::table('products')->where('id', $id)->first();
        
            if (!$producto) {
                return redirect()->route('catalogo.index')->with('error', 'Producto no encontrado');
            }
        
    
            return view('catalogo.show', compact('producto'));
        }
        
    }
    
