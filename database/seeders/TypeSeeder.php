<?php
namespace Database\Seeders;

use Hash;

use App\Models\Type;

class TypeSeeder extends BaseSeeder
{
  private function insert_data($data){
    $type = new Type();
    $type->id = $this->base->id_helper->generate_new_id_with_date('TYPE',new Type());
    $type->name = $data;
    $type->save();
  }
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $arr = ['customer','admin', 'staff'];
    foreach($arr as $data)
      $this->insert_data($data);
  }
}
