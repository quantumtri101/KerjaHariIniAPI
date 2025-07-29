<?php
namespace Database\Seeders;

use Hash;

use App\Models\CommunicationType;

class CommunicationTypeSeeder extends BaseSeeder
{
  private function insert_data($data1){
    $data = new CommunicationType();
    $data->id = $this->base->id_helper->generate_new_id_with_date('COMMUNICATION_TYPE',new CommunicationType());
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
      ['name' => 'All', 'data' => 'all'],
      ['name' => 'Personal', 'data' => 'personal'],
      ['name' => 'Blast', 'data' => 'blast'],
    ];
    foreach($arr as $data)
      $this->insert_data($data);
  }
}
