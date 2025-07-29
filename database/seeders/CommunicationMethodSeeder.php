<?php
namespace Database\Seeders;

use Hash;

use App\Models\CommunicationMethod;

class CommunicationMethodSeeder extends BaseSeeder
{
  private function insert_data($data1){
    $data = new CommunicationMethod();
    $data->id = $this->base->id_helper->generate_new_id_with_date('COMMUNICATION_METHOD',new CommunicationMethod());
    $data->name = $data1['name'];
    $data->data = $data1['data'];
    $data->save();
  }
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $arr = [
      ['name' => 'Email', 'data' => 'email'],
      ['name' => 'Push Notificaton', 'data' => 'push_notification'],
    ];
    foreach($arr as $data)
      $this->insert_data($data);
  }
}
