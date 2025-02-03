<?php


namespace App\Traits;

use App\Entities\Response;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WebController;
use App\Imports\BranchDetailImport;
use App\Imports\CategoryImport;
use App\Imports\BatchDetailImport;
use App\Imports\ClientImport;
use App\Imports\FactorImport;
use App\Imports\PricesImport;
use App\Imports\ProductImport;
use App\Imports\SaleDetailImport;
use App\Imports\SaleImport;
use App\Imports\UserImport;
use App\Models\Batch;
use App\Models\BatchDetail;
use App\Models\BranchDetail;
use App\Models\Client;
use App\Models\Mark;
use App\Models\Photo;
use App\Models\Price;
use App\Models\Product;
use App\Models\Provieder;
use App\Models\Color;
use App\Models\ColorProduct;
use App\Models\SaleDetail;
use App\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use function Clue\StreamFilter\fun;

trait ProductTrait {

  public function product_index(Request $request)
  {
    $categories = ((new CategoryController())->get($request))['data'];
    $units = ((new UnitController())->get($request))['data'];
    $products = ((new ProductController())->get($request))['data'];

    return view('product.index', compact(  'categories', 'units', 'products'));
  }

  public function list_products()
  {

    $products = Product::withoutTrashed()
      ->leftJoin('photos as ph', function ($query) {
        $query->on('products.id', '=', 'ph.product_id');
        $query->where('ph.cover', 1);
        $query->where('ph.deleted_at', null);
      })
      ->leftJoin('prices as pr', function ($query) {
        $query->on('products.id', '=', 'pr.product_id');
        $query->where('pr.is_predetermined', 0);
        $query->where('pr.deleted_at', null);
      })
      ->join('categories as c', 'products.category_id', '=', 'c.id')
      ->join('units as u', 'products.unit_id', '=', 'u.id')
      ->join('branch_details as bd', 'products.id', '=', 'bd.product_id')
      ->select('products.*',
        'bd.stock',
        'ph.photo',
        'c.name as category',
        'u.name as unit',
        'pr.price'
      )
      ->where('bd.branch_id', WebController::$branch_id)
      ->groupBy('products.id')
      ->get();

    return view('product.list_products', compact('products'));
  }

  public function get_list_products_menu(Request $request)
  {
    $response = (new ProductController())->get_list_products_menu($request);
    return response()->json($response);
  }

  public function list_only_products()
  {
    $products = \DB::table('products as p')
      ->leftJoin('photos as ph', function ($query) {
        $query->on('p.id', '=', 'ph.product_id');
        $query->where('ph.cover', 1);
        $query->where('ph.deleted_at', null);
      })

      ->leftJoin('prices as pr', function ($query) {
        $query->on('p.id', '=', 'pr.product_id');
        $query->where('pr.is_predetermined', 1);
        $query->where('pr.deleted_at', null);
      })

      ->join('categories as c', 'p.category_id', '=', 'c.id')
      ->join('units as u', 'p.unit_id', '=', 'u.id')
      ->select('p.*',
        'ph.photo', 'c.name as category',
        'u.name as unit', 'pr.price', 'pr.id as price_id')
      ->groupBy('p.id')
      ->get();

    $products->map(function ($item) {
      $item->price2 = Price::withoutTrashed()
          ->where('product_id', $item->id)
          ->where('id', '<>', $item->price_id)
          ->orderBy('created_at', 'asc')
          ->first()->price ?? 0;
    });

    return view('product.list_only_products', compact('products'));
  }

  public function import_excel()
  {
      return view('system.import_excel');
  }

