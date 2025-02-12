<?php
namespace App\Http\Controllers;

use App\Models\Suit;
use Illuminate\Http\Request;


    class SuitController extends Controller
    {
        public function index()
        {
            $suits = \DB::table('suits')->get();
            
            return view('suit.index', compact('suits'));
        }
    
        public function show($id)
        {
            $suit = \DB::table('suits')->where('id', $id)->first();
        
            if (!$suit) {
                return redirect()->route('suit.index')->with('error', 'Habitación no encontrada');
            }
        
            return view('suit.show', compact('suit'));
        }
        
        
    }
    