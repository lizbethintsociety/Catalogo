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
            $services = \DB::table('services')->get();
            $rate_details = \DB::table('rates_details')->get();
        
            if (!$suit) {
                return redirect()->route('suit.index')->with('error', 'Habitación no encontrada');
            }
        
            // Obtener el primer precio del primer rate_detail
            $first_price = $rate_details->first()->price;
        
            return view('suit.show', compact('suit', 'services', 'rate_details', 'first_price'));
        }
        
        
        
    }
    