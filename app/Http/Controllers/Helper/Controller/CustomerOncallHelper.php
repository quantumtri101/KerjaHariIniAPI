<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\JobsApproveHelper;
use App\Http\Controllers\Helper\Controller\XPTransactionHelper;

use App\Models\Resume;
use App\Models\Experience;
use App\Models\ResumeSkill;
use App\Models\Skill;
use App\Models\City;
use App\Models\User;

use App\Jobs\SendPushNotificationJob;

class CustomerOncallHelper extends BaseController{
  public function edit_experience($arr_experience, $resume){
    $arr_temp = Experience::where(function($where) use($arr_experience){
      foreach($arr_experience as $temp){
        if(!empty($temp['id']))
          $where = $where->where('id','!=',$temp['id']);
      }
    })
      ->where('resume_id', '=', $resume->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_experience as $key => $temp){
      $city = City::where('name', 'like', '%'.str_replace(' ', '%', $temp["location"]).'%')->first();
      if(!empty($temp["id"]))
        $experience = Experience::find($temp["id"]);
      if(empty($experience))
        $experience = new Experience();

      $experience->resume_id = $resume->id;
      $experience->name = $temp["name"];
      $experience->start_year = $temp["start_year"];
      $experience->end_year = $temp["end_year"];
      $experience->corporation = $temp["company"];
      $experience->description = $temp["description"];
      $experience->city_id = $city->id;
      $experience->save();
    }
  }

  public function edit_skill($arr_skill, $resume){
    $arr_temp = ResumeSkill::where(function($where) use($arr_skill){
      foreach($arr_skill as $temp)
        $where = $where->where('id','!=',$temp["skill"]['id']);
    })
      ->where('resume_id', '=', $resume->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_skill as $key => $temp){
      if(!empty($temp["skill"]["id"]))
        $resume_skill = ResumeSkill::where('id','!=',$temp["skill"]['id'])->where('resume_id', '=', $resume->id)->first();
      if(empty($resume_skill))
        $resume_skill = new ResumeSkill();

      $resume_skill->resume_id = $resume->id;
      $resume_skill->skill_id = $temp["skill"]['id'];
      $resume_skill->save();
    }
  }

  public function edit_resume($request, $user){
    $resume = Resume::where('user_id', '=', $user->id)->first();

    if(empty($resume)){
      $resume = new Resume();
      $resume->user_id = $user->id;
    }
    $resume->name = $request->name;
    $resume->phone = $request->phone;
    $resume->birth_date = Carbon::createFromFormat('d-m-Y', $request->birth_date);
    $resume->address = $request->address;
    $resume->city_id = $request->city_id;
    $resume->marital_status = $request->marital_status;
    $resume->height = str_replace('.', '', $request->height);
    $resume->weight = str_replace('.', '', $request->weight);
    $resume->education_id = $request->education_id;
    $resume->bank_id = $request->bank_id;
    $resume->acc_no = $request->acc_no;
    $resume->save();

    if(!empty($request->vaccine_covid_image)){
      $this->file_helper->manage_image($request->vaccine_covid_image, $resume, 'user_vaccine_covid', 'vaccine_covid_file_name');
      $resume->save();
    }
    if(!empty($request->cv_image)){
      $this->file_helper->manage_image($request->cv_image, $resume, 'user_cv', 'cv_file_name');
      $resume->save();
    }

    $arr_experience = json_decode($request->arr_experience, true);
    $arr_skill = json_decode($request->arr_skill, true);
    $this->edit_experience($arr_experience, $resume);
    $this->edit_skill($arr_skill, $resume);
  }
}
