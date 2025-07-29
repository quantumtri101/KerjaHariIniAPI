<?php
namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\Helper\CommunicationHelper;

class SendPushNotificationJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $title;
  protected $body;
  protected $user;
  /**
  * Create a new job instance.
  *
  * @return void
  */
  public function __construct($user, $title, $body)
  {
    $this->title = $title;
    $this->body = $body;
    $this->user = $user;
  }

  /**
  * Execute the job.
  *
  * @return void
  */
  public function handle()
  {
    $helper = new CommunicationHelper();
    $helper->send_push_notif($this->user, $this->title, $this->body);
  }
}
