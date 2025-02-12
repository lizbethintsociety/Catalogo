<?php
namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;


    class ServiceController extends Controller
    {
        public function index()
        {
            $services = \DB::table('services')->get();
            
            return view('service.index', compact('services'));
        }
    
        public function show($id)
        {
            $service = \DB::table('services')->where('id', $id)->first();
        
            if (!$service) {
                return redirect()->route('service.index')->with('error', 'Promocion no encontrada');
            }
        
            return view('service.show', compact('service'));
        }
        
        
    }
    