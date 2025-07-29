<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\EndpointLog;
use App\Models\CurlLog;

class DeleteLog extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'delete:log';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'To delete log in specific time';
  private $base;
  private $interval_day = 5;
  private $max_data = 1000;

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

  public function remove_curl_log(){
    $total_log = CurlLog::selectRaw('COUNT(id) as total')->first();

    $offset = ($page - 1) * $this->base->num_data;
    if($total_log->total <= $this->max_data)
      $arr_log = CurlLog::withTrashed()
        ->where('created_at','<=',Carbon::now()->subDays($this->interval_day)->formatLocalized('%Y-%m-%d').'%')
        ->get();
    else
      $arr_log = CurlLog::withTrashed()
        ->orderBy('created_at', 'asc')
        ->get();

    foreach($arr_log as $log)
      $log->forceDelete();
  }

  public function remove_log(){
      $total_log = EndpointLog::selectRaw('COUNT(id) as total')->first();

      $offset = ($page - 1) * $this->base->num_data;
      if($total_log->total <= $this->max_data)
        $arr_log = EndpointLog::withTrashed()
          ->where('created_at','<=',Carbon::now()->subDays($this->interval_day)->formatLocalized('%Y-%m-%d').'%')
          ->get();
      else
        $arr_log = EndpointLog::withTrashed()
          ->orderBy('created_at', 'asc')
          ->get();

      foreach($arr_log as $log)
        $log->forceDelete();
  }

  /**
  * Execute the console command.
  *
  * @return int
  */
  public function handle()
  {
    $this->remove_log();
    $this->remove_curl_log();
  }
}
