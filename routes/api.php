<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\SalaryTransactionController;
use App\Http\Controllers\RequestWithdrawController;
use App\Http\Controllers\CheckLogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RatingController;

use App\Http\Controllers\Jobs\JobsController;
use App\Http\Controllers\Jobs\JobsApplicationSalaryController;
use App\Http\Controllers\Jobs\JobsApplicationController;
use App\Http\Controllers\Jobs\JobsAppliedController;
use App\Http\Controllers\Jobs\JobsBriefingController;
use App\Http\Controllers\Jobs\JobsInterviewController;
use App\Http\Controllers\Jobs\JobsQualificationController;
use App\Http\Controllers\Jobs\JobsRecommendationController;
use App\Http\Controllers\Jobs\JobsSalaryController;
use App\Http\Controllers\Jobs\JobsApproveController;
use App\Http\Controllers\Jobs\JobsDocumentController;
use App\Http\Controllers\Jobs\JobsShiftController;
use App\Http\Controllers\Jobs\JobsApproveCheckLogController;
use App\Http\Controllers\Jobs\JobsApproveSalaryController;
use App\Http\Controllers\Jobs\JobsImageController;

use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\ChatRoomController;

use App\Http\Controllers\GeneralQuiz\GeneralQuizAnswerController;
use App\Http\Controllers\GeneralQuiz\GeneralQuizOptionController;
use App\Http\Controllers\GeneralQuiz\GeneralQuizQuestionController;
use App\Http\Controllers\GeneralQuiz\GeneralQuizResultController;

use App\Http\Controllers\Resume\ResumeController;
use App\Http\Controllers\Resume\ResumeSkillController;
use App\Http\Controllers\Resume\ExperienceController;

use App\Http\Controllers\Report\ReportEventController;

use App\Http\Controllers\Master\CityController;
use App\Http\Controllers\Master\ProvinceController;
use App\Http\Controllers\Master\CountryController;
use App\Http\Controllers\Master\TermConditionController;
use App\Http\Controllers\Master\PrivacyController;
use App\Http\Controllers\Master\PaymentMethodController;
use App\Http\Controllers\Master\TypeController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\SubCategoryController;
use App\Http\Controllers\Master\BannerController;
use App\Http\Controllers\Master\RangeSalaryController;
use App\Http\Controllers\Master\SkillController;
use App\Http\Controllers\Master\BankController;
use App\Http\Controllers\Master\CompanyController;
use App\Http\Controllers\Master\EducationController;
use App\Http\Controllers\Master\CompanyPositionController;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CustomerOncallController;
use App\Http\Controllers\User\CustomerRegularController;
use App\Http\Controllers\User\ROController;
use App\Http\Controllers\User\StaffController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
  Route::post('/login', [AuthController::class, 'login']);
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login/social-media', [AuthController::class, 'login_social_media']);
  Route::post('/forget-password', [AuthController::class, 'forget_password']);
  Route::post('/change-password', [AuthController::class, 'change_password']);
  Route::post('/otp/confirm', [AuthController::class, 'confirm_otp']);

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'api_logout']);
    Route::post('/change-profile', [AuthController::class, 'change_profile']);
    
    
    Route::post('/change-active', [AuthController::class, 'change_active']);
    Route::get('/profile', [AuthController::class, 'get_profile']);
  });
});

Route::get('/city/all', [CityController::class, 'get_all']);
Route::get('/province/all', [ProvinceController::class, 'get_all']);
Route::get('/country/all', [CountryController::class, 'get_all']);
Route::get('/payment-method/all', [PaymentMethodController::class, 'get_all']);
Route::get('/payment-method/group/all', [PaymentMethodController::class, 'index_group']);
Route::get('/type/all', [TypeController::class, 'get_all']);
Route::get('/category/all', [CategoryController::class, 'get_all']);
Route::get('/sub-category/all', [SubCategoryController::class, 'get_all']);
Route::get('/banner/all', [BannerController::class, 'get_all']);
Route::get('/range-salary/all', [RangeSalaryController::class, 'get_all']);
Route::get('/bank/all', [BankController::class, 'get_all']);
Route::get('/company/all', [CompanyController::class, 'get_all']);
Route::get('/company/position/all', [CompanyPositionController::class, 'get_all']);
Route::get('/education/all', [EducationController::class, 'get_all']);
Route::get('/skill/all', [SkillController::class, 'get_all']);
Route::get('/notification/all', [NotificationController::class, 'get_all']);

Route::get('/city', [CityController::class, 'index']);
Route::prefix('term-condition')->group(function () {
  Route::get('/', [TermConditionController::class, 'index']);
});

Route::prefix('privacy')->group(function () {
  Route::get('/', [PrivacyController::class, 'index']);
});

