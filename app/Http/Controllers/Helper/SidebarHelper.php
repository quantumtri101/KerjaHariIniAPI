<?php
namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Models\Announcement;
use App\Models\AcademicYear;
use App\Models\User;

class SidebarHelper{
  private $arr = [
    [
      "id" => 'dashboard',
      "name" => 'general.dashboard',
      "icon" => 'fa-tachometer-alt',
      "href" => '/',
      "url" => '/',
    ],
    [
      "id" => 'banner',
      "name" => 'general.banner',
      "icon" => 'fa-tachometer-alt',
      "href" => '/banner',
      "url" => 'banner',
      "roles" => ["admin",],
    ],
    
    [
      "id" => 'master',
      "name" => 'general.master',
      "icon" => 'fa-tachometer-alt',
      "href" => '#master',
      "url" => 'master',
      "roles" => ["admin",],
      "arr" => [
        [
          "id" => 'category',
          "name" => 'general.category',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/category',
          "url" => 'master/category',
          "roles" => ["admin", ],
        ],
        [
          "id" => 'sub_category',
          "name" => 'general.sub_category',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/sub-category',
          "url" => 'master/sub-category',
          "roles" => ["admin", ],
        ],
        [
          "id" => 'skill',
          "name" => 'general.skill',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/skill',
          "url" => 'master/skill',
          "roles" => ["admin", ],
        ],
        [
          "id" => 'range_salary',
          "name" => 'general.range_salary',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/range-salary',
          "url" => 'master/range-salary',
          "roles" => ["admin", ],
        ],
        [
          "id" => 'bank',
          "name" => 'general.bank',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/bank',
          "url" => 'master/bank',
          "roles" => ["admin", ],
        ],
        [
          "id" => 'company',
          "name" => 'general.company',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/company',
          "url" => 'master/company',
          "roles" => ["admin", ],
        ],
        [
          "id" => 'company_position',
          "name" => 'general.company_position',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/company-position',
          "url" => 'master/company-position',
          "roles" => ["admin", ],
        ],
        [
          "id" => 'term_condition',
          "name" => 'general.term_condition',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/term-condition',
          "url" => 'master/term-condition',
          "roles" => ["admin", ],
        ],
        [
          "id" => 'privacy_policy',
          "name" => 'general.privacy_policy',
          "icon" => 'fa-tachometer-alt',
          "href" => '/master/privacy',
          "url" => 'master/privacy',
          "roles" => ["admin", ],
        ],
        // [
        //   "id" => 'education',
        //   "name" => 'general.education',
        //   "icon" => 'fa-tachometer-alt',
        //   "href" => '/master/education',
        //   "url" => 'master/education',
        // ],
      ],
    ],
    [
      "id" => 'event',
      "name" => 'general.event',
      "icon" => 'fa-tachometer-alt',
      "href" => '/event',
      "url" => 'event',
      "roles" => ["admin", "RO", "staff", ],
    ],
    [
      "id" => 'calendar',
      "name" => 'general.calendar',
      "icon" => 'fa-tachometer-alt',
      "href" => '/calendar',
      "url" => 'calendar',
      "roles" => ["admin", "RO", "staff", ],
    ],
    [
      "id" => 'jobs',
      "name" => 'general.jobs',
      "icon" => 'fa-tachometer-alt',
      "href" => '/jobs',
      "url" => 'jobs',
      "roles" => ["admin", "RO", "staff", ],
    ],
    [
      "id" => 'check_log',
      "name" => 'general.check_log',
      "icon" => 'fa-tachometer-alt',
      "href" => '/check-log',
      "url" => 'check-log',
      "roles" => ["admin", "RO", "staff",],
    ],
    [
      "id" => 'salary_verification',
      "name" => 'general.salary_verification',
      "icon" => 'fa-tachometer-alt',
      "href" => '/salary',
      "url" => 'salary',
      "roles" => ["admin", "RO", "staff",],
    ],
    [
      "id" => 'general_quiz',
      "name" => 'general.general_quiz',
      "icon" => 'fa-tachometer-alt',
      "href" => '/general-quiz',
      "url" => 'general-quiz',
      "roles" => ["admin", ],
    ],
    [
      "id" => 'request_withdraw',
      "name" => 'general.request_withdraw',
      "icon" => 'fa-tachometer-alt',
      "href" => '/request-withdraw',
      "url" => 'request-withdraw',
      "roles" => ["admin", "RO", ],
    ],
    [
      "id" => 'report',
      "name" => 'general.report',
      "icon" => 'fa-tachometer-alt',
      "href" => '#report',
      "url" => 'report',
      "roles" => [],
      "arr" => [
        [
          "id" => 'event',
          "name" => 'general.event',
          "icon" => 'fa-tachometer-alt',
          "href" => '/report/event',
          "url" => 'report/event',
          "roles" => [],
        ],
        [
          "id" => 'monthly',
          "name" => 'general.monthly',
          "icon" => 'fa-tachometer-alt',
          "href" => '/report/monthly',
          "url" => 'report/monthly',
          "roles" => [],
        ],
      ],
    ],
    [
      "id" => 'user',
      "name" => 'general.user',
      "icon" => 'fa-tachometer-alt',
      "href" => '#user',
      "url" => 'user',
      "roles" => ["admin", "RO", ],
      "arr" => [
        [
          "id" => 'customer_regular',
          "name" => 'general.customer_regular',
          "icon" => 'fa-tachometer-alt',
          "href" => '/user/customer/regular',
          "url" => 'user/customer/regular',
          "roles" => ["admin", "RO", ],
        ],
        [
          "id" => 'customer_oncall',
          "name" => 'general.customer_oncall',
          "icon" => 'fa-tachometer-alt',
          "href" => '/user/customer/oncall',
          "url" => 'user/customer/oncall',
          "roles" => ["admin", "RO", ],
        ],
        [
          "id" => 'staff',
          "name" => 'general.staff',
          "icon" => 'fa-tachometer-alt',
          "href" => '/user/staff',
          "url" => 'user/staff',
          "roles" => ["admin", "RO", ],
        ],
        [
          "id" => 'ro',
          "name" => 'general.ro',
          "icon" => 'fa-tachometer-alt',
          "href" => '/user/ro',
          "url" => 'user/ro',
          "roles" => ["admin", ],
        ],
      ],
    ],
  ];

