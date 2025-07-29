<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\JobsApplicationSalaryHelper;

use App\Models\User;
use App\Models\SalaryDocument;
use App\Models\AdditionalSalaryDocument;

class SalaryHelper extends BaseController{
  public function add_salary_document($arr_image, $jobs_application){
    foreach($arr_image as $image){
      $data = new SalaryDocument();
      $data->jobs_application_id = $jobs_application->id;
      $data->save();

      $this->file_helper->manage_file($image, $data, 'salary_document', 'file_name', 'mime_type');
      $data->save();
    }
  }

  public function add_additional_salary_document($arr_image, $jobs_application){
    foreach($arr_image as $image){
      $data = new AdditionalSalaryDocument();
      $data->jobs_application_id = $jobs_application->id;
      $data->save();

      $this->file_helper->manage_file($image, $data, 'additional_salary_document', 'file_name', 'mime_type');
      $data->save();
    }
  }
}
