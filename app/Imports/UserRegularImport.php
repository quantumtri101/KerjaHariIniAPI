<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Hash;

use App\Http\Controllers\BaseController;

use App\Jobs\SendEmailAuthJob;

use App\Models\User;
use App\Models\Type;
use App\Models\Company;

class UserRegularImport implements ToCollection
{
	public function collection(Collection $rows){
		$base_controller = new BaseController();
		foreach($rows as $key => $row){
			if($key == 0)
				continue;

			$type = Type::where('name', 'like', 'customer_regular')->first();
			$company = Company::where('name', 'like', '%'.str_replace(' ', '%', $row[3]).'%')->first();
			$user = User::orWhere('phone','=',"+62".$row[2])->first();
			if(!empty($user))
				continue;

			$password = $base_controller->string_helper->generateRandomString($base_controller->str_length);
			$data = new User();
			$data->type_id = $type->id;
			if(!empty($company))
				$data->company_id = $company->id;
			$data->name = $row[0];
			$data->phone = "+62".$row[2];
			$data->email = strtolower($row[1]);
			$data->is_active = 1;
			$data->gender = $row[4] == "male" ? 1 : 0;
			$data->password = Hash::make($password);
			$data->id_no = $row[5];
			$data->save();

			SendEmailAuthJob::dispatch('email.auth.register', [
				'user' => $data,
				'status' => 'Welcome',
				'url_frontend' => '',
				'type' => 'register',
				'app_name' => $base_controller->app_name,
				'password' => $password,
			], $data, 'Registration Staff Regular Process Successful')
				->onQueue('worker_1')
				->afterResponse();
		}
  }
}