  public function manage_href($arr){
    foreach($arr as $key => $data){
      if(!empty($data["arr"]))
        $arr[$key]["arr"] = $this->manage_href($data["arr"]);
      else
        $arr[$key]["href"] = url($data["href"]);
    }
    return $arr;
  }

  public function manage_role($arr){
    $arr_temp = [];
    foreach($arr as $key => $data){
      $allow_roles = false;
      if(Auth::check()){
        if(!empty($data["roles"])){
          foreach($data["roles"] as $roles){
            if($roles == Auth::user()->type->name){
              $allow_roles = true;
              break;
            }
          }
        }
        else
          $allow_roles = true;
      }

      if(!$allow_roles)
        continue;
      else{
        if(!empty($data["arr"]))
          $data["arr"] = $this->manage_role($data["arr"]);
        array_push($arr_temp, $data);
      }
      
    }
    
    
    return $arr_temp;
  }

  public function get_arr_sidebar($request){
    $arr = $this->arr;
    $arr = $this->manage_href($arr);
    $arr = $this->manage_role($arr);

    // foreach($arr as $key => $data){
    //   if(!empty($data['arr'])){
    //     foreach($data['arr'] as $key1 => $data1){
    //       if($data1['id'] == 'user_operator' && Auth::user()->type->name == 'operator'){
    //         array_splice($data, $key1, 1);
    //         break;
    //       }
    //     }
    //   }
    // }

    return [
      'arr_sidebar' => $arr,
      'json_arr_sidebar' => json_encode($arr),
    ];
  }
}
