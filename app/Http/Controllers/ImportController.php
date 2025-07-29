<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CityProvinceCountryHelper;

class ImportController extends BaseController{

  public function import_country(Request $request){
    $helper = new CityProvinceCountryHelper();
    $helper->import_country();
    $helper->import_province();
    $helper->import_city();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ]);
  }
}