Route::prefix('payment/xendit')->group(function () {
  Route::post('/simulate', [PaymentController::class, 'simulate_payment']);
});

Route::prefix('maps')->group(function () {
  Route::get('/location-detail', [MapsController::class, 'get_location_detail']);
});

Route::post('/communication/test-send', [CommunicationController::class, 'test_send_notification']);

Route::middleware(['auth:sanctum'])->group(function () {
  Route::prefix('communication')->group(function () {
    Route::get('/', [CommunicationController::class, 'index']);
    Route::post('/', [CommunicationController::class, 'post']);
    Route::post('/edit', [CommunicationController::class, 'put']);
    Route::get('/delete', [CommunicationController::class, 'delete']);
  });

  Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'post']);
    Route::post('/edit', [CategoryController::class, 'put']);
    Route::get('/delete', [CategoryController::class, 'delete']);
  });

  Route::prefix('sub-category')->group(function () {
    Route::get('/', [SubCategoryController::class, 'index']);
    Route::post('/', [SubCategoryController::class, 'post']);
    Route::post('/edit', [SubCategoryController::class, 'put']);
    Route::get('/delete', [SubCategoryController::class, 'delete']);
  });

  Route::prefix('range-salary')->group(function () {
    Route::get('/', [RangeSalaryController::class, 'index']);
    Route::post('/', [RangeSalaryController::class, 'post']);
    Route::post('/edit', [RangeSalaryController::class, 'put']);
    Route::get('/delete', [RangeSalaryController::class, 'delete']);
  });

  Route::prefix('skill')->group(function () {
    Route::get('/', [SkillController::class, 'index']);
    Route::post('/', [SkillController::class, 'post']);
    Route::post('/edit', [SkillController::class, 'put']);
    Route::get('/delete', [SkillController::class, 'delete']);
  });

  Route::prefix('bank')->group(function () {
    Route::get('/', [BankController::class, 'index']);
    Route::post('/', [BankController::class, 'post']);
    Route::post('/edit', [BankController::class, 'put']);
    Route::get('/delete', [BankController::class, 'delete']);
  });

  Route::prefix('company')->group(function () {
    Route::get('/', [CompanyController::class, 'index']);
    Route::post('/', [CompanyController::class, 'post']);
    Route::post('/edit', [CompanyController::class, 'put']);
    Route::get('/delete', [CompanyController::class, 'delete']);
  });

  Route::prefix('event')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::post('/', [EventController::class, 'post']);
    Route::post('/edit', [EventController::class, 'put']);
    Route::get('/delete', [EventController::class, 'delete']);
  });

  Route::prefix('notification')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/total-read', [NotificationController::class, 'get_total_unread']);
    Route::post('/', [NotificationController::class, 'post']);
    Route::post('/edit', [NotificationController::class, 'put']);
    Route::post('/read', [NotificationController::class, 'set_read']);
    Route::get('/delete', [NotificationController::class, 'delete']);
  });

  Route::prefix('report')->group(function () {
    Route::get('/event', [ReportEventController::class, 'event']);
    Route::get('/monthly', [ReportEventController::class, 'monthly']);
  });

  Route::prefix('city')->group(function () {
    
    Route::post('/', [CityController::class, 'post']);
    Route::post('/edit', [CityController::class, 'put']);
    Route::get('/delete', [CityController::class, 'delete']);
  });

  Route::prefix('education')->group(function () {
    Route::get('/', [EducationController::class, 'index']);
    Route::post('/', [EducationController::class, 'post']);
    Route::post('/edit', [EducationController::class, 'put']);
    Route::get('/delete', [EducationController::class, 'delete']);
  });

  Route::prefix('company-position')->group(function () {
    Route::get('/', [CompanyPositionController::class, 'index']);
    Route::post('/', [CompanyPositionController::class, 'post']);
    Route::post('/edit', [CompanyPositionController::class, 'put']);
    Route::get('/delete', [CompanyPositionController::class, 'delete']);
  });

  Route::prefix('banner')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::post('/', [BannerController::class, 'post']);
    Route::post('/edit', [BannerController::class, 'put']);
    Route::get('/delete', [BannerController::class, 'delete']);
  });

  Route::prefix('rating')->group(function () {
    Route::get('/', [RatingController::class, 'index']);
    Route::post('/', [RatingController::class, 'post']);
  });

  Route::prefix('check-log')->group(function () {
    Route::get('/', [CheckLogController::class, 'index']);
    Route::post('/action', [CheckLogController::class, 'check_log_action']);
    Route::post('/', [CheckLogController::class, 'post']);
    Route::post('/approve', [CheckLogController::class, 'change_approve']);
    Route::post('/approve/all', [CheckLogController::class, 'change_approve_all']);
    Route::post('/edit', [CheckLogController::class, 'put']);
    Route::get('/delete', [CheckLogController::class, 'delete']);
  });

  Route::prefix('resume')->group(function () {
    Route::get('/', [ResumeController::class, 'index']);
    Route::post('/', [ResumeController::class, 'post']);
    Route::post('/edit', [ResumeController::class, 'put']);
    Route::get('/delete', [ResumeController::class, 'delete']);

    Route::prefix('skill')->group(function () {
      Route::get('/', [ResumeSkillController::class, 'index']);
      Route::post('/', [ResumeSkillController::class, 'post']);
      Route::post('/edit', [ResumeSkillController::class, 'put']);
      Route::get('/delete', [ResumeSkillController::class, 'delete']);
    });
  });

  Route::prefix('experience')->group(function () {
    Route::get('/', [ExperienceController::class, 'index']);
    Route::post('/', [ExperienceController::class, 'post']);
    Route::post('/edit', [ExperienceController::class, 'put']);
    Route::get('/delete', [ExperienceController::class, 'delete']);
  });

  Route::prefix('general-quiz')->group(function () {
    Route::prefix('option')->group(function () {
      Route::get('/', [GeneralQuizOptionController::class, 'index']);
      Route::post('/', [GeneralQuizOptionController::class, 'post']);
      Route::post('/edit', [GeneralQuizOptionController::class, 'put']);
      Route::get('/delete', [GeneralQuizOptionController::class, 'delete']);
    });

    Route::prefix('answer')->group(function () {
      Route::get('/', [GeneralQuizAnswerController::class, 'index']);
      Route::post('/', [GeneralQuizAnswerController::class, 'post']);
      Route::post('/edit', [GeneralQuizAnswerController::class, 'put']);
      Route::get('/delete', [GeneralQuizAnswerController::class, 'delete']);
    });

    Route::prefix('question')->group(function () {
      Route::get('/', [GeneralQuizQuestionController::class, 'index']);
      Route::post('/', [GeneralQuizQuestionController::class, 'post']);
      Route::post('/edit', [GeneralQuizQuestionController::class, 'put']);
      Route::get('/delete', [GeneralQuizQuestionController::class, 'delete']);
    });

    Route::prefix('result')->group(function () {
      Route::get('/', [GeneralQuizResultController::class, 'index']);
      Route::post('/', [GeneralQuizResultController::class, 'post']);
      Route::get('/delete', [GeneralQuizResultController::class, 'delete']);
    });
  });

  Route::prefix('jobs')->group(function () {
    Route::get('/', [JobsController::class, 'index']);
    Route::post('/', [JobsController::class, 'post']);
    Route::post('/edit', [JobsController::class, 'put']);
    Route::get('/delete', [JobsController::class, 'delete']);

    Route::prefix('application')->group(function () {
      Route::get('/', [JobsApplicationController::class, 'index']);
      Route::post('/', [JobsApplicationController::class, 'post']);
      Route::post('/edit', [JobsApplicationController::class, 'put']);
      Route::post('/change-approve-worker', [JobsApplicationController::class, 'change_approve_worker']);
      Route::get('/delete', [JobsApplicationController::class, 'delete']);

      Route::prefix('salary')->group(function () {
        Route::get('/', [JobsApplicationSalaryController::class, 'index']);
        Route::post('/', [JobsApplicationSalaryController::class, 'post']);
        Route::post('/edit', [JobsApplicationSalaryController::class, 'put']);
        Route::get('/delete', [JobsApplicationSalaryController::class, 'delete']);
      });
    });

    Route::prefix('applied')->group(function () {
      Route::get('/', [JobsAppliedController::class, 'index']);
      Route::post('/', [JobsAppliedController::class, 'post']);
      Route::post('/edit', [JobsAppliedController::class, 'put']);
      Route::get('/delete', [JobsAppliedController::class, 'delete']);
    });

    Route::prefix('document')->group(function () {
      Route::get('/', [JobsDocumentController::class, 'index']);
      Route::post('/', [JobsDocumentController::class, 'post']);
      Route::post('/edit', [JobsDocumentController::class, 'put']);
      Route::get('/delete', [JobsDocumentController::class, 'delete']);
    });

    Route::prefix('shift')->group(function () {
      Route::get('/', [JobsShiftController::class, 'index']);
      Route::post('/', [JobsShiftController::class, 'post']);
      Route::post('/edit', [JobsShiftController::class, 'put']);
      Route::get('/delete', [JobsShiftController::class, 'delete']);
    });

    Route::prefix('briefing')->group(function () {
      Route::get('/', [JobsBriefingController::class, 'index']);
      Route::post('/', [JobsBriefingController::class, 'post']);
      Route::post('/edit', [JobsBriefingController::class, 'put']);
      Route::get('/delete', [JobsBriefingController::class, 'delete']);
    });

    Route::prefix('interview')->group(function () {
      Route::get('/', [JobsInterviewController::class, 'index']);
      Route::post('/', [JobsInterviewController::class, 'post']);
      Route::post('/edit', [JobsInterviewController::class, 'put']);
      Route::get('/delete', [JobsInterviewController::class, 'delete']);
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

    Route::prefix('recommendation')->group(function () {
      Route::get('/', [JobsRecommendationController::class, 'index']);
      Route::post('/', [JobsRecommendationController::class, 'post']);
      Route::post('/edit', [JobsRecommendationController::class, 'put']);
      Route::get('/delete', [JobsRecommendationController::class, 'delete']);
    });

    Route::prefix('salary')->group(function () {
      Route::get('/', [JobsSalaryController::class, 'index']);
      Route::post('/', [JobsSalaryController::class, 'post']);
      Route::post('/edit', [JobsSalaryController::class, 'put']);
      Route::get('/delete', [JobsSalaryController::class, 'delete']);
    });

    Route::prefix('approve')->group(function () {
      Route::get('/', [JobsApproveController::class, 'index']);
      Route::post('/', [JobsApproveController::class, 'post']);
      Route::post('/change-approve', [JobsApproveController::class, 'change_approve']);
      Route::post('/edit', [JobsApproveController::class, 'put']);
      Route::get('/delete', [JobsApproveController::class, 'delete']);

      Route::prefix('check-log')->group(function () {
        Route::get('/', [JobsApproveCheckLogController::class, 'index']);
        Route::post('/', [JobsApproveCheckLogController::class, 'post']);
        Route::post('/edit', [JobsApproveCheckLogController::class, 'put']);
        Route::get('/delete', [JobsApproveCheckLogController::class, 'delete']);
      });

      Route::prefix('salary')->group(function () {
        Route::get('/', [JobsApproveSalaryController::class, 'index']);
        Route::post('/', [JobsApproveSalaryController::class, 'post']);
        Route::post('/edit', [JobsApproveSalaryController::class, 'put']);
        Route::get('/delete', [JobsApproveSalaryController::class, 'delete']);
      });
    });
  });

  Route::prefix('request-withdraw')->group(function () {
    Route::get('/', [RequestWithdrawController::class, 'index']);
    Route::post('/', [RequestWithdrawController::class, 'post']);
    Route::post('/edit', [RequestWithdrawController::class, 'put']);
    Route::get('/delete', [RequestWithdrawController::class, 'delete']);
  });

  Route::prefix('chat')->group(function () {
    Route::get('/', [ChatController::class, 'index']);

    Route::prefix('room')->group(function () {
      Route::get('/', [ChatRoomController::class, 'index']);
      Route::post('/', [ChatRoomController::class, 'post']);
      Route::put('/read', [ChatRoomController::class, 'set_read']);
    });
  });

  Route::prefix('transaction')->group(function () {
    Route::prefix('salary')->group(function () {
      Route::get('/', [SalaryTransactionController::class, 'index']);
      Route::post('/', [SalaryTransactionController::class, 'post']);
      Route::post('/edit', [SalaryTransactionController::class, 'put']);
      Route::get('/delete', [SalaryTransactionController::class, 'delete']);
    });
  });

  Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'post']);
    Route::post('/edit', [UserController::class, 'put']);
    Route::get('/delete', [UserController::class, 'delete']);

    Route::prefix('customer')->group(function () {
      Route::prefix('regular')->group(function () {
        Route::get('/', [CustomerRegularController::class, 'index']);
        Route::post('/', [CustomerRegularController::class, 'post']);
        Route::post('/edit', [CustomerRegularController::class, 'put']);
        Route::get('/delete', [CustomerRegularController::class, 'delete']);
      });

      Route::prefix('oncall')->group(function () {
        Route::get('/', [CustomerOncallController::class, 'index']);
        Route::post('/', [CustomerOncallController::class, 'post']);
        Route::post('/edit', [CustomerOncallController::class, 'put']);
        Route::get('/delete', [CustomerOncallController::class, 'delete']);
      });
    });

    Route::prefix('ro')->group(function () {
      Route::get('/', [ROController::class, 'index']);
      Route::post('/', [ROController::class, 'post']);
      Route::post('/edit', [ROController::class, 'put']);
      Route::get('/delete', [ROController::class, 'delete']);
    });

    Route::prefix('staff')->group(function () {
      Route::get('/', [StaffController::class, 'index']);
      Route::post('/', [StaffController::class, 'post']);
      Route::post('/edit', [StaffController::class, 'put']);
      Route::get('/delete', [StaffController::class, 'delete']);
    });
  });
});
