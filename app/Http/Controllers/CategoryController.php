<?php

namespace App\Http\Controllers;

use App\Entities\CommonEntity;
use App\Entities\Response;
use App\Interfaces\EntityTransactionInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller implements EntityTransactionInterface
{
  public function store(Request $request): array
  {
    $validator = $this->rulesValidateEntity($request->all());
    if ($validator->fails()){
      $response = Response::get_response($validator->errors()->messages(), 0, 400);
    }else{
      $response = CommonEntity::transaction_safe_commit($this->makeModel($request));
    }
    return $response;
  }

  public function update(Request $request): array
  {
    $category = Category::findOrFail($request->id);
    $validator = $this->rulesValidateEntity($request->all(), true, $category);
    if ($validator->fails()){
      $response = Response::get_response($validator->errors()->messages(), 0, 400);
    }
    else{
      $response = CommonEntity::transaction_safe_commit($this->makeModel($request, $category));
    }
    return $response;
  }

  public function delete(Request $request): array
  {
    $category = Category::findOrFail($request->id);
    $response = CommonEntity::safe_remove($category);
    return $response;
  }

  public function show(Request $request): array
  {
    $category = Category::findOrFail($request->id);
    return Response::get_response($category, 1, 200);
  }

  public function get(Request $request): array
  {
    $categories = ($request->deleted) ? Category::onlyTrashed()->get() : Category::withoutTrashed()->get();
    return Response::get_response($categories, 1, 200);
  }

  public function get_category_general(Request $request)
  {
      // Obtener las categorías (considerando si están eliminadas o no)
      $category = ($request->deleted) ? Category::onlyTrashed()->where('name', 'General')->first() : Category::withoutTrashed()->where('name', 'General')->first();
      return Response::get_response($category->id, 1, 200);
  }

  public function restore(Request $request): array
  {
    $category = Category::onlyTrashed()->findOrFail($request->id)->restore();
    return Response::get_response($category, 1, 200);
  }

  public function makeModel(Request $request, Model $model = null): Model
  {
    if (!$model){
      $model = new Category();
    }
    $model->name = $request->name;
    $model->description = $request->description;
    $model->is_visible = $request->has('is_visible') ? 1 : 0;
    return $model;
  }

  public function rulesValidateEntity(array $data, $update = false, Model $model = null): \Illuminate\Contracts\Validation\Validator
  {
    if ($update){
      $rules =  [
        "name" =>  ['required','min:4','max:250', Rule::unique('categories')->ignore($model->id)],
      ];
    }
    else{
      $rules =  [
        "name" => 'required|min:4|max:250|unique:categories,name',
      ];
    }

    return Validator::make($data, $rules);
  }
}
