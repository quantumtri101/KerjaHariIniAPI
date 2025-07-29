<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Auth;
use Hash;
use Curl;
use Image;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Helper\Controller\BaseHelper;

use App\Http\Controllers\Helper\FileHelper;
use App\Http\Controllers\Helper\IDHelper;
use App\Http\Controllers\Helper\StringHelper;
use App\Http\Controllers\Helper\RelationshipHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\GetDataHelper;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\SendSMSHelper;

class BaseController extends Controller{
  public $app_name = 'Casual';
  public $app_address = '';
  public $app_version = '0.0.0001';
  public $web_admin_name = '';
  public $locale = 'id';
  public $str_length = 5;
  public $num_data = 20;
  public $reset_password_expired = 7;
  public $job_wait_time = 10;
  public $column_base_category = 'base_category_id';
  public $url_asset = null;

  protected $url_backup = "http://backup-transaction.quantumtri.com";
  protected $url_city_province_backup = "http://city-province.quantumtri.com";
  protected $backup_from = "casual";
  protected $url_admin = "http://casual-admin.quantumtri.com";

  public $file_helper;
  public $id_helper;
  public $string_helper;
  public $relationship_helper;
  public $payment_helper;
  public $get_data_helper;
  public $communication_helper;
  public $send_sms_helper;
  public $base_helper;

  public function __construct(){
    $this->web_admin_name = __('general.app_name');
    $this->url_asset = url('/');

    $this->file_helper = new FileHelper();
    $this->id_helper = new IDHelper();
    $this->string_helper = new StringHelper();
    $this->relationship_helper = new RelationshipHelper();
    $this->payment_helper = new PaymentHelper();
    $this->send_sms_helper = new SendSMSHelper();
    $this->base_helper = new BaseHelper();
    $this->get_data_helper = new GetDataHelper($this->num_data);
    $this->communication_helper = new CommunicationHelper($this->app_name);
  }

  protected function manage_per_page($request){
    return !empty($request->per_page) ? $request->per_page : $this->num_data;
  }

  public function processed_to_backup($data, $method = 'add'){
    $response = $curl_helper->request($this->url_backup.'/transaction', [
      "Content-Type" => "application/json",
    ], [
      'data_id' => $data->id,
      'from' => $this->backup_from,
      'data' => $data,
    ], $method == 'add' ? 'post' : 'put');
  }

  protected function manage_validation($request, $arr_condition){
    $validator = Validator::make($request->all(), $arr_condition);
    if($validator->fails())
      return [
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ];

    return null;
  }

  function distance($lat1, $lon1, $lat2, $lon2, $unit) {

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
  
    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}
