<?php
namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Setting;
use App\Models\User;

use App\Http\Controllers\BaseController;

class SendEmailAuthJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $template;
  protected $arr;
  protected $user;
  protected $subject;
  /**
  * Create a new job instance.
  *
  * @return void
  */
  public function __construct($template, $arr, $user, $subject)
  {
    $this->template = $template;
    $this->arr = $arr;
    $this->user = $user;
    $this->subject = $subject;

    $base = new BaseController();

    // $setting = Setting::first();
    // if($setting->send_email_user == 1){
      $user = $this->user;
      $subject = $this->subject;
      Mail::send($this->template, $this->arr, function ($message) use($user, $subject){
        $message->to($user->email)->subject($subject);
      });
    // }
  }

  /**
  * Execute the job.
  *
  * @return void
  */
  public function handle()
  {
    // $base = new BaseController();

    // // $setting = Setting::first();
    // // if($setting->send_email_user == 1){
    //   $user = $this->user;
    //   $subject = $this->subject;
    //   Mail::send($this->template, $this->arr, function ($message) use($user, $subject){
    //     $message->to($user->email)->subject($subject);
    //   });
    // // }
  }
}
