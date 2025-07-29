<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\PayBillController;
use App\Http\Controllers\Helper\Controller\SalaryTransactionHelper;
use App\Http\Controllers\Helper\Controller\AuthHelper;
use App\Http\Controllers\Helper\CommunicationHelper;

use App\Models\SalaryTransaction;
use App\Models\User;
use App\Models\PaymentMethod;

class SalaryTransactionController extends BaseController{
  private $arr_header = [
    [
      "id" => "created_at",
      "column" => "salary_transaction.created_at",
      "name" => "general.date",
      "data_type" => "date",
    ],
    [
      "id" => "user_name",
      "column" => "user.name",
      "name" => "general.user",
      "data_type" => "string",
    ],
    [
      "id" => "amount_format",
      "column" => "amount_format",
      "name" => "general.amount",
      "data_type" => "string",
    ],
    [
      "id" => "description",
      "column" => "salary_transaction.description",
      "name" => "general.description",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $this->relationship_helper->salary_transaction($data);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) || !empty($request->transaction_code) ? $arr[0] : $arr,
    ], 'view', 'salary_transaction.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $salary_transaction = new SalaryTransaction();
    $user_model = new User();

    $arr = SalaryTransaction::select($salary_transaction->get_table_name().'.*', $user_model->get_table_name().'.name as user_name', $user_model->get_table_name().'.phone as user_phone', $salary_transaction->get_table_name().'.created_at as created_at_format', $salary_transaction->get_table_name().'.updated_at as updated_at_format', $salary_transaction->get_table_name().'.date as date_format',)
      ->leftJoin($user_model->get_table_name(), $salary_transaction->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->leftJoin($user_model->get_table_name().' as created_user', $salary_transaction->get_table_name().'.created_by', '=', 'created_user.id');

    if(!empty($request->id))
      $arr = $arr->where($salary_transaction->get_table_name().'.id', '=', $request->id);

    if(isset($request->is_approve))
      $arr = $arr->where($salary_transaction->get_table_name().'.is_approve', '=', $request->is_approve);

    if(!empty($request->type))
      $arr = $arr->where($salary_transaction->get_table_name().'.type', '=', $request->type);

    if(!empty($request->user_id))
      $arr = $arr->where($salary_transaction->get_table_name().'.user_id', '=', $request->user_id);

    if(empty($request->id) && empty($request->user_id) && Auth::check() && (Auth::user()->type->name == 'customer_oncall' || Auth::user()->type->name == 'customer_regular'))
      $arr = $arr->where($salary_transaction->get_table_name().'.user_id', '=', Auth::user()->id);

    if(empty($request->sort) && empty($request->order))
      $arr = $arr->orderBy('created_at', 'desc');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    // dd($arr->toSql());
    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function post(Request $request){
    $helper = new SalaryTransactionHelper();
    $payment_method = PaymentMethod::find($request->payment_method["id"]);
    $data = $helper->add_transaction(Auth::user(), $request->amount, $payment_method);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ]);
  }

  public function withdraw(Request $request){
    $helper = new SalaryTransactionHelper();
    $data = $helper->add_transaction(Auth::user(), $request->amount, null, 'out', 'Withdraw');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ]);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) || !empty($request->transaction_code) ? $arr[0] : $arr,
    ]);
  }
}
