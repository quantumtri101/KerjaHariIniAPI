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

use App\Models\Resume;
use App\Models\Experience;
use App\Models\ResumeSkill;
use App\Models\Company;
use App\Models\Skill;
use App\Models\User;

use App\Jobs\SendPushNotificationJob;

class ResumeHelper extends BaseController{
  public function add_experience($arr_experience, $resume){
    $arr_temp = Experience::where('resume_id', '=', $resume->id)
      ->where(function($where) use($arr_experience){
        foreach($arr_experience as $experience1){
          if(!empty($experience1["id"]))
            $where = $where->where('id', '!=', $experience1["id"]);
          // $where = $where->where('name', '!=', $experience1["name"]);
        }
      })
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_experience as $experience1){
      // dd($experience);
      if(!empty($experience1["corporation"]))
        $company = Company::where('name', 'like', $experience1["corporation"])->first();

      $experience = Experience::where('resume_id', '=', $resume->id)
        ->where('name', 'like', $experience1["name"])
        ->first();

      if(empty($experience)){
        $experience = new Experience();
        $experience->resume_id = $resume->id;
        $experience->name = $experience1["name"];
      }
      if(!empty($company))
        $experience->company_id = $company->id;
      $experience->city_id = $experience1["city"]["id"];
      $experience->start_year = $experience1["start_year"];
      $experience->end_year = $experience1["end_year"];
      if(!empty($experience1["corporation"]))
        $experience->corporation = $experience1["corporation"];
      if(!empty($experience1["description"]))
        $experience->description = $experience1["description"];
      $experience->save();
    }
  }

  public function get_marital_status($request){
    $arr_marital_status = [
      "unmarried" => "Belum Kawin", 
      "married" => "Kawin", 
      "divorce_by_living" => "Cerai Hidup", 
      "divorce_by_death" => "Cerai Mati",
    ];
    $maritas_status = "";
    foreach($arr_marital_status as $key => $maritas_status1){
      if($maritas_status1 == $request->marital_status)
        $maritas_status = $key;
    }
    return $maritas_status;
  }

  public function add_skill($arr_skill, $resume){
    $arr_temp = ResumeSkill::where('resume_id', '=', $resume->id)
      ->where(function($where) use($arr_skill){
        foreach($arr_skill as $skill1){
          if(!empty($skill1["id"]))
            $where = $where->where('id', '!=', $skill1["id"]);
          // if(!empty($skill1["skill"]))
          //   $where = $where->where('skill_id', '!=', $skill1["skill"]["id"]);
          // else if(!empty($skill1["custom_skill"]))
          //   $where = $where->where('custom_skill', '!=', $skill1["custom_skill"]);
        }
      })
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_skill as $skill1){
      $skill = ResumeSkill::where('resume_id', '=', $resume->id);
      
      if(!empty($skill1["skill"]))
        $skill = $skill->where('skill_id', 'like', $skill1["skill"]["id"]);

      if(!empty($skill1["custom_skill"]))
        $skill = $skill->where('custom_skill', 'like', $skill1["custom_skill"]);

      $skill = $skill->first();

      if(empty($skill)){
        $skill = new ResumeSkill();
        $skill->resume_id = $resume->id;
      }

      if(!empty($skill1["skill"]))
        $skill->skill_id = $skill1["skill"]["id"];
      if(!empty($skill1["custom_skill"]))
        $skill->custom_skill = $skill1["custom_skill"];
      $skill->save();

      if(!empty($skill1["custom_skill"])){
        $data = Skill::where('name', 'like', $skill1["custom_skill"])->first();

        if(empty($data)){
          $data = new Skill();
          $data->name = $skill1["custom_skill"];
          $data->save();
        }

        $skill->skill_id = $data->id;
        $skill->save();

      }
    }
  }
}