  function factory_import($module, $request){
    switch ($module) {
      case 'users':
        return new UserImport();
        break;
      case 'products':
        return new ProductImport();
        break;
      case 'branch_detail':
        return new BranchDetailImport();
        break;
      case 'client':
        return new ClientImport();
        break;
      case 'category':
        return new CategoryImport();
        break;
      case 'sale':
        return new SaleImport();
        break;
      case 'detailSale':
        return new SaleDetailImport();
        break;
      case 'prices':
        return new PricesImport();
        break;
      case 'factor':
        return new FactorImport();
        break;
      case 'batch_detail':
        $count_batch = Batch::withTrashed()->where("branch_id", WebController::$branch_id)->count() + 1;
        $batch = Batch::create([
          'code' => WebController::generate_code($count_batch),
          'date_into' => Carbon::now(),
          'provider_id' => Provieder::first()->id,
          'branch_id' => WebController::$branch_id,
        ]);

//        $branch_origin = ((new BranchController())->get_main_branch())->id;
//        $transfer = null;
//        if ($branch_origin !== WebController::$branch_id){
//          $transfer = Transfer::create([
//            'code' => WebController::generate_code((Transfer::count() + 1)),
//            'date_transfer' => Carbon::today(),
//            'branch_origin_id' => $branch_origin,
//            'note_transfer' => 'Por Ingreso de Lote',
//            'branch_destine_id' => WebController::$branch_id,
//            'user_id' => Auth::user()->id,
//            'status' => 0
//          ]);
//        }
//        $transfer_id = ($transfer != null) ? $transfer->id : null;
        return new BatchDetailImport($batch->id, null, $request);
    }
  }

  public function upload_file_excel(Request $request)
  {
    $data = \DB::transaction(function () use ($request) {
      try {
        $import = $this->factory_import($request->module, $request);
        Excel::import($import, $request->file('file'));
        \DB::commit();
        return 1;
      } catch (\Exception $e) {
        \DB::rollback();
        $messagge = ($e->getCode() == -1) ? $e->getMessage() : "Error al importar el excel, formato incorrecto o opcion incorrecta";
        $messagge = $e->getMessage();
//        Log::error($e->getMessage());
        return $messagge;
//        return "Error al importar el excel, formato incorrecto o opcion incorrecta";
      }
    });

    $status = ($data === 1) ? 200 : 500;
    return response()->json($data, $status);
  }

  public function update_price(Request $request)
  {
    $price = Price::find($request->id);
    if (WebController::$enable_factor_in_prices_product == 1){
      $price->update([
        'price' => (float) $request->value,
        'factor' => (float) $request->factor,
      ]);
    }
    else{
      $price->update([
        'price' => (float) $request->value,
      ]);
    }

    return response()->json('great!', 200);
  }

  public function delete_price(Request $request)
  {
    $price = Price::find($request->id)->delete();
    return response()->json('great!', 200);
  }

  public function price_store(Request $request)
  {
    $request['user_id'] = Auth::user()->id;
    $price = Price::create($request->all());
    return response()->json('great!', 200);
  }

  public function show_product(Request $request)
  {
    $product = Product::findOrFail($request->id);
    $photos = Photo::withoutTrashed()->where('product_id', $request->id)->get();

    $prices = Price::withoutTrashed()
      ->join('products as p', 'prices.product_id', '=', 'p.id')
      ->select('prices.description',
        'prices.price', 'prices.id')
      ->where('p.id', $request->id)
      ->get();

    return response()->json([
      'data' => 0,
      'view' => view('product.show', compact('prices',
        'product', 'photos'))->render()
    ]);
  }

  public function details_stock_in_branch(Request $request)
  {
    $color_product = ColorProduct::withoutTrashed()
      ->join('products as p', 'color_product.product_id','=', 'p.id')
      ->join('colors as z', 'color_product.color_id','=', 'z.id')
      ->select('color_product.id', 'p.name', 'z.color')
      ->where('color_product.id', $request->id)
      ->first();

    $branches = BranchDetail::withoutTrashed()->join('branch as b', 'branch_details.branch_id', '=', 'b.id')
      ->select('b.name', DB::raw('SUM(branch_details.stock) as stock'))
      ->where('color_product_id', $request->id)
      ->groupBy('b.id')
      ->get();

    return response()->json([
      'data' => 0,
      'view' => view('product.details_stock_in_branch', compact('color_product', 'branches'))->render()
    ]);
  }

  public function update_price_product(Request $request)
  {

    $price = Price::findOrFail($request->id);
    if ($request->checked){
      Price::where('deleted_at', null)
        ->where('product_id', $price->product_id)
        ->update(['is_predetermined' => false]);

      Price::findOrFail($request->id)
        ->update(['is_predetermined' => true]);
    }
    else{
      Price::where('deleted_at', null)->where('product_id', $price->product_id)->update(['is_predetermined' => false]);
    }

    return response()->json('great!', 200);
  }

