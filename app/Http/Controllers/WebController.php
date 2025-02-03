<?php

namespace App\Http\Controllers;

use App\Entities\Response;
use App\Helpers\ConnectivityHelper;
use App\Models\Branch;
use App\Models\BranchDetail;
use App\Models\Checkout;
use App\Models\CheckoutDetail;
use App\Models\CheckoutUser;
use App\Models\Configuration;
use App\Models\Matrix;
use App\Models\Role;
use App\Models\TransferDetail;
use App\Traits\AlertTrait;
use App\Traits\BranchDetailTrait;
use App\Traits\CashFlowTrait;
use App\Traits\CashFlowTypeTrait;
use App\Traits\CategoryTrait;
use App\Traits\CheckoutDetailTrait;
use App\Traits\CheckoutTrait;
use App\Traits\CheckoutUserTrait;
use App\Traits\ClientTrait;
use App\Traits\BatchTrait;
use App\Traits\ConfigurationTrait;
use App\Traits\ContractTrait;
use App\Traits\CreditTrait;
use App\Traits\DashboardTrait;
use App\Traits\DeliveryDispatchTrait;
use App\Traits\FileCabinetTrait;
use App\Traits\FormulaTrait;
use App\Traits\MarkTrait;
use App\Traits\InventoryAdjustmentTrait;
use App\Traits\PaymentTrait;
use App\Traits\PercentageTrait;
use App\Traits\PermissionTrait;
use App\Traits\ProductionTrait;
use App\Traits\ProductTrait;
use App\Traits\ProviderTrait;
use App\Traits\QuotationTrait;
use App\Traits\ReportTrait;
use App\Traits\SaleTrait;
use App\Traits\ColorTrait;
use App\Traits\ServiceTrait;
use App\Traits\StockTrait;
use App\Traits\SubCategoryTrait;
use App\Traits\TransferTrait;
use App\Traits\UnitTrait;
use App\Traits\UnpackedTrait;
use App\Traits\UserTrait;
use App\Traits\BranchTrait;
use App\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\This;

class WebController extends Controller
{
  use UserTrait;
  use BranchTrait;
  use ProductTrait;
  use CategoryTrait;
 


  
}