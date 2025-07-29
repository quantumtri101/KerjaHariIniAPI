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

use App\Models\Event;
use App\Models\EventImage;
use App\Models\User;

class EventHelper extends BaseController{
  public function edit_image($arr_image, $event){
    $arr_temp = EventImage::where(function($where) use($arr_image){
      foreach($arr_image as $temp){
        if(!empty($temp['id']))
          $where = $where->where('id','!=',$temp['id']);
      }
    })
      ->where('event_id', '=', $event->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_image as $temp){
      if(!empty($temp["id"]))
        $event_image = EventImage::find($temp["id"]);
      if(empty($event_image))
        $event_image = new EventImage();

      $event_image->event_id = $event->id;
      $event_image->is_publish = 1;
      $event_image->save();

      if(!empty($temp["image"]) && $temp["image"] != ""){
        $this->file_helper->manage_image($temp["image"], $event_image, 'event');
        $event_image->save();
      }
    }
  }
}
