<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Auth;
use Hash;
use Curl;
use Image;
use Mail;
use Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;

use App\Models\User;
use App\Models\Type;
use App\Models\FirebaseToken;

use App\Http\Controllers\PaymentController;

use App\Jobs\SendEmailAuthJob;
use App\Jobs\SendEmailOperatorJob;
use App\Jobs\SendNotificationJob;

class AuthController extends BaseController{
  public function login(Request $request){
    // $validation = $this->manage_validation($request, [
    //   // 'email' => 'bail|required',
    //   'password' => 'bail|required',
    // ]);
    // if(!empty($validation))
    //   return $this->get_data_helper->return_data($request, $validation, 'back', '');

    $user = User::orWhere('email','=',$request->email)
      ->orWhere('phone','=',$request->email[0] == '0' ? '+62'.substr($request->email, 1) : $request->email)
      ->first();

    if(empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_not_found'),
        'type' => 'email',
      ], 'redirect', '/auth/login');
    // if($user->is_active == 0)
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => __('controller.user_inactive'),
    //     'type' => 'email',
    //   ], 'redirect', '/auth/login');
    if(!empty($request->type)){
      if($request->type == 'web_admin' && ($user->type->name == 'customer')){
        return $this->get_data_helper->return_data($request, [
          'status' => 'error',
          'message' => __('controller.user_cannot_access'),
        ], 'redirect', '/auth/login');
      }
      else if($request->type != 'web_admin' ){
        $type = Type::where('name', 'like', $request->type)->first();
        if($user->type->id != $type->id)
          return $this->get_data_helper->return_data($request, [
            'status' => 'error',
            'message' => __('controller.not_'.$type->name),
          ], 'back', '');
      }
    }

		// if($user->id == 'USER_20230905_000002'){
		// 	$user->password = Hash::make('12345');
		// 	$user->save();
		// }
    // $arr_access_token = $user->tokens()->get();
    // foreach($arr_access_token as $access_token)
    //   $access_token->delete();



    $token = Auth::attempt(['email' => strtolower($request->email), 'password' => $request->password]);
    if(!$token)
      $token = Auth::attempt(['phone' => $request->email[0] == '0' ? '+62'.substr($request->email, 1) : $request->email, 'password' => $request->password]);


