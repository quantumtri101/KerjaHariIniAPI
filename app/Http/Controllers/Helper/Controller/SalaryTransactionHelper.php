<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\XPTransactionHelper;

use App\Models\SalaryTransaction;
use App\Models\Setting;
use App\Models\User;

use App\Jobs\SendPushNotificationJob;

class SalaryTransactionHelper extends BaseController{
  private function create_transaction($user, $amount, $payment_method, $type, $description, $date, $request_withdraw){
    $payment_helper = new PaymentHelper();

    $data = new SalaryTransaction();
    if(!empty($user))
      $data->user_id = $user->id;
    if(!empty($payment_method))
      $data->payment_method_id = $payment_method->id;
    $data->date = !empty($date) ? $date : Carbon::now();
    $data->amount = round($amount);
    $data->type = $type;
    $data->description = $description;
    $data->total_amount = $data->amount;
    if(!empty($request_withdraw))
      $data->request_withdraw_id = $request_withdraw->id;
    $data->save();

    return $data;
  }

  public function add_transaction_only($user, $amount, $payment_method = null, $type = 'in', $description = null, $date = null, $request_withdraw = null){
    $data = null;
    if($amount > 0){
      $data = $this->create_transaction($user, $amount, $payment_method, $type, $description, $date, $request_withdraw);
    }
    return $data;
  }

  public function add_transaction($user, $amount, $payment_method = null, $type = 'in', $description = null, $date = null, $request_withdraw = null){
    $data = null;
    if($amount > 0){
      $data = $this->create_transaction($user, $amount, $payment_method, $type, $description, $date, $request_withdraw);
      if(!empty($data))
        $this->commit_transaction($data, !empty($user));
    }
    return $data;
  }

  public function update_balance($transaction){
    $communication_helper = new CommunicationHelper();
    
    $user = $transaction->user;
    if(is_numeric($transaction->amount) && $transaction->amount > 0){
      if($transaction->type == 'in'){
        $user->salary_balance += $transaction->amount;
      }
      else if($transaction->type == 'out'){
        $user->salary_balance -= $transaction->amount;
      }
      $user->save();
    }
  }

  public function commit_transaction($transaction, $with_update_balance = true){
    if($with_update_balance)
      $this->update_balance($transaction);

    $transaction->is_approve = 1;
    $transaction->status = 'accepted';
    $transaction->save();
  }

  public function declined_transaction($transaction){
    $transaction->status = 'declined';
    $transaction->save();
  }
}