  public function store_product(Request $request)
  {
    $response = (new ProductController())->store($request);
    return response()->json($response);
  }

  public function store_product_color(Request $request)
  {
    $response = (new ProductController())->store_color_product($request);
    return response()->json($response);
  }

  public function update_product(Request $request)
  {
    $response = (new ProductController())->update($request);
    return response()->json($response);
  }

  public function delete_photo(Request $request)
  {
    $response = (new PhotoController())->delete($request);
    return response()->json($response);
  }

  public function product_restore(Request $request)
  {
    $response = (new ProductController())->restore($request);
    return response()->json($response);
  }

  public function make_cover_photo(Request $request)
  {
    $response = (new PhotoController())->update($request);
    return response()->json($response);
  }

  public function upload_image(Request $request)
  {
    $response = (new ProductController())->upload_image($request);
    return response()->json($response);
  }

  public function product_delete(Request $request)
  {
    $response = (new ProductController())->delete($request);
    return response()->json($response);
  }

  public function get_product_with_prices(Request $request)
  {
    $product = \DB::table('products as p')
      ->leftJoin('photos as ph', function ($query) {
        $query->on('p.id', '=', 'ph.product_id');
        $query->where('ph.cover', 1);
        $query->where('ph.deleted_at', null);
      })
      ->join('units as u', 'p.unit_id', '=', 'u.id')
      ->join('prices as pr', 'p.id', '=', 'pr.product_id')
      ->select('p.*',
        'u.name as unit',
        'pr.id as price_id',
        'pr.price', 'pr.description as type_price',
        'ph.photo')
      ->where('p.id', $request->id)
      ->first();

    $product->stock = (new BranchController())->get_stock_product_branch_category($product->id, WebController::$branch_id)->stock ?? 0;


    $prices = Price::withoutTrashed()->select('id', 'description as name', 'product_id', 'price')
      ->where('product_id', $request->id)
      ->get();

    return response()->json([
      'product' => $product,
      'prices' => $prices
    ], 200);
  }

  public function get_product_prices(Request $request)
  {
      // Obtener los precios del producto, excluyendo los productos eliminados
      $prices = Price::withoutTrashed()
          ->select('id', 'description as name', 'product_id', 'price')
          ->where('product_id', $request->id)  // Filtra por el ID del producto
          ->get();  // Obtiene todos los precios del producto

      // Retornar los precios como respuesta JSON
      return response()->json([
          'prices' => $prices
      ], 200);
  }

  function get_products(Request $request){

    $product = (isset($request->deleted) ?
      Product::onlyTrashed()
      ->join('categories as c', 'products.category_id', '=', 'c.id')
      ->join('units as u', 'products.unit_id', '=', 'u.id')
      ->select('products.id',
        'code',
        \DB::raw('CONCAT(products.code," - ", products.name ) AS name'),
        \DB::raw('CONCAT(products.code," - ", products.name ) AS text'),
        'products.description',
        'c.name as category',
        'u.name as unit'
      )
      ->orderBy('products.created_at', 'asc')
      ->get()

      : Product::withoutTrashed()
      ->join('categories as c', 'products.category_id', '=', 'c.id')
      ->join('units as u', 'products.unit_id', '=', 'u.id')
      ->select('products.id', 'code',
        \DB::raw('CONCAT(products.code," - ", products.name ) AS name'),
        \DB::raw('CONCAT(products.code," - ", products.name ) AS text'),
        'products.description',
        'c.name as category',
        'u.name as unit'
      )
      ->where(function ($query) use ($request) {
        if (isset($request->category)) {
          $query->where('products.category_id',$request->category );
        }
      })
      ->orderBy('products.created_at', 'asc')
      ->get());


    return response()->json([
      'data' => $product
    ]);
  }