    if($token){
      $arr_api = [];
      $redirect = '';

      if($request->expectsJson()){

        $this->add_firebase_token($request, $user);
        $this->relationship_helper->user($user);
        $api_token = Auth::user()->createToken('login');
        $arr_api = [
          'status' => 'success',
          'message' => __('controller.auth_success'),
          'token' => 'Bearer '.$api_token->plainTextToken,
          'user' => $user,
          'type' => $user->type,
        ];
      }
      else{
        // $user = Auth::user();
        // $user->session_id = $request->session()->getId();
        // $user->save();

        $redirect = $request->session()->has('redirect') ? $request->session()->get('redirect') : '/';
        $request->session()->forget('redirect');
      }

      return $this->get_data_helper->return_data($request, $arr_api, 'redirect', $redirect);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'error',
      'message' => __('controller.password_wrong'),
      'type' => 'password',
    ], 'redirect', '/auth/login');
  }

  public function login_social_media(Request $request){
    $user = User::orWhere('email','=',$request->email)->first();
    $auth_type = 'login';

    if(empty($user)){
      $type = Type::where('name','like','customer')->first();
      $password = $this->string_helper->generateRandomString($this->str_length);
      $auth_type = 'register';

      $user = new User();
      $user->email = strtolower($request->email);
      $user->name = $request->name;
      $user->password = Hash::make($password);
      $user->phone = $request->phone;
      if(!empty($request->image))
        $user->file_name = $request->image;
      $user->type_id = $type->id;
      $user->login_via = $request->via;
      $user->save();

      SendEmailAuthJob::dispatch('email.auth.register', [
        'user' => $user,
        'status' => 'Welcome',
        'url_frontend' => '',
        'type' => 'register',
        'app_name' => $this->app_name,
        'password' => '',
      ], $user, 'Registration Staff Process Successful')
        ->onQueue('worker_1')
        ->afterResponse();
    }

    $arr_api = [];
    $token = Auth::login($user);
    $this->relationship_helper->user($user);

    if($request->expectsJson()){
      $this->add_firebase_token($request, $user);
      $api_token = Auth::user()->createToken('login');
      $arr_api = [
        'status' => 'success',
        'token' => 'Bearer '.$api_token->plainTextToken,
        'data' => $user,
        'auth_type' => $auth_type,
      ];
    }

    return $this->get_data_helper->return_data($request, $arr_api);
  }

  private function add_firebase_token($request, $user){
    if(!empty($request->token)){
      // foreach($user->firebase_token as $firebase_token)
      //   $firebase_token->forceDelete();

      $data = new FirebaseToken();
      $data->user_id = $user->id;
      $data->token = $request->token;
      $data->save();
    }
  }

  public function logout(Request $request){
    if(!empty($request->token)){
      $firebase_token = FirebaseToken::where('user_id', '=', Auth::user()->id)
        ->where('token', '=', $request->token)
        ->first();
      if(!empty($firebase_token))
        $firebase_token->forceDelete();
    }

    Auth::logout();
    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/auth/login');
  }

  public function check_phone(Request $request){
    $user = new User();

    $data = User::select($user->get_table_name().'.*')
      // ->orWhere($user->get_table_name().'.email','=',$request->email)
      ->where($user->get_table_name().'.phone','=',$request->phone[0] == "0" ? "+62".substr($request->phone, 1) : $request->phone)
      ->first();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => [
        "is_exist" => !empty($data),
      ],
    ], 'redirect', '/auth/login');
  }

  public function api_logout(Request $request){
    if(!empty($request->token)){
      $arr_token = FirebaseToken::where('user_id', '=', Auth::user()->id);
      $arr_token = $arr_token->where('token', 'like', $request->token);
      $arr_token = $arr_token->get();

      foreach($arr_token as $token)
        $token->delete();
    }
    $arr_access_token = Auth::user()->tokens()->get();
    foreach($arr_access_token as $access_token)
      $access_token->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'back');
  }

  public function register(Request $request){
    $user = new User();

    $data = User::select($user->get_table_name().'.*')
      // ->orWhere($user->get_table_name().'.email','=',$request->email)
      ->orWhere($user->get_table_name().'.phone','like',$request->phone[0] == "0" ? "+62".substr($request->phone, 1) : $request->phone)
      ->first();

    if(!empty($data))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ]);

    // $password = $this->generateRandomString($this->str_length);
    $otp_code = $this->string_helper->generateOTP(5);
    $type = Type::where('name','like','customer_oncall')->first();

    $user = new User();
    if(!empty($request->email))
      $user->email = strtolower($request->email);
    $user->password = Hash::make($request->password);
    $user->type_id = $type->id;
    $user->name = $request->name;
    $user->gender = $request->gender;
    $user->is_active = 0;
    $user->phone = $request->phone[0] == "0" ? "+62".substr($request->phone, 1) : $request->phone;
    $user->save();

    if(!empty($avatar))
      $this->file_helper->manage_image($file, $user, 'user', 'file_name');
    $user->save();

    if(!empty($user->email))
      SendEmailAuthJob::dispatch('email.auth.register', [
        'user' => $user,
        'status' => 'Welcome',
        'url_frontend' => '',
        'type' => 'register',
        'app_name' => $this->app_name,
        'password' => $request->password,
      ], $user, 'Registration Staff Process Successful')
        ->onQueue('worker_1')
        ->afterResponse();

    $arr_api = [];
    $token = Auth::login($user);

    if($request->expectsJson()){
      $this->add_firebase_token($request, $user);
      $this->relationship_helper->user($user);
      $api_token = Auth::user()->createToken('login');
      $arr_api = [
        'status' => 'success',
        'token' => 'Bearer '.$api_token->plainTextToken,
        'data' => $user,
      ];
    }

    return $this->get_data_helper->return_data($request, $arr_api, 'redirect', '/');
  }

  public function forget_password(Request $request){
    // $validation = $this->manage_validation($request, [
    //   'email' => 'bail|required',
    // ]);
    // if(!empty($validation))
    //   return $this->get_data_helper->return_data($request, $validation, 'back', '');

    $user = User::where('phone', 'like', $request->phone[0] == "0" ? "+62".substr($request->phone, 1) : $request->phone)->first();

    if(empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_not_found'),
      ], 'back', '');

    $password = $this->string_helper->generateRandomString($this->str_length);

    $user->password = Hash::make($password);
    $user->save();

    // SendEmailAuthJob::dispatch('email.auth.forget_password_v1', [
    //   'user' => $user,
    //   'status' => 'Forget Password',
    //   'url_frontend' => '',
    //   'type' => 'register',
    //   'app_name' => $this->app_name,
    //   'password' => $password,
    // ], $user, 'Forget Password')
    //   ->onQueue('worker_1')
    //   ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'password' => $password,
    ], 'redirect', '/auth/login');
  }

  public function change_password(Request $request){
    $validation = $this->manage_validation($request, [
      // 'old_password' => 'bail|required',
      'new_password' => 'bail|required',
    ]);
    if(!empty($validation))
      return $this->get_data_helper->return_data($request, $validation, 'back', '');

    if(Auth::check())
      $user = Auth::user();
    else
      $user = !empty($request->phone) ? User::where('phone', 'like', $request->phone[0] == "0" ? "+62".substr($request->phone, 1) : $request->phone)->first() : User::find($request->id);

    if(empty($user))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_not_found'),
      ], 'back', '');

    // if(empty($request->phone) && !Hash::check($request->old_password, $user->password))
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => __('controller.old_password_incorrect'),
    //   ], 'back', '');

    $user->password = Hash::make($request->new_password);
    $user->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/', [
      "message_info" => "Change Password Successfully",
    ]);
  }

  public function change_active(Request $request){
    $user = User::find($request->id);
    $user->is_active = $user->is_active == 1 ? 0 : 1;
    $user->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'back');
  }

  public function reset_password(Request $request){
    $password = $this->string_helper->generateRandomString($this->str_length);

    // dd($password);
    $user = User::find($request->id);
    $user->password = Hash::make($password);
    $user->save();

    if(!empty($user->email))
      SendEmailAuthJob::dispatch('email.auth.reset_password_v1', [
        'user' => $user,
        'status' => 'Reset Password',
        'url_frontend' => '',
        'type' => 'register',
        'app_name' => $this->app_name,
        'app_address' => '',
        'password' => $password,
      ], $user, 'Reset Password')
        ->onQueue('worker_1')
        ->afterResponse();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'password' => $password,
    ], 'back', '', [
      "message" => "Reset Password Successfully",
    ]);
  }

  public function send_otp(Request $request){
    $user = Auth::user();
    if(!empty($request->phone))
      $user->temp_phone = $request->phone[0] == "0" ? "+62".substr($request->phone, 1) : $request->phone;
    $user->save();
    $otp_code = $this->string_helper->generateOTP(6);

    $this->send_sms_helper->send_otp($user, __('controller.otp_code_message', ['otp_code' => $otp_code]), $otp_code);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'otp_code' => $user->otp_code,
    ]);
  }

  public function confirm_otp(Request $request){
    // $validation = $this->manage_validation($request, [
    //   // 'otp_code' => 'bail|required',
    // ]);
    // if(!empty($validation))
    //   return $this->get_data_helper->return_data($request, $validation, 'back', '');

    $user = Auth::check() ? Auth::user() : User::where('phone', 'like', $request->phone)->first();

    // if($request->otp_code != $user->otp_code)
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => __('controller.otp_code_wrong'),
    //   ], 'back', '');

    // $user->phone = $user->temp_phone;
    $user->phone_verified_at = Carbon::now();
    $user->is_active = 1;
    // $user->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ]);
  }

  public function change_profile(Request $request){
    $data = User::select('*')
      ->where('phone','like',$request->phone[0] == "0" ? "+62".substr($request->phone, 1) : $request->phone)
      ->where('id', '!=', Auth::user()->id)
      ->first();

    if(!empty($data))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => __('controller.user_already_exist'),
      ]);
    // dd($request->all());

    $user = Auth::user();
    $user->email = $request->email;
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->id_no = $request->id_no;
    if(isset($request->gender))
      $user->gender = $request->gender;
    if(!empty($request->birth_date))
      $user->birth_date = Carbon::createFromFormat('d/m/Y', $request->birth_date);
    if(!empty($request->image))
      $this->file_helper->manage_image($request->image, $user, 'user', 'file_name');
    $user->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/');
  }

  public function get_profile(Request $request){
    $auth = Auth::user();
    $this->relationship_helper->user($auth, $request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $auth,
    ], 'redirect', '/');
  }
}
