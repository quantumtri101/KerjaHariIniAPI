<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Notification;

class DeleteNotification extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'delete:notification';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'To delete notification in specific time';
  private $base;

  /**
  * Create a new command instance.
  *
  * @return void
  */
  public function __construct()
  {
    parent::__construct();
    $this->base = new BaseController();
  }

  /**
  * Execute the console command.
  *
  * @return int
  */
  public function handle()
  {
    $page = 1;
    do{
      $offset = ($page - 1) * $this->base->num_data;
      $arr_notification = Notification::where('created_at','<=',Carbon::now()->subMonth()->formatLocalized('%Y-%m-%d').'%')
        ->offset($offset)
        ->limit($this->base->num_data)
        ->get();

      foreach($arr_notification as $notification)
        $notification->delete();
      $page++;
    } while(count($arr_notification) > 0);
  }
}
