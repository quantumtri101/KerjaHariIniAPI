<?php
namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Helper\StringHelper;

use App\Models\Outlet;
use App\Models\Branch;
use App\Models\Event;

class IDHelper{
  private $str_length = 6;
  private $id_number_length = 4;

  public $string_helper;

  public function __construct(){
    $this->string_helper = new StringHelper();
  }

  public function generate_new_id($type,$database,$with_underscore = true){
    $data = $database::withTrashed()->orderBy('id','desc')->first();

    if(empty($data))
      $id = $type.($with_underscore ? '_' : '').str_pad(1,$this->id_number_length,"0",STR_PAD_LEFT);
    else{
      if($with_underscore){
        $explode = explode('_',$data->id);
        $counter = (int)$explode[count($explode) - 1];
      }
      else{
        $counter = (int) substr($data->id,strlen($type));
      }

      $idB = $counter+1;
      $idB = str_pad($idB,$this->id_number_length,"0",STR_PAD_LEFT);
      $id = $type.($with_underscore ? '_' : '').$idB;
    }

    return $id;
  }

  public function generate_new_id_with_date($type, $database,$with_underscore = true){
    $data = $database::withTrashed()->orderBy('id','desc')->first();

    if(empty($data))
      $id = $type.($with_underscore ? '_' : '').Carbon::now()->formatLocalized('%Y%m%d').($with_underscore ? '_' : '').str_pad(1,$this->str_length,"0",STR_PAD_LEFT);
    else{
      if($with_underscore){
				$explode = explode('_',$data->id);
				$date = Carbon::createFromFormat('Ymd',$explode[count($explode) - 2]);
        $counter = (int)$explode[count($explode) - 1];
      }
      else{
				$date = Carbon::createFromFormat('Ymd',substr($data->id,strlen($type),8));
        $counter = (int) substr($data->id,strlen($type));
      }

			if($date->isSameDay(Carbon::now()))
				$idB = $counter+1;
			else
				$idB = 1;
      $idB = str_pad($idB,$this->str_length,"0",STR_PAD_LEFT);
      $id = $type.($with_underscore ? '_' : '').Carbon::now()->formatLocalized('%Y%m%d').($with_underscore ? '_' : '').$idB;
    }

    return $id;
  }

  public function generate_new_id_outlet($type){
    $data = Outlet::withTrashed()->orderBy('id','desc')->first();

    if(empty($data))
      $id = $type.Carbon::now()->formatLocalized('%y%m').str_pad(1, 3,"0", STR_PAD_LEFT);
    else{
      $date = Carbon::createFromFormat('ym',substr($data->id, strlen($type), 4));
      $counter = (int) substr($data->id, strlen($type) + 4);

      $idB = $counter+1;
      $idB = str_pad($idB, 3, "0", STR_PAD_LEFT);
      $id = $type.Carbon::now()->formatLocalized('%y%m').$idB;
    }

    return $id;
  }

  public function generate_new_id_branch($type, $outlet_id){
    $data = Branch::withTrashed()->where('id', 'like', '%'.$outlet_id)->orderBy('id','desc')->first();

    if(empty($data))
      $id = $type.str_pad(1, 3,"0", STR_PAD_LEFT).$outlet_id;
    else{
      $counter = (int) substr($data->id, strlen($type));

      $idB = $counter+1;
      $idB = str_pad($idB, 3, "0", STR_PAD_LEFT);
      $id = $type.$idB.$outlet_id;
    }

    return $id;
  }

  public function generate_new_id_event($type){
    $data = Event::withTrashed()->orderBy('id','desc')->first();

    if(empty($data))
      $id = $type.Carbon::now()->formatLocalized('%y%m').str_pad(1, 3,"0", STR_PAD_LEFT);
    else{
      $date = Carbon::createFromFormat('ym',substr($data->id, strlen($type), 4));
      $counter = (int) substr($data->id, strlen($type) + 4);

      $idB = $counter+1;
      $idB = str_pad($idB, 3, "0", STR_PAD_LEFT);
      $id = $type.Carbon::now()->formatLocalized('%y%m').$idB;
    }

    return $id;
  }

  public function generate_new_id_random($database, $column = 'id'){
    do{
      $id = $this->string_helper->generateRandomString1();

      $data = $database::where($column, '=', $id)->first();
    }while(!empty($data));
    return $id;
  }

  public function get_char($total_char_length, $total_num_length, $char_length, $number_length){
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    $char = $characters[rand(0, $charactersLength - 1)];
    if(is_numeric($char) && $total_num_length < $number_length)
      return [
        "char" => $char,
        "total_num_length" => $total_num_length + 1,
      ];
    else if(!is_numeric($char) && $total_char_length < $char_length)
      return [
        "char" => $char,
        "total_char_length" => $total_char_length + 1,
      ];

    return $this->get_char($total_char_length, $total_num_length, $char_length, $number_length);
  }

  public function generate_new_id_code_transaction($char_length, $number_length, $prefix = "TU") {
    $total_num_length = 0;
    $total_char_length = 0;
    $randomString = '';
    for ($i = 0; $i < $char_length + $number_length; $i++){
      $arr = $this->get_char($total_char_length, $total_num_length, $char_length, $number_length);
      $randomString .= $arr["char"];
      

      if(!empty($arr["total_num_length"]))
        $total_num_length = $arr["total_num_length"];
      else if(!empty($arr["total_char_length"]))
        $total_char_length = $arr["total_char_length"];
    }

    return $prefix.'-'.$randomString;
  }
}
