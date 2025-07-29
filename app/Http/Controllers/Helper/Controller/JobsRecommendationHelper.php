<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\SalaryTransactionHelper;

use App\Models\JobsRecommendationSubCategory;
use App\Models\JobsRecommendationCity;
use App\Models\User;

class JobsRecommendationHelper extends BaseController{
  public function edit_sub_category($arr_sub_category, $jobs_recommendation){
    $arr_temp = JobsRecommendationSubCategory::where(function($where) use($arr_sub_category){
      foreach($arr_sub_category as $temp){
        if(!empty($temp['id']))
          $where = $where->where('sub_category_id','!=',$temp['id']);
      }
    })
      ->where('jobs_recommendation_id', '=', $jobs_recommendation->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_sub_category as $temp){
      $data = JobsRecommendationSubCategory::where('sub_category_id', '=', $temp["id"])
        ->where('jobs_recommendation_id', '=', $jobs_recommendation->id)
        ->first();
      if(empty($data))
        $data = new JobsRecommendationSubCategory();

      $data->jobs_recommendation_id = $jobs_recommendation->id;
      $data->sub_category_id = $temp["id"];
      $data->save();
    }
  }

  public function edit_city($arr_city, $jobs_recommendation){
    $arr_temp = JobsRecommendationCity::where(function($where) use($arr_city){
      foreach($arr_city as $temp){
        if(!empty($temp['id']))
          $where = $where->where('city_id','!=',$temp['id']);
      }
    })
      ->where('jobs_recommendation_id', '=', $jobs_recommendation->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_city as $temp){
      $data = JobsRecommendationCity::where('city_id', '=', $temp["id"])
        ->where('jobs_recommendation_id', '=', $jobs_recommendation->id)
        ->first();
      if(empty($data))
        $data = new JobsRecommendationCity();

      $data->jobs_recommendation_id = $jobs_recommendation->id;
      $data->city_id = $temp["id"];
      $data->save();
    }
  }
}
