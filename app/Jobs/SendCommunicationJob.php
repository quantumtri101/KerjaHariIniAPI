<?php
namespace App\Jobs;

use Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Curl;
use Carbon\Carbon;

use App\Models\Communication;
use App\Models\User;

use App\Http\Controllers\BaseController;

class SendCommunicationJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $communication;
  /**
  * Create a new job instance.
  *
  * @return void
  */
  public function __construct($communication){
    $this->communication = $communication;


  }

  private function send_email($communication, $user){
    $base = new BaseController();

    Mail::send('email.notification', [
      'user' => $user,
      'url_image' => asset('public/image/notification.png'),
      'title' => $communication->subject,
      'body' => $communication->message,
      'url_frontend' => $base->url_teacher_frontend,
      'app_name' => $base->app_name,
      'app_address' => $base->app_address,
    ], function ($message) use($user){
      $message->to($user->email)->subject('New Announcement from'.$base->app_name);
    });
  }

  private function send_all_comm($communication){
    $base = new BaseController();
    $page = 1;

    do{
      $offset = ($page - 1) * $base->num_data;
      $arr_user = User::offset($offset)->limit($base->num_data)->get();

      foreach($arr_user as $user){
        if($communication->communication_method->name == "Email")
          $this->send_email($communication, $user);
        else if($communication->communication_method->name == "Push Notification")
          $base->communication_helper->send_push_notif($user, $communication->subject, $communication->message);
      }
      $page++;
    }while(count($arr_user) > 0);
  }

  private function send_personal_comm($communication){
    $base = new BaseController();

    if($communication->communication_method->name == "Email")
      $this->send_email($communication, $communication->user);
    else if($communication->communication_method->name == "Push Notification")
      $base->communication_helper->send_push_notif($communication->user, $communication->subject, $communication->message);
  }

  /**
  * Execute the job.
  *
  * @return void
  */
  public function handle()
  {
    $base = new BaseController();

    $communication = $this->communication;
    $communication->sent_at = Carbon::now();
    $communication->save();
    if($communication->communication_type->name == "Personal")
      $this->send_personal_comm($communication);
    else if($communication->communication_type->name == "Blast")
      $this->send_all_comm($communication);
  }
}
