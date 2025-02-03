<?php

namespace App\Http\Controllers;

use App\Entities\CommonEntity;
use App\Entities\Response;
use App\Interfaces\EntityTransactionInterface;
use App\Models\Batch;
use App\Models\Branch;
use App\Models\BranchDetail;
use App\Models\FileCabinet;
use App\Models\Photo;
use App\Models\Product;
use App\Models\Color;
use App\Models\ColorProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Calculation\Web;

class ProductController extends Controller  implements EntityTransactionInterface
{
  public function store(Request $request): array
  {
    $validator = $this->rulesValidateEntity($request->all());
    if ($validator->fails()){
      $response = Response::get_response($validator->errors()->messages(), 0, 400);
    }else{
      $request['user_id'] = Auth::user()->id;
      $response = CommonEntity::custom_safe_transaction($request, function ($request) {
        $product = Product::create($request->all());
        $exist_predetermined = false;
        if(isset($request->prices)){
          foreach ($request->prices as $item){
            if (CommonController::existAndNotIsEmpty($item['description_price']) && CommonController::existAndNotIsEmpty($item['price'])){
                $request['description'] = $item['description_price'];
                $request['price'] = $item['price'];
                $request['factor'] = $item['factor'] ?? 1;
                $request['product_id'] = $product->id;
                $request['is_predetermined'] = 0;
                ((new PriceController())->makeModel($request))->save();

                $request['description'] = __('label.product_register');
                $request['code_operation'] = $product->code;
                $request['price_unit'] = $request->price_unit;
                $request['product_id'] = $product->id;
                ((new FileCabinetController())->makeModel($request))->save();
            }
          }
        }

        if (isset($request->file)){
          $cont = 1;
          foreach ($request->file as $file){
            $path = CommonController::store_file('images_product/'.$product->code, $file);
            $request['photo'] = $path;
            $request['name'] = $file->getClientOriginalName();
            $request['cover'] = ($cont === 1) ? 1 : 0;
            $request['product_id'] = $product->id;
            ((new PhotoController())->makeModel($request))->save();
            $cont ++ ;
          }
        }

        return $product;
      });

    }
    return $response;
  }

  public function store_color_product(Request $request): array
  {
    $validator = $this->rulesValidateEntityColorProduct($request->all());
    if ($validator->fails()){
      $response = Response::get_response($validator->errors()->messages(), 0, 400);
    }else{
      $response = CommonEntity::custom_safe_transaction($request, function ($request) {
        $color_product = ColorProduct::create([
          'color_id' => $request->color_id,
          'product_id' => $request->product_id
        ]);
        return $color_product;
      });

    }
    return $response;
  }

  public function update(Request $request): array
  {
    $product = Product::findOrFail($request->id);
    $validator = $this->rulesValidateEntity($request->all(), true, $product);
    if ($validator->fails()){
      $response = Response::get_response($validator->errors()->messages(), 0, 400);
    }
    else{
      $response = CommonEntity::custom_safe_transaction($request, function ($request){
        $product = Product::findOrFail($request->id);
        $request['code_operation'] = $product->code;
        $request['product_id'] = $product->id;

        if (isset($request->file)){
          $cont = 1;
          foreach ($request->file as $file){
            $path = CommonController::store_file('images_product/'.$product->code, $file);
            $request['photo'] = $path;
            $request['name'] = $file->getClientOriginalName();
            $request['cover'] = ($cont === 1) ? 1 : 0;
            $request['product_id'] = $product->id;
            ((new PhotoController())->makeModel($request))->save();
            $cont ++ ;
          }
        }
        $product->update(($this->makeModel($request))->getAttributes());

        $request['description'] = 'Product '.$product->code.' Actualizado';
        ((new FileCabinetController())->makeModel($request))->save();
        return $product;
      });
    }
    return $response;
  }

  public function delete(Request $request): array
  {
    $response = CommonEntity::custom_safe_transaction($request, function ($request) {
      $product = Product::findOrFail($request->id);
      $request['description'] = 'Producto '.$product->code.' Eliminado';
      $request['code_operation'] = $product->code;
      $request['product_id'] = $product->id;
      ((new FileCabinetController())->makeModel($request))->save();
      $product->delete();
      return $product;
    });
    return $response;
  }

  public function show(Request $request): array
  {
    $branch_details = Product::join('branch as b', 'branch_details.branch_id', '=', 'b.id')
      ->join('products as p', 'branch_details.product_id', '=', 'p.id')
      ->select('p.id','p.code', 'p.name as text', 'p.name', 'branch_details.stock', 'branch_details.acquisition_price')
      ->where('b.id', $request->id)
      ->where('branch_details.stock', '>', 0)
      ->get();
    return Response::get_response($branch_details, 1, 200);
  }

  public function get(Request $request): array
  {
    $branches = ($request->deleted)
      ? Product::onlyTrashed()
        ->join('users as u', 'branch.user_id', '=', 'u.id')
        ->join('units as un', 'branch.user_id', '=', 'u.id')
        ->join('users as u', 'branch.user_id', '=', 'u.id')
        ->select('branch.*', 'u.name as user')->get()
      : Product::withTrashed()
        ->join('users as u', 'products.user_id', '=', 'u.id')
        ->select('products.*', 'u.name as user')
        ->get();

    return Response::get_response($branches, 1, 200);
  }

  public function get_product_by_code($code): array
  {
    $product = (Product::withTrashed()->where('code', $code)->first());
    return Response::get_response($product, 1, 200);
  }

