<?php
namespace Database\Seeders;

use Hash;

use App\Models\User;
use App\Models\Type;

class UserSeeder extends BaseSeeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $type = Type::where('name', 'like', 'admin')->first();

    $user = new User();
    $user->id = $this->base->id_helper->generate_new_id_with_date('USER',new User());
    $user->type_id = $type->id;
    $user->name = 'admin';
    $user->email = 'admin@admin.com';
    $user->password = Hash::make('12345');
    $user->phone = '12345';
    $user->save();
  }
}
