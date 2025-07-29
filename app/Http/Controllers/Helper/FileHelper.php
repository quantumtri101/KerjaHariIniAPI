<?php
namespace App\Http\Controllers\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Storage;
use Auth;
use Hash;
use Curl;
use Image;
use Mail;
use File;
use Carbon\Carbon;

class FileHelper{
  private $max_width = 1000;
  private $quality_compress = 80;
  private $image_extension = 'png';
  private $image_content_type = 'image/png';

  public function manage_image($image, $model, $filesystem, $column = 'file_name', $file_name = '', $extension = '', $save_original = false){
    if(!empty($image) && $image != ''){
      $image1 = Image::make($image);
      $req = new Request();
      // if(!empty($image['original_rotation'])){
      //   $req->original_rotation = $image['original_rotation'];
      //   $image1->rotate($image['original_rotation']);
      // }
      $file_name = $this->save_image($image1, $filesystem, $file_name == '' ? $model->id : $file_name, $extension, $save_original);
      if(!empty($model))
        $model->{$column} = $file_name;
    }
  }

  public function manage_file($file, $model, $filesystem, $column = 'file_name', $mime_type_column = ''){
    
    if(!empty($file) && !empty($file["file"])){
      $file_name_split = explode('.',$file['file_name']);
      $extension = $file_name_split[count($file_name_split) - 1];
      
      $file_name = $this->save_file($file['file'], $filesystem, !empty($model) ? $model->id : $file_name_split[0], $extension);
      $model->{$column} = $file_name;
      if($mime_type_column != ""){
        if($extension == 'docx')
          $model->{$mime_type_column} = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        else if($extension == 'xlsx')
          $model->{$mime_type_column} = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        else if($extension == 'pptx')
          $model->{$mime_type_column} = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        else if($extension == 'pdf')
          $model->{$mime_type_column} = 'application/pdf';
        else if($extension == 'png')
          $model->{$mime_type_column} = 'image/png';
        else if($extension == 'jpeg' || $extension == 'jpg')
          $model->{$mime_type_column} = 'image/jpeg';
      }
    }
  }

  public function save_image($image, $storage, $file_name, $extension = '', $save_original = false){
    if(!$save_original && $image->width() > $this->max_width)
      $image->resize($this->max_width, null, function ($constraint) {
        $constraint->aspectRatio();
      });

    Storage::disk($storage)->put($file_name.'.'.($extension == '' ? $this->image_extension : $extension),$image->encode($extension == '' ? $this->image_extension : $extension,$this->quality_compress));

    return $file_name.'.'.($extension == '' ? $this->image_extension : $extension);
  }

  public function remove_image($storage, $file_name){
    Storage::disk($storage)->delete($file_name);
  }

  public function save_file($file,$storage,$file_name,$extension){
    if(!($file instanceof UploadedFile)){
      $file = str_replace('data:application/pdf;base64,', '', $file);
      $file = str_replace('data:application/vnd.openxmlformats-officedocument.wordprocessingml.document;base64,', '', $file);
      $file = str_replace('data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,', '', $file);
      $file = str_replace('data:application/vnd.openxmlformats-officedocument.presentationml.presentation;base64,', '', $file);
      $file = str_replace('data:image/jpeg;base64,', '', $file);
      $file = str_replace('data:image/png;base64,', '', $file);
      $file = str_replace(' ', '+', $file);
    }
    
    Storage::disk($storage)->put($file_name.'.'.$extension, !($file instanceof UploadedFile) ? base64_decode($file) : File::get($file));

    return $file_name.'.'.$extension;
  }

  public function copy_file($source_filename, $source_filesystem, $model, $destination_filesystem, $column = 'file_name'){
    $file = Image::make(Storage::disk($source_filesystem)->get($source_filename));
    $file_name = $this->save_image($file, $destination_filesystem, $model->id);
    $model->{$column} = $file_name;
  }
}
