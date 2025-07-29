<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Auth;

use App\Http\Controllers\Helper\Controller\BranchImageViewHelper;
use App\Http\Controllers\Helper\Controller\OutletImageViewHelper;
use App\Http\Controllers\Helper\Controller\TableLayoutHelper;

use App\Models\BranchImage;
use App\Models\OutletImage;
use App\Models\Branch;
use App\Models\JobsDocument;
use App\Models\CheckLogDocument;
use App\Models\SalaryDocument;
use App\Models\AdditionalSalaryDocument;
use App\Models\JobsApplication;
use App\Models\Event;

class ImageController extends BaseController{
  public function get_public(Request $request){
    $file = Storage::disk('public')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_sub_category(Request $request){
    $file = Storage::disk('sub_category')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_service(Request $request){
    $file = Storage::disk('service')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_category(Request $request){
    $file = Storage::disk('category')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_chat(Request $request){
    $file = Storage::disk('chat')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_bank(Request $request){
    $file = Storage::disk('bank')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_request_withdraw(Request $request){
    $file = Storage::disk('request_withdraw')->get($request->file_name);
    return response($file,200)->header('content-Type','image/png');
  }

  public function get_jobs(Request $request){
    $file = Storage::disk('jobs')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_jobs_document(Request $request){
    $jobs_document = JobsDocument::find($request->id);
    $file = Storage::disk('jobs_document')->get($jobs_document->file_name);
		return response($file,200)->header('content-Type', $jobs_document->mime_type)->header('Content-disposition','attachment; filename="'.$jobs_document->file_name.'"');
  }

  public function get_check_log_document(Request $request){
    $check_log_document = CheckLogDocument::find($request->id);
    $file = Storage::disk('check_log_document')->get($check_log_document->file_name);
		return response($file,200)->header('content-Type', $check_log_document->mime_type)->header('Content-disposition','attachment; filename="'.$check_log_document->file_name.'"');
  }

  public function get_salary_document(Request $request){
    $salary_document = SalaryDocument::find($request->id);
    $file = Storage::disk('salary_document')->get($salary_document->file_name);
		return response($file,200)->header('content-Type', $salary_document->mime_type)->header('Content-disposition','attachment; filename="'.$salary_document->file_name.'"');
  }

  public function get_additional_salary_document(Request $request){
    $additional_salary_document = AdditionalSalaryDocument::find($request->id);
    $file = Storage::disk('additional_salary_document')->get($additional_salary_document->file_name);
		return response($file,200)->header('content-Type', $additional_salary_document->mime_type)->header('Content-disposition','attachment; filename="'.$additional_salary_document->file_name.'"');
  }

  public function get_jobs_pkhl_document(Request $request){
    $jobs_application = JobsApplication::find($request->id);
    $file = Storage::disk('pkhl')->get($jobs_application->pkhl_file_name);
		return response($file,200)->header('content-Type', $jobs_application->pkhl_mime_type)->header('Content-disposition','attachment; filename="'.$jobs_application->pkhl_file_name.'"');
  }

  public function get_jobs_pkwt_document(Request $request){
    $jobs_application = JobsApplication::find($request->id);
    $file = Storage::disk('pkwt')->get($jobs_application->pkwt_file_name);
		return response($file,200)->header('content-Type', $jobs_application->pkwt_mime_type)->header('Content-disposition','attachment; filename="'.$jobs_application->pkwt_file_name.'"');
  }

  public function get_company(Request $request){
    $file = Storage::disk('company')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_event(Request $request){
    $file = Storage::disk('event')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_banner(Request $request){
    $file = Storage::disk('banner')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_resume_id(Request $request){
    $file = Storage::disk('resume_id')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_resume_selfie(Request $request){
    $file = Storage::disk('resume_selfie')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_user(Request $request){
    $file = Storage::disk('user')->get($request->file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_user_vaccine_covid(Request $request){
    $file = Storage::disk('user_vaccine_covid')->get($request->vaccine_covid_file_name);
		return response($file,200)->header('content-Type','image/png');
  }

  public function get_user_cv(Request $request){
    $file = Storage::disk('user_cv')->get($request->cv_file_name);
		return response($file,200)->header('content-Type','image/png');
  }
}
