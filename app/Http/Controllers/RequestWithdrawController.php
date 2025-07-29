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

use App\Models\RequestWithdraw;
use App\Models\SalaryTransaction;
use App\Models\User;
use App\Models\PaymentMethod;

class RequestWithdrawController extends BaseController{
  private $arr_header = [
    [
      "id" => "created_at",
      "column" => "request_withdraw.created_at",
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
      "column" => "request_withdraw.description",
      "name" => "general.description",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $this->relationship_helper->request_withdraw($data);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) || !empty($request->transaction_code) ? $arr[0] : $arr,
    ], 'view', 'request_withdraw.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $request_withdraw = new RequestWithdraw();
    $user_model = new User();

    $arr = RequestWithdraw::select($request_withdraw->get_table_name().'.*', $user_model->get_table_name().'.name as user_name', $user_model->get_table_name().'.phone as user_phone', $request_withdraw->get_table_name().'.created_at as created_at_format', $request_withdraw->get_table_name().'.updated_at as updated_at_format', $request_withdraw->get_table_name().'.date as date_format',)
      ->leftJoin($user_model->get_table_name(), $request_withdraw->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->leftJoin($user_model->get_table_name().' as created_user', $request_withdraw->get_table_name().'.created_by', '=', 'created_user.id');

    if(!empty($request->id))
      $arr = $arr->where($request_withdraw->get_table_name().'.id', '=', $request->id);

    if(isset($request->is_approve))
      $arr = $arr->where($request_withdraw->get_table_name().'.is_approve', '=', $request->is_approve);

    if(!empty($request->user_id))
      $arr = $arr->where($request_withdraw->get_table_name().'.user_id', '=', $request->user_id);

    if(empty($request->id) && empty($request->user_id) && Auth::check() && Auth::user()->type->name == 'customer')
      $arr = $arr->where($request_withdraw->get_table_name().'.user_id', '=', Auth::user()->id);

    if(empty($request->sort) && empty($request->order))
      $arr = $arr->orderBy('created_at', 'desc');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    // dd($arr->toSql());
    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function detail(Request $request){
    $data = RequestWithdraw::find($request->id);

    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "request_withdraw.component.general_info",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'request_withdraw.detail', [
      'request_withdraw' => $data,
      'arr_tab' => $arr_tab,
    ]);
  }

  public function post(Request $request){
    $helper = new SalaryTransactionHelper();

    $data = new RequestWithdraw();
    $data->bank_id = $request->bank_id;
    $data->acc_no = $request->acc_no;
    $data->acc_name = $request->acc_name;
    $data->user_id = Auth::user()->id;
    $data->amount = str_replace('.', '', $request->amount);
    $data->fee = str_replace('.', '', $request->fee);
    $data->total_amount = $data->amount - $data->fee;
    $data->date = Carbon::now();
    $data->save();

    $transaction = $helper->add_transaction_only($data->user, $data->total_amount, null, 'out', 'Pengajuan Penarikan Dana ke No. Rekening '.$data->acc_no, null, $data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ]);
  }

  public function change_approve(Request $request){
    $helper = new SalaryTransactionHelper();


    $data = RequestWithdraw::find($request->id);
    $data->status = $request->status;
    $data->save();

    $this->communication_helper->send_push_notif($data->user, 'Status Request Penarikan', $data->status == 'accepted' ? 'Pengajuan Penarikan Dana Rp. '.number_format($data->total_amount, 0, ',', '.').', telah Diproses oleh Admin' : 'Pengajuan Penarikan Dana Rp. '.number_format($data->total_amount, 0, ',', '.').', telah ditolak oleh Admin', ["id" => $data->id, 'type' => "request_withdraw"]);

    if($data->status == 'accepted'){
      $data->transfer_date = Carbon::createFromFormat('d/m/Y', $request->transfer_date);
      $this->file_helper->manage_image($request->file, $data, 'request_withdraw');
      $data->save();

      $transaction = SalaryTransaction::where('request_withdraw_id', '=', $data->id)->first();
      if(!empty($transaction))
        $helper->commit_transaction($transaction);
    }
    else if($data->status == 'declined'){
      $data->decline_reason = $request->decline_reason;
      $data->declined_at = Carbon::now();
      $data->save();

      $transaction = SalaryTransaction::where('request_withdraw_id', '=', $data->id)->first();
      if(!empty($transaction))
        $helper->declined_transaction($transaction);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'back');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) || !empty($request->transaction_code) ? $arr[0] : $arr,
    ]);
  }
}
