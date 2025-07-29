<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Mail;

use App\Http\Controllers\Helper\Controller\PointTransactionHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\CommunicationHelper;

use App\Models\Order;
use App\Models\PayBill;
use App\Models\Reservation;
use App\Models\User;

class PaymentController extends BaseController{

  private function get_data($request){
    if(empty($data) && !empty($request->external_id)){
      $data = Order::find($request->external_id);
    }

    return $data;
  }

  public function simulate_payment(Request $request){
    $helper = new PaymentHelper();
    $data = $this->get_data($request);
    if(empty($data))
      return $this->return_data($request, [
        'status' => 'error',
        'message' => __('controller.data_not_found'),
      ]);

    $helper->simulate_payment($data);

    return $this->return_data($request, [
      'status' => 'success',
    ]);
  }

  public function payment_process($data){
    $communication_helper = new CommunicationHelper();

    if($data instanceof Order){
      // $helper = new EWalletTransactionHelper();
      // $helper->commit_transaction($data, $data->payment_method->data != "cash");
      // $communication_helper->send_push_notif($data->user, 'Payment Successfull', 'Payment for Order #'.$data->id.' was successfull.');

      $data->status_payment = 'paid';
      $data->paid_at = Carbon::now();
      $data->status = "wait_confirmation";
      $data->save();
    }
  }

  private function return_data($request, $arr){
    if(!empty($request->api_type) && $request->api_type == 'web')
      return $this->get_data_helper->return_data($request, $arr, 'back');
    else
      return response()->json($arr);
  }

  public function callback(Request $request){
    if(empty($request->external_id))
      return $this->return_data($request, [
        'status' => 'error',
        'message' => __('controller.data_not_found'),
      ]);
    else if(!empty($request->status) && $request->status != "PAID")
      return $this->return_data($request, [
        'status' => 'error',
        'message' => __('controller.transaction_expired'),
      ]);

    $data = $this->get_data($request);
    if(empty($data))
      return $this->return_data($request, [
        'status' => 'error',
        'message' => __('controller.data_not_found'),
      ]);
    // dd($data);

    $this->payment_process($data);

    return $this->return_data($request, [
      'status' => 'success',
    ]);
  }
}
