<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

use App\Http\Controllers\Helper\FileHelper;
use App\Http\Controllers\BaseController;

class BaseAuth extends Authenticatable
{
  use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

  protected $table = 'user';

  protected $dates = [
    'updated_at',
    'created_at',
    'birth_date',
    'contract_start_date',
  ];

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'name',
    'email',
  ];

  public $incrementing = false;

  public $arr_relationship = [];
  public $arr_column_image = [];

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

    static::updating(function($data) {
      if(Auth::check()){
        $data->updated_by = Auth::user()->id;
      }
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
