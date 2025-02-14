<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rate;
use App\Models\RateDetail;


class RateDetailController extends Controller 
{
   
    public function show(Request $request): array
    {
        $rate = Rate::withoutTrashed()->findOrFail($request->id ?? $request->rate_id);

        $rate->details = RateDetail::withoutTrashed()
          ->where('rate_id', $rate->id)
          ->orderBy('time_start', 'asc')
          ->get();

        return Response::get_response($rate, 1, 200);
    }
    public function get(Request $request): array
    {
        $data = $this->entity::withTrashed()
            ->join('rates as r', 'rates_details.rate_id', '=', 'r.id')
            ->join('users as u', 'rates_details.user_id', '=', 'u.id')
            ->select('r.*', 'u.name as user')
            ->where(function ($query) use ($request) {
                if (isset($request->rate_id)){
                    $query->where('rate_id', $request->rate_id);
                }
            })
            ->whereNull('r.deleted_at') // Filtro para excluir los rates eliminados
            ->groupBy('r.id')
            ->where('r.branch_id', $this->dataLoad->get_key_for_value('branch'))
            ->orderBy('created_at', 'asc');

        $branches = ($request->deleted) ? $data->onlyTrashed()->get() : $data->withoutTrashed()->get();

        return Response::get_response($branches, 1, 200);
    }


  
}
