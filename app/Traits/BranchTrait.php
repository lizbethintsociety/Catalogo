<?php


namespace App\Traits;

use App\Http\Controllers\BranchController;
use Illuminate\Http\Request;

trait BranchTrait {

  public function branch_index()
  {
    return view('branch.index');
  }

  public function store_branch(Request $request)
  {
    $response = (new BranchController())->store($request);
    return response()->json($response);
  }

  public function update_branch(Request $request)
  {
    $response = (new BranchController())->update($request);
    return response()->json($response);
  }

  public function branch_delete(Request $request)
  {
    $response = (new BranchController())->delete($request);
    return response()->json($response);
  }

  public function branch_restore(Request $request)
  {
    $response = (new BranchController())->restore($request);
    return response()->json($response);
  }

  function get_branches(Request $request){
    $response = (new BranchController())->get($request);
    return response()->json($response);
  }

  function get_branch(Request $request){
    $response = (new BranchController())->show($request);
    return response()->json($response);
  }

  function branch_store_billing(Request $request){
    $response = (new BranchController())->branch_store_billing($request);
    return response()->json($response);
  }

  function disabled_billing(Request $request){
    $response = (new BranchController())->disabled_billing($request);
    return response()->json($response);
  }

}

