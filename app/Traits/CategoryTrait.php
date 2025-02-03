<?php


namespace App\Traits;

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;

trait CategoryTrait {

  public function category_index()
  {

    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => __('locale.ManagamentCategory')]
    ];

    return view('category.index', compact('breadcrumbs'));
  }

  public function store_category(Request $request)
  {
    $response = (new CategoryController())->store($request);
    return response()->json($response);
  }

  public function update_category(Request $request)
  {
    $response = (new CategoryController())->update($request);
    return response()->json($response);
  }

  public function category_delete(Request $request)
  {
    $response = (new CategoryController())->delete($request);
    return response()->json($response);
  }

  function get_categories(Request $request){

    $response = (new CategoryController())->get($request);
    return response()->json($response);
  }

  public function category_restore(Request $request)
  {
    $response = (new CategoryController())->restore($request);
    return response()->json($response);
  }

  function get_category(Request $request){
    $response = (new CategoryController())->show($request);
    $category = $response['data'];
    return response()->json([
      'data' => $category,
      'view' => view('category.show', compact('category'))->render()
    ]);
  }

}

