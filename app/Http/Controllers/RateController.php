<?php
namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;


    class RateController extends Controller
    {
        public function index()
        {
            $rates = \DB::table('rates')->get();
            
            return view('rate.index', compact('rates'));
        }
    
        public function show($id)
        {
            $rate = \DB::table('rates')->where('id', $id)->first();
            $services = \DB::table('services')->get();
            $suits = \DB::table('suits')->get();
            $suit = \DB::table('suits')->where('id', $id)->first();
        
            if (!$rate) {
                return redirect()->route('rate.index')->with('error', 'Promocion no encontrada');
            }
        
            return view('rate.show', compact('rate','services','suits','suit'));
        }
        
        
    }
    