  function numberReturn() {
    return 1;
  }

  public function get_stock_minim(Request $request = null): array
  {
    $branch_id = WebController::$branch_id;
    $products = Product::withoutTrashed()
      ->join('categories as c', 'products.category_id', 'c.id')
      ->select('products.id','products.code', 'products.name','products.quantity_minim','c.name as category', 'products.description', DB::raw("(SELECT SUM(stock) FROM branch_details WHERE product_id = products.id and branch_id = '$branch_id' ) as stockTotal"))
      ->get();

    foreach ($products as $key =>$item){
      if($item->quantity_minim < $item->stockTotal || $item->stockTotal == null){
        unset($products[$key]);
      }
    }

    return Response::get_response($products, 1, 200);
  }

  public function get_list_products_menu(Request $request)
  {
      // Recuperar el categoryId de la solicitud
      $categoryId = $request->input('categoryId');

      // Verificar si el categoryId está asociado a una categoría distinta de "GENERAL"
      $isNotGeneral = false;

      if ($categoryId) {
          $categoryName = DB::table('categories')
              ->where('id', $categoryId)
              ->value('name');

          // Verificar si la categoría no es "GENERAL"
          $isNotGeneral = ($categoryName !== 'General');
      }

      // Filtrar productos según el categoryId y la lógica solicitada
      $products = Product::withoutTrashed()
          ->leftJoin('photos as ph', function ($query) {
              $query->on('products.id', '=', 'ph.product_id');
              $query->where('ph.cover', 1);
              $query->whereNull('ph.deleted_at');
          })
          ->leftJoin('prices as pr', function ($query) {
              $query->on('products.id', '=', 'pr.product_id');
              $query->where('pr.is_predetermined', 0);
              $query->whereNull('pr.deleted_at');
          })
          ->join('categories as c', 'products.category_id', '=', 'c.id')
          ->join('units as u', 'products.unit_id', '=', 'u.id')
          ->join('branch_details as bd', 'products.id', '=', 'bd.product_id')
          ->select(
              'products.*',
              'bd.stock',
              DB::raw("CONCAT('" . asset('') . "', IFNULL(ph.photo, 'images/pr-default.png')) as photo"),
              'c.name as category',
              'u.name as unit',
              'pr.price'
          )
          ->where('bd.branch_id', WebController::$branch_id)
          ->where('products.is_sale', 1)
          // Solo aplicamos el filtro por categoría si el nombre no es "GENERAL"
        ->when($categoryId && $isNotGeneral, function ($query) use ($categoryId) {
            return $query->where('products.category_id', $categoryId);
        })
        ->groupBy('products.id')
        ->get();

    return Response::get_response($products, 1, 200);
}


  public function restore(Request $request): array
  {
    $product = Product::onlyTrashed()->findOrFail($request->id)->restore();
    return Response::get_response($product, 1, 200);
  }

  public function upload_image(Request $request): array
  {
    $response = CommonEntity::custom_safe_transaction($request, function ($request) {
      if (isset($request->file)){
        $product = Product::findOrFail($request->id);
        if (count($request->file) <= (3 - Photo::where('product_id', $request->id)->count())){
          foreach ($request->file as $file){
            $path = CommonController::store_file('images_product/'.$product->code, $file);
            $request['photo'] = $path;
            $request['name'] = $file->getClientOriginalName();
            $request['cover'] = ((Photo::where('product_id', $product->id)->count()) > 0) ? 0 : 1;
            $request['product_id'] = $product->id;
            ((new PhotoController())->makeModel($request))->save();
          }
        }else {
          throw new \Exception('Solo puede subir '.(3 - Photo::where('product_id', $request->id)->count()).' Imagen(es)', -1);
        }
      }
    });

    return $response;
  }
  public function makeModel(Request $request, Model $model = null): Model
  {
    if (!$model){
      $model = new Product();
    }
    $model->code = $request->code;
    $model->name = $request->name;
    $model->description = $request->description;
    $model->quantity_minim = $request->quantity_minim;
    $model->user_id = Auth::user()->id;
    $model->unit_id = $request->unit_id;
    $model->category_id = $request->category_id;
    $model->type_product = $request->type_product;
    $model->is_sale = $request->is_sale;
    $model->relation_product_id = $request->relation_product_id;
    return $model;
  }

  public function verifyExistCodeInProducts($code) {
      return Product::withTrashed()->where('code', $code)->first();
  }


  public function rulesValidateEntity(array $data, $update = false, Model $model = null): \Illuminate\Contracts\Validation\Validator
  {
    if ($update){
      $rules =  [
        "name" => 'required',
        "code" => ['required', Rule::unique('products')->ignore($model->id)],
        "unit_id" =>  'required',
        "category_id" =>  'required',
      ];
    }
    else{
      $rules =  [
        "name" => 'required',
        "code" => 'required|unique:products,code',
        "unit_id" =>  'required',
        "category_id" =>  'required',
      ];
    }

    return Validator::make($data, $rules);
  }

  public function rulesValidateEntityColorProduct(array $data, $update = false, Model $model = null): \Illuminate\Contracts\Validation\Validator
  {
    if ($update){
      $rules =  [
      ];
    }
    else{
      $rules =  [
        "color_id" =>  'required',
        "product_id" =>  'required',
      ];
    }

    return Validator::make($data, $rules);
  }

}