  function get_products_by_category(Request $request) {
    $products = Product::join('categories as c', 'products.category_id', '=', 'c.id')
      ->select('products.*', 'c.name as category', \DB::raw('CONCAT(products.code, " - ",  products.name ) AS text'))
      ->where('c.id', $request->category_id)
      ->get();

    return response()->json(['data' => $products]);
  }

  function get_products_by_filters(Request $request){
    $products = \DB::table('branch_details as bd')
      ->join('branch as b', 'bd.branch_id', '=', 'b.id')
      ->join('products as p', 'bd.product_id', '=', 'p.id')
      ->leftJoin('photos as ph', function ($query) {
        $query->on('p.id', '=', 'ph.product_id');
        $query->where('ph.cover', 1);
        $query->where('ph.deleted_at', null);
      })
      ->join('categories as c', 'p.category_id', '=', 'c.id')
      ->select(\DB::raw('CONCAT(p.code," - ", p.name ) AS name'),
        \DB::raw('CONCAT(p.code," - ", p.name ) AS value'),
        'p.id',
        'bd.id as product_id',
        'p.id as data',
        'p.code',
        \DB::raw('CONCAT(p.code, " - ",  p.name ) AS text'),
        'ph.photo')
      ->where('bd.deleted_at', null)
      ->where('bd.stock', '>', 0)
      ->where(function ($query) use ($request) {
        if (isset($request->category)){
          if ($request->category != "all"){
            $query->where('c.id', $request->category);
          }
        }
      })
      ->where(function ($query) use ($request) {
        if (isset($request->branch_id)){
          $query->where('b.id', $request->branch_id);
        }
        else{
          $query->where('b.id', WebController::$branch_id);
        }
      })
//      ->orderBy('bd.created_at', 'asc')
      ->groupBy('p.code')
      ->get();

    if($request->ajax()){
      return response()->json(['data' => $products]);

    }else{
      return $products;
    }

  }

  function get_product(Request $request){
    if (isset($request->code)){
      $product = Product::withoutTrashed()->where('code', $request->code)->first();
      if ($product->type_product == 1) {
        $product = Product::withoutTrashed()
          ->join('prices as pr', 'products.id', '=', 'pr.product_id')
          ->join('units as u', 'products.unit_id', '=', 'u.id')
          ->select('products.*', 'pr.price', 'u.name as unit')
          ->where('code', $request->code)
          ->where('pr.is_predetermined', 1)
          ->first();
      }

    }
    else{
      $product = Product::withoutTrashed()
        ->join('units as u', 'products.unit_id', '=', 'u.id')
        ->select('products.*','u.name as unit')
        ->where('products.id', $request->id)
        ->first();
    }

    if ($product->type_product == 1) {
      $batch_detail = BatchDetail::withoutTrashed()
        ->join('batch as b', 'batch_details.batch_id', '=', 'b.id')
        ->where('product_id', $product->id)
        ->where('b.branch_id', WebController::$branch_id)
        ->orderBy('batch_details.created_at', 'desc')
        ->first();

      $product['price_batch'] = (isset($batch_detail->cost)) ? $batch_detail->cost : 0;
    }
    return response()->json([
      'data' => $product
    ]);
  }

  function get_product_all(Request $request){
    $products = Product::withoutTrashed()
      ->join('units as u', 'products.unit_id', '=', 'u.id')
      ->select('products.*',
        'u.name as unit')
      ->where('type_product', 1)
      ->get();

    $products->map(function ($item) {
      $item->batch = BranchDetail::withoutTrashed()
        ->join('products as p', 'branch_details.product_id', '=', 'p.id')
        ->join('branch as b', 'branch_details.branch_id', '=', 'b.id')
        ->select(DB::raw('SUM(branch_details.stock) as stock'),
          'p.id', 'branch_details.acquisition_price')
        ->where('p.id', $item->id)
        ->where('branch_details.branch_id', WebController::$branch_id)
        ->where('branch_details.deleted_at', null)
        ->groupBy('p.id')
        ->get();

      $item->stock_total = $item->batch->sum('stock');
      $item->cost = $item->batch->avg('acquisition_price');
    });

    return response()->json([
      'data' => $products
    ]);
  }

  function print_tags(Request $request){
    return view('product.print_tags');
  }


}

