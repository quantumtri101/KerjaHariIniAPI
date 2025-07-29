<?php
namespace Database\Seeders;

use App\Http\Controllers\Helper\CityProvinceCountryHelper;

class CountrySeeder extends BaseSeeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $helper = new CityProvinceCountryHelper();
    $helper->import_country();
    $helper->import_province();
    $helper->import_city();
  }
}
