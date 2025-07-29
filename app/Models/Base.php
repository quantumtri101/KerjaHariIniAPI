<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

use App\Http\Controllers\Helper\FileHelper;
use App\Http\Controllers\BaseController;

use App\Models\EndpointLog;

class Base extends Model
{
    use SoftDeletes;
    // configuration for increment id
    public $incrementing = false;

    public $arr_relationship = [];
    public $arr_column_image = [];

    public $from_system = false;

    // configuration for dates group
    protected $dates = [
			'updated_at',
			'created_at',
			'date',
      'birth_date',
      'canceled_at',
      'work_schedule',
      'brief_schedule',
      'schedule',
      'sent_at',
      'approve_at',
      'start_date',
      'end_date',
      'start_time',
      'end_time',
      'declined_at',
      'publish_at',
      'salary_sent_at',
      'additional_salary_sent_at',
      'date_init',
      'publish_start_at',
      'publish_end_at',
      'publish_start_date',
      'publish_end_date',
      'approved_at',
      'declined_at',
      'transfer_date',
      'request_delete_at',
		];

    public static function boot() {
      parent::boot();

      static::deleting(function($data) {
        if(Auth::check()){
          $data->deleted_by = Auth::user()->id;
          $data->save();
        }
      });

      static::deleted(function($data) {
        $data->on_delete_relationship($data);
      });

      static::creating(function($data) {
        $data->id = parent::get_id($data);
        if(Auth::check()){
          $data->created_by = Auth::user()->id;
          $data->updated_by = Auth::user()->id;
        }
      });

      static::updating(function($data)  {
        if(Auth::check()){
          $data->updated_by = Auth::user()->id;
        }
      });

      static::restoring(function ($user) {
      });
    }

    public function on_delete_relationship($data){
      $file_helper = new FileHelper();
      foreach($this->arr_column_image as $key => $column){
        if(!empty($data->{$key}))
          $file_helper->remove_image($column, $data->{$key});
      }

      foreach($this->arr_relationship as $relationship){
        foreach($data->{$relationship} as $rel)
          $rel->delete();
      }
    }

    // function to show table name
    public function get_table_name(){
    	return $this->table;
    }

    public function get_id($data){
      $base_controller = new BaseController();
    	return $base_controller->id_helper->generate_new_id_with_date($this->get_id_label(), $data);
    }

    public function get_id_label(){
    	return strtoupper($this->table);
    }

    public function created_user(){
      return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function updated_user(){
      return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }
}
