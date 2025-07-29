<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalaryTransactionController;
use App\Http\Controllers\RequestWithdrawController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\CheckLogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\CalendarController;

use App\Http\Controllers\Master\TermConditionController;
use App\Http\Controllers\Master\PrivacyController;
use App\Http\Controllers\Master\BannerController;
use App\Http\Controllers\Master\SkillController;
use App\Http\Controllers\Master\BankController;
use App\Http\Controllers\Master\RangeSalaryController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\SubCategoryController;
use App\Http\Controllers\Master\EducationController;
use App\Http\Controllers\Master\CompanyController;
use App\Http\Controllers\Master\CompanyPositionController;

use App\Http\Controllers\Resume\ResumeController;

use App\Http\Controllers\Report\ReportEventController;

use App\Http\Controllers\GeneralQuiz\GeneralQuizAnswerController;
use App\Http\Controllers\GeneralQuiz\GeneralQuizOptionController;
use App\Http\Controllers\GeneralQuiz\GeneralQuizQuestionController;
use App\Http\Controllers\GeneralQuiz\GeneralQuizResultController;
use App\Http\Controllers\GeneralQuiz\GeneralQuizController;

use App\Http\Controllers\Jobs\JobsApplicationController;
use App\Http\Controllers\Jobs\JobsAppliedController;
use App\Http\Controllers\Jobs\JobsController;
use App\Http\Controllers\Jobs\JobsInterviewController;
use App\Http\Controllers\Jobs\JobsQualificationController;
use App\Http\Controllers\Jobs\JobsRecommendationController;
use App\Http\Controllers\Jobs\JobsSalaryController;
use App\Http\Controllers\Jobs\JobsApproveController;
use App\Http\Controllers\Jobs\JobsDocumentController;
use App\Http\Controllers\Jobs\JobsShiftController;
use App\Http\Controllers\Jobs\JobsImageController;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CustomerOncallController;
use App\Http\Controllers\User\CustomerRegularController;
use App\Http\Controllers\User\ROController;
use App\Http\Controllers\User\StaffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('image')->group(function () {
  Route::get('/public', [ImageController::class, 'get_public']);
  Route::get('/sub-category', [ImageController::class, 'get_sub_category']);
  Route::get('/service', [ImageController::class, 'get_service']);
  Route::get('/vehicle', [ImageController::class, 'get_vehicle']);
  Route::get('/add-on', [ImageController::class, 'get_add_on']);
  Route::get('/banner', [ImageController::class, 'get_banner']);
  Route::get('/bank', [ImageController::class, 'get_bank']);
  Route::get('/chat', [ImageController::class, 'get_chat']);
  Route::get('/jobs', [ImageController::class, 'get_jobs']);
  Route::get('/request-withdraw', [ImageController::class, 'get_request_withdraw']);
  
  Route::get('/company', [ImageController::class, 'get_company']);
  Route::get('/event', [ImageController::class, 'get_event']);
  Route::get('/category', [ImageController::class, 'get_category']);
  Route::prefix('resume')->group(function () {
    Route::get('/id', [ImageController::class, 'get_resume_id']);
    Route::get('/selfie', [ImageController::class, 'get_resume_selfie']);
  });
  Route::prefix('user')->group(function () {
    Route::get('/', [ImageController::class, 'get_user']);
    Route::get('/id', [ImageController::class, 'get_user_id']);
    Route::get('/selfie', [ImageController::class, 'get_user_selfie']);
    Route::get('/vaccine-covid', [ImageController::class, 'get_user_vaccine_covid']);
    Route::get('/cv', [ImageController::class, 'get_user_cv']);
  });
});

Route::prefix('auth')->group(function () {
  Route::view('/login', 'auth.login_v2')->name('login');
  Route::post('/login', [AuthController::class, 'login']);
  Route::get('/logout', [AuthController::class, 'logout']);
  Route::get('/reset-password', [AuthController::class, 'reset_password']);
  
});

Route::prefix('payment')->group(function () {
  Route::post('/xendit/callback', [PaymentController::class, 'callback']);
});

Route::get('/term-condition', [TermConditionController::class, 'get_view']);
Route::get('/privacy', [PrivacyController::class, 'get_view']);
Route::get('/jobs/print-qr', [JobsController::class, 'print_qr']);
Route::get('/jobs/document/download', [ImageController::class, 'get_jobs_document']);
Route::get('/check-log/document/download', [ImageController::class, 'get_check_log_document']);
Route::get('/salary/document/download', [ImageController::class, 'get_salary_document']);
Route::get('/additional-salary/document/download', [ImageController::class, 'get_additional_salary_document']);
Route::get('/jobs/application/pkwt/download', [ImageController::class, 'get_jobs_pkwt_document']);
Route::get('/jobs/application/pkhl/download', [ImageController::class, 'get_jobs_pkhl_document']);

