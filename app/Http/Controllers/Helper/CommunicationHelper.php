<?php
namespace App\Http\Controllers\Helper;

use Mail;
use Curl;
use Auth;
use Carbon\Carbon;
use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

use App\Models\FirebaseToken;
use App\Models\User;
use App\Models\Notification;
use App\Models\Todo;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\IDHelper;
use App\Http\Controllers\Helper\CurlHelper;

// use App\Events\SendNotificationEvent;

class CommunicationHelper{
  private $firebase_token = "AAAAfRQnXhY:APA91bF6iqpBYfWMDHG9EJEp4vSrdvdMpFJ5BW-ydE0ddMyXEh1LIv2cl7qeRi6K-qSA7WbKhEZLxRG6pRyXzeUVncjZ7pB1wQLXObCZiE_yqC3Pl9WHFrIdjwimQTa2NfwyovubNAjq";
  // private $firebase_token = "AAAAV0cI1k4:APA91bGalDzDozyMURtpzaNxi8kFEuW0_fwhgy2oenqUiXbI84lkoBJUjvskSsxpEHDiqRcJ6kH5DZRYFnOJKdQCEEJOtNsQstT7OJEFP3aM0sAqDu522xuP0cQPXHRJOZzLSRl7aIKf";
  private $project_name = 'casual-982ce';
  private $app_name = '';
  private $url_frontend = "";
  private $url_admin = "";

  public function get_oauth_token(){
    $scope = 'https://www.googleapis.com/auth/firebase.messaging';
    $credentials = ApplicationDefaultCredentials::getCredentials($scope);
    $arrAccessToken = $credentials->fetchAuthToken();
    return $arrAccessToken;
  }

  public function send_push_notif_old($user, $title, $body, $payload = [], $is_saved_db = true){
    $id_helper = new IDHelper();
    $base = new BaseController();

    if($is_saved_db){
      $notification = new Notification();
      $notification->id = $id_helper->generate_new_id_with_date('NOTIFICATION', new Notification());
      $notification->user_id = $user->id;
      $notification->title = $title;
      $notification->body = $body;
      $notification->data = json_encode($payload);
      $notification->save();
    }

    // SendNotificationEvent::dispatch($title, $body, $user);

    $page = 1;
    do{
      $offset = ($page - 1) * $base->num_data;
      $arr_firebase_token = FirebaseToken::where('user_id', '=', $user->id)
        ->offset($offset)
        ->limit($base->num_data)
        ->get();


      foreach($arr_firebase_token as $firebase_token){
        $data = [
          'to' => $firebase_token->token,
          "priority" => "high",
          'notification' => [
            "title" => $title,
            "body" => strip_tags($body),
            "sound" => "default",
            "priority" => "high",
          ],
          'data' => [
            "title" => $title,
            "body" => strip_tags($body),
            "payload" => $payload,
            "priority" => "high",
          ]
        ];

        $curl_helper = new CurlHelper();
        $response = $curl_helper->request('https://fcm.googleapis.com/fcm/send', [
          'Authorization: key='.$this->firebase_token,
        ], $data);
      }
      $page++;
    }while(count($arr_firebase_token) > 0);
  }

  // public function send_push_notif($user, $title, $body, $payload = [], $is_saved_db = true){
  //   $this->send_push_notif_old($user, $title, $body, $payload, $is_saved_db);
  // }

  public function send_push_notif($user, $title, $body, $payload = [], $is_saved_db = true){
    $id_helper = new IDHelper();
    $base = new BaseController();
    $arrAccessToken = $this->get_oauth_token();

    if($is_saved_db){
      $notification = new Notification();
      $notification->id = $id_helper->generate_new_id_with_date('NOTIFICATION', new Notification());
      $notification->user_id = $user->id;
      $notification->title = $title;
      $notification->body = $body;
      $notification->data = json_encode($payload);
      $notification->save();
    }

    // SendNotificationEvent::dispatch($title, $body, $user);

    $page = 1;
    do{
      $offset = ($page - 1) * $base->num_data;
      $arr_firebase_token = FirebaseToken::where('user_id', '=', $user->id)
        ->offset($offset)
        ->limit($base->num_data)
        ->get();


      foreach($arr_firebase_token as $firebase_token){
        $arr_data = [
          "title" => $title,
          "message" => strip_tags($body),
        ];
        $arr_data = array_merge($arr_data, $payload);

        $data = [
          "message" => [
            'token' => $firebase_token->token,
            'notification' => [
              "title" => $title,
              "body" => strip_tags($body),
            ],
            "android" => [
              "notification" => [
                "sound" => "default",
              ],
            ],
            'data' => $arr_data,
          ],
        ];

        $curl_helper = new CurlHelper();
        $response = $curl_helper->request('https://fcm.googleapis.com/v1/projects/'.$this->project_name.'/messages:send', [
          'Authorization: '.$arrAccessToken["token_type"].' '.$arrAccessToken["access_token"],
        ], $data);
      }
      $page++;
    }while(count($arr_firebase_token) > 0);
  }

  public function send_test_push_notif_old($token, $title, $body, $payload = [], $is_saved_db = true){
    $id_helper = new IDHelper();
    $base = new BaseController();
    $arrAccessToken = $this->get_oauth_token();

    $data = [
      'to' => $token,
      "priority" => "high",
      'notification' => [
        "title" => $title,
        "body" => strip_tags($body),
        "sound" => "default",
        "priority" => "high",
      ],
      'data' => [
        "title" => $title,
        "body" => strip_tags($body),
        "payload" => $payload,
        "priority" => "high",
      ],
    ];

    $curl_helper = new CurlHelper();
    $response = $curl_helper->request('https://fcm.googleapis.com/fcm/send', [
      'Authorization: key='.$this->firebase_token,
    ], $data);

    if(!empty($response["error"]))
      return $response["error"];
    else
      return ["status" => "success"];
  }

  public function send_test_push_notif($token, $title, $body, $payload = [], $is_saved_db = true){
    $id_helper = new IDHelper();
    $base = new BaseController();
    $arrAccessToken = $this->get_oauth_token();

    $arr_data = [
      "title" => $title,
      "message" => strip_tags($body),
    ];
    $arr_data = array_merge($arr_data, $payload);

    $data = [
      "message" => [
        'token' => $token,
        'notification' => [
          "title" => $title,
          "body" => strip_tags($body),
        ],
        "android" => [
          "notification" => [
            "sound" => "default",
          ],
        ],
        'data' => $arr_data,
      ],
    ];

    $curl_helper = new CurlHelper();
    $response = $curl_helper->request('https://fcm.googleapis.com/v1/projects/'.$this->project_name.'/messages:send', [
      'Authorization: '.$arrAccessToken["token_type"].' '.$arrAccessToken["access_token"],
    ], $data);

    if(!empty($response["error"]))
      return $response["error"];
    else
      return ["status" => "success"];
  }
}
