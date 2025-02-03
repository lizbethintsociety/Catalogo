<?php


namespace App\Traits;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Modesl\Rule;
use App\User;
use Illuminate\Http\Request;
use App\Models\Branch;

trait UserTrait {

  public function user_index()
  {
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => __('locale.ManagamentUser')]
    ];
    $branches = Branch::all();
    $rules = Rule::all();
    return view('user.index', compact('breadcrumbs','rules', 'branches'));
  }

  public function store_user(Request $request)
  {
    $response = (new UserController())->store($request);
    return response()->json($response);
  }

  public function update_user(Request $request)
  {
    $response = (new UserController())->update($request);
    return response()->json($response);
  }

  public function user_delete(Request $request)
  {
    $response = (new UserController())->delete($request);
    return response()->json($response);
  }

  function get_users(Request $request){
    $response = (new UserController())->get($request);
    return response()->json($response);
  }

  function get_user(Request $request){
    $response = (new UserController())->show($request);
    return response()->json($response);
  }

  function user_restore(Request $request){
    $response = (new UserController())->restore($request);
    return response()->json($response);
  }

  function get_users_enabled_for_checkout(Request $request) {
    $users_enabled = (new UserController())->get_users_enabled_for_checkout($request)['data'];
    return response()->json($users_enabled);
  }
}

