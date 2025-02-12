<?php
namespace App\Http\Controllers;

use App\Models\Suit;
use Illuminate\Http\Request;


    class ReservationController extends Controller
    {
        public function create()
        {
            $suits = \DB::table('suits')->get();
            $suit = \DB::table('suits')->where('id', $id)->first();
            return view('reservation.registerRate',compact('suits','suit'));

        }

      
        
        
    }
    