Route::view('/user/request-delete', 'home.request_delete');
Route::post('/user/request-delete', [UserController::class, 'request_delete']);
Route::view('/user/request-delete/finish', 'home.request_delete_finish');

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/', [HomeController::class, 'index']);

  Route::prefix('auth')->group(function () {
    Route::get('/change-active', [AuthController::class, 'change_active']);
    Route::view('/change-password', 'auth.change_password');
    Route::post('/change-password', [AuthController::class, 'change_password']);
  });

  Route::prefix('master')->group(function () {
    Route::prefix('category')->group(function () {
      Route::get('/', [CategoryController::class, 'index']);
      Route::get('/action', [CategoryController::class, 'action']);
      Route::get('/multiple', [CategoryController::class, 'multiple']);
      Route::post('/', [CategoryController::class, 'post']);
      Route::post('/multiple', [CategoryController::class, 'multiple_post']);
      Route::post('/edit', [CategoryController::class, 'put']);
      Route::get('/delete', [CategoryController::class, 'delete']);
    });

    Route::prefix('sub-category')->group(function () {
      Route::get('/', [SubCategoryController::class, 'index']);
      Route::get('/action', [SubCategoryController::class, 'action']);
      Route::get('/multiple', [SubCategoryController::class, 'multiple']);
      Route::post('/', [SubCategoryController::class, 'post']);
      Route::post('/multiple', [SubCategoryController::class, 'multiple_post']);
      Route::post('/edit', [SubCategoryController::class, 'put']);
      Route::get('/delete', [SubCategoryController::class, 'delete']);
    });

    Route::prefix('company')->group(function () {
      Route::get('/', [CompanyController::class, 'index']);
      Route::get('/detail', [CompanyController::class, 'detail']);
      Route::get('/action', [CompanyController::class, 'action']);
      Route::get('/multiple', [CompanyController::class, 'multiple']);
      Route::post('/', [CompanyController::class, 'post']);
      Route::post('/multiple', [CompanyController::class, 'multiple_post']);
      Route::post('/edit', [CompanyController::class, 'put']);
      Route::get('/delete', [CompanyController::class, 'delete']);
    });

    Route::prefix('company-position')->group(function () {
      Route::get('/', [CompanyPositionController::class, 'index']);
      Route::get('/action', [CompanyPositionController::class, 'action']);
      Route::get('/multiple', [CompanyPositionController::class, 'multiple']);
      Route::post('/', [CompanyPositionController::class, 'post']);
      Route::post('/multiple', [CompanyPositionController::class, 'multiple_post']);
      Route::post('/edit', [CompanyPositionController::class, 'put']);
      Route::get('/delete', [CompanyPositionController::class, 'delete']);
    });

    Route::prefix('skill')->group(function () {
      Route::get('/', [SkillController::class, 'index']);
      Route::get('/action', [SkillController::class, 'action']);
      Route::get('/multiple', [SkillController::class, 'multiple']);
      Route::post('/', [SkillController::class, 'post']);
      Route::post('/multiple', [SkillController::class, 'multiple_post']);
      Route::post('/edit', [SkillController::class, 'put']);
      Route::get('/delete', [SkillController::class, 'delete']);
    });

    Route::prefix('range-salary')->group(function () {
      Route::get('/', [RangeSalaryController::class, 'index']);
      Route::get('/action', [RangeSalaryController::class, 'action']);
      Route::get('/multiple', [RangeSalaryController::class, 'multiple']);
      Route::post('/', [RangeSalaryController::class, 'post']);
      Route::post('/multiple', [RangeSalaryController::class, 'multiple_post']);
      Route::post('/edit', [RangeSalaryController::class, 'put']);
      Route::get('/delete', [RangeSalaryController::class, 'delete']);
    });

    Route::prefix('bank')->group(function () {
      Route::get('/', [BankController::class, 'index']);
      Route::get('/action', [BankController::class, 'action']);
      Route::get('/multiple', [BankController::class, 'multiple']);
      Route::post('/', [BankController::class, 'post']);
      Route::post('/multiple', [BankController::class, 'multiple_post']);
      Route::post('/edit', [BankController::class, 'put']);
      Route::get('/delete', [BankController::class, 'delete']);
    });

    Route::prefix('education')->group(function () {
      Route::get('/', [EducationController::class, 'index']);
      Route::get('/action', [EducationController::class, 'action']);
      Route::get('/multiple', [EducationController::class, 'multiple']);
      Route::post('/', [EducationController::class, 'post']);
      Route::post('/multiple', [EducationController::class, 'multiple_post']);
      Route::post('/edit', [EducationController::class, 'put']);
      Route::get('/delete', [EducationController::class, 'delete']);
    });

    Route::prefix('term-condition')->group(function () {
      Route::get('/', [TermConditionController::class, 'index']);
      Route::put('/', [TermConditionController::class, 'put']);
    });

    Route::prefix('privacy')->group(function () {
      Route::get('/', [PrivacyController::class, 'index']);
      Route::put('/', [PrivacyController::class, 'put']);
    });
  });

  Route::prefix('general-quiz')->group(function () {
    Route::get('/', [GeneralQuizController::class, 'index']);
    Route::get('/action', [GeneralQuizController::class, 'action']);
    Route::get('/detail', [GeneralQuizController::class, 'detail']);
    Route::get('/multiple', [GeneralQuizController::class, 'multiple']);
    Route::post('/', [GeneralQuizController::class, 'post']);
    Route::post('/multiple', [GeneralQuizController::class, 'multiple_post']);
    Route::post('/edit', [GeneralQuizController::class, 'put']);
    Route::get('/delete', [GeneralQuizController::class, 'delete']);

    Route::prefix('option')->group(function () {
      Route::get('/', [GeneralQuizOptionController::class, 'index']);
      Route::post('/', [GeneralQuizOptionController::class, 'post']);
      Route::post('/edit', [GeneralQuizOptionController::class, 'put']);
      Route::get('/delete', [GeneralQuizOptionController::class, 'delete']);
    });

    Route::prefix('result')->group(function () {
      Route::get('/', [GeneralQuizResultController::class, 'index']);
      Route::post('/', [GeneralQuizResultController::class, 'post']);
      Route::post('/edit', [GeneralQuizResultController::class, 'put']);
      Route::get('/delete', [GeneralQuizResultController::class, 'delete']);
    });
  });

  Route::prefix('rating')->group(function () {
    Route::get('/', [RatingController::class, 'index']);
    Route::post('/', [RatingController::class, 'post']);
  });

  Route::prefix('jobs')->group(function () {
    Route::get('/', [JobsController::class, 'index']);
    Route::get('/action', [JobsController::class, 'action']);
    Route::get('/detail', [JobsController::class, 'detail']);
    Route::get('/choose-staff', [JobsController::class, 'choose_staff']);
    Route::get('/export/pdf', [JobsController::class, 'export_jobs_approve_pdf']);
    Route::post('/', [JobsController::class, 'post']);
    Route::post('/edit', [JobsController::class, 'put']);
    Route::post('/choose-staff', [JobsController::class, 'add_choose_staff']);
    Route::post('/change-live', [JobsController::class, 'change_live_app']);
    Route::post('/ots', [JobsController::class, 'add_staff_ots']);
    Route::get('/delete', [JobsController::class, 'delete']);

    Route::prefix('salary')->group(function () {
      Route::get('/', [JobsSalaryController::class, 'index']);
      Route::get('/action', [JobsSalaryController::class, 'action']);
      Route::get('/detail', [JobsSalaryController::class, 'detail']);
      Route::post('/', [JobsSalaryController::class, 'post']);
      Route::post('/edit', [JobsSalaryController::class, 'put']);
      
      Route::get('/delete', [JobsSalaryController::class, 'delete']);
    });

    Route::prefix('shift')->group(function () {
      Route::get('/', [JobsShiftController::class, 'index']);
      Route::post('/', [JobsShiftController::class, 'post']);
      Route::prefix('approve')->group(function () {
        Route::post('/', [JobsShiftController::class, 'change_approve']);
        Route::post('/salary', [JobsShiftController::class, 'change_approve_salary']);
        Route::post('/additional-salary', [JobsShiftController::class, 'change_approve_additional_salary']);
      });
      Route::post('/edit', [JobsShiftController::class, 'put']);
      Route::get('/delete', [JobsShiftController::class, 'delete']);
    });

    Route::prefix('qualification')->group(function () {
      Route::get('/', [JobsQualificationController::class, 'index']);
      Route::post('/', [JobsQualificationController::class, 'post']);
      Route::post('/edit', [JobsQualificationController::class, 'put']);
      Route::get('/delete', [JobsQualificationController::class, 'delete']);
    });

    Route::prefix('image')->group(function () {
      Route::get('/', [JobsImageController::class, 'index']);
      Route::post('/', [JobsImageController::class, 'post']);
      Route::post('/edit', [JobsImageController::class, 'put']);
      Route::get('/delete', [JobsImageController::class, 'delete']);
    });

    Route::prefix('document')->group(function () {
      Route::get('/', [JobsDocumentController::class, 'index']);
      Route::post('/', [JobsDocumentController::class, 'post']);
      Route::post('/edit', [JobsDocumentController::class, 'put']);
      Route::get('/delete', [JobsDocumentController::class, 'delete']);
    });

    Route::prefix('application')->group(function () {
      Route::get('/', [JobsApplicationController::class, 'index']);
      Route::get('/detail', [JobsApplicationController::class, 'detail']);
      Route::post('/upload/pkhl', [JobsApplicationController::class, 'upload_pkhl']);
      Route::post('/upload/pkwt', [JobsApplicationController::class, 'upload_pkwt']);
      Route::post('/', [JobsApplicationController::class, 'post']);
      Route::post('/edit', [JobsApplicationController::class, 'put']);
      Route::post('/change-status', [JobsApplicationController::class, 'change_status']);
      Route::get('/delete', [JobsApplicationController::class, 'delete']);
    });

    Route::prefix('applied')->group(function () {
      Route::get('/', [JobsAppliedController::class, 'index']);
      Route::post('/', [JobsAppliedController::class, 'post']);
      Route::post('/edit', [JobsAppliedController::class, 'put']);
      Route::get('/delete', [JobsAppliedController::class, 'delete']);
    });

    Route::prefix('approve')->group(function () {
      Route::get('/', [JobsApproveController::class, 'index']);
      Route::post('/', [JobsApproveController::class, 'post']);
      Route::post('/edit', [JobsApproveController::class, 'put']);
      Route::post('/change-approve', [JobsApproveController::class, 'change_approve']);
      Route::get('/delete', [JobsApproveController::class, 'delete']);
    });

    Route::prefix('interview')->group(function () {
      Route::get('/', [JobsInterviewController::class, 'index']);
      Route::post('/', [JobsInterviewController::class, 'post']);
      Route::post('/edit', [JobsInterviewController::class, 'put']);
      Route::get('/delete', [JobsInterviewController::class, 'delete']);
    });

    Route::prefix('check-log')->group(function () {
      Route::get('/', [CheckLogController::class, 'check_log_action']);
      Route::post('/approve', [CheckLogController::class, 'change_approve']);
    });
  });

  Route::prefix('check-log')->group(function () {
    Route::get('/', [CheckLogController::class, 'index']);
    Route::get('/detail', [CheckLogController::class, 'detail']);
    Route::get('/maps', [CheckLogController::class, 'maps']);
    Route::get('/export', [CheckLogController::class, 'export']);
    Route::get('/export/shift', [CheckLogController::class, 'export_shift']);
    Route::get('/export/shift/pdf', [CheckLogController::class, 'export_shift_pdf']);
    Route::post('/', [CheckLogController::class, 'post']);
    Route::post('/edit', [CheckLogController::class, 'put']);
    Route::post('/requested', [CheckLogController::class, 'change_requested']);
    Route::post('/requested/all', [CheckLogController::class, 'change_requested_all']);
    Route::post('/approve', [CheckLogController::class, 'change_approve']);
  });

  Route::prefix('report')->group(function () {
    Route::get('/event', [ReportEventController::class, 'event']);
    Route::get('/monthly', [ReportEventController::class, 'monthly']);
  });

  Route::prefix('salary')->group(function () {
    Route::get('/', [SalaryController::class, 'index']);
    Route::get('/detail', [SalaryController::class, 'detail']);
    Route::prefix('approve')->group(function () {
      Route::post('/salary', [SalaryController::class, 'change_approve_salary']);
      Route::post('/salary/all', [SalaryController::class, 'change_approve_salary_all']);
      Route::post('/additional-salary', [SalaryController::class, 'change_approve_additional_salary']);
    });
    Route::prefix('edit')->group(function () {
      Route::post('/salary', [SalaryController::class, 'edit_salary']);
      Route::post('/additional-salary', [SalaryController::class, 'edit_additional_salary']);
    });
  });

  Route::prefix('export')->group(function () {
    Route::prefix('resume')->group(function () {
      Route::get('/', [ExportController::class, 'resume_pdf']);
    });
  });

  Route::prefix('jobs-recommendation')->group(function () {
    Route::get('/', [JobsRecommendationController::class, 'index']);
    Route::get('/detail', [JobsRecommendationController::class, 'detail']);
    Route::post('/', [JobsRecommendationController::class, 'post']);
    Route::post('/edit', [JobsRecommendationController::class, 'put']);
    Route::get('/delete', [JobsRecommendationController::class, 'delete']);
  });

  Route::prefix('request-withdraw')->group(function () {
    Route::get('/', [RequestWithdrawController::class, 'index']);
    Route::get('/detail', [RequestWithdrawController::class, 'detail']);
    Route::post('/', [RequestWithdrawController::class, 'post']);
    Route::post('/edit', [RequestWithdrawController::class, 'put']);
    Route::post('/change-approve', [RequestWithdrawController::class, 'change_approve']);
    Route::get('/delete', [RequestWithdrawController::class, 'delete']);
  });

  Route::prefix('resume')->group(function () {
    Route::get('/', [ResumeController::class, 'index']);
    Route::get('/detail', [ResumeController::class, 'detail']);
    Route::post('/', [ResumeController::class, 'post']);
    Route::post('/edit', [ResumeController::class, 'put']);
    Route::get('/delete', [ResumeController::class, 'delete']);
  });

  Route::prefix('event')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/action', [EventController::class, 'action']);
    Route::get('/detail', [EventController::class, 'detail']);
    Route::post('/', [EventController::class, 'post']);
    Route::post('/edit', [EventController::class, 'put']);
    Route::get('/delete', [EventController::class, 'delete']);
  });

  Route::prefix('calendar')->group(function () {
    Route::get('/', [CalendarController::class, 'index']);
  });

  Route::prefix('banner')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::get('/multiple', [BannerController::class, 'multiple_action']);
    Route::get('/action', [BannerController::class, 'action']);
    Route::post('/', [BannerController::class, 'post']);
    Route::post('/multiple', [BannerController::class, 'multiple_post']);
    Route::post('/edit', [BannerController::class, 'put']);
    Route::get('/delete', [BannerController::class, 'delete']);
  });

  Route::prefix('notification')->group(function () {
    Route::get('/', [CommunicationController::class, 'index']);
    Route::get('/action', [CommunicationController::class, 'action']);
    Route::get('/detail', [CommunicationController::class, 'detail']);
    Route::post('/', [CommunicationController::class, 'post']);
    Route::post('/edit', [CommunicationController::class, 'put']);
  });

  Route::prefix('user')->group(function () {
    Route::prefix('customer')->group(function () {
      Route::prefix('regular')->group(function () {
        Route::get('/', [CustomerRegularController::class, 'index']);
        Route::get('/detail', [CustomerRegularController::class, 'detail']);
        Route::get('/action', [CustomerRegularController::class, 'action']);
        Route::get('/export', [CustomerRegularController::class, 'export']);
        Route::post('/import', [CustomerRegularController::class, 'import']);
        Route::post('/', [CustomerRegularController::class, 'post']);
        Route::post('/edit', [CustomerRegularController::class, 'put']);
      });

      Route::prefix('oncall')->group(function () {
        Route::get('/', [CustomerOncallController::class, 'index']);
        Route::get('/detail', [CustomerOncallController::class, 'detail']);
        Route::get('/action', [CustomerOncallController::class, 'action']);
        Route::post('/', [CustomerOncallController::class, 'post']);
        Route::post('/edit', [CustomerOncallController::class, 'put']);
      });
    });

    Route::prefix('staff')->group(function () {
      Route::get('/', [StaffController::class, 'index']);
      Route::get('/detail', [StaffController::class, 'detail']);
      Route::get('/action', [StaffController::class, 'action']);
      Route::post('/', [StaffController::class, 'post']);
      Route::post('/edit', [StaffController::class, 'put']);
      Route::post('/delete', [StaffController::class, 'delete']);
    });

    Route::prefix('ro')->group(function () {
      Route::get('/', [ROController::class, 'index']);
      Route::get('/detail', [ROController::class, 'detail']);
      Route::get('/action', [ROController::class, 'action']);
      Route::post('/', [ROController::class, 'post']);
      Route::post('/edit', [ROController::class, 'put']);
      Route::post('/delete', [ROController::class, 'delete']);
    });
  });
});