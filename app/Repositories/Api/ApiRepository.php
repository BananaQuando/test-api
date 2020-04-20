<?php
/**
 * Created by PhpStorm.
 * User: Oden
 * Date: 26.04.2019
 * Time: 7:25
 */

namespace App\Repositories\Api;

use App\Repositories\CoreRepository;
use App\Models\Api\ApiModel as Model;

class ApiRepository extends CoreRepository {

	/**
	 * @return string
	 */
	protected function getModelClass() {

		return Model::class;
	}

	public function clearAllApi() {

		$this->startConditions()::whereNotNull('id')->delete();
	}


	public function createNewApi($api){

		$this->startConditions()->create($api);
	}

	public function updateApi($api_name, $data){

		$api = $this->startConditions()::where(['api_name' => $api_name])->first();;

		$api->data = $data;

		$api->save();

		return $api;
	}

	public function getAllProjectsApi($project_name){

		$result = $this->startConditions()::select('api_name')->leftJoin('api_projects', 'api.project_id', '=', 'api_projects.id')->where(['project_name' => $project_name])->get();

		return $result;
	}

	public function getApiData($api_name){

		$result = $this->startConditions()::select('data')->where(['api_name' => $api_name])->first();

		if ($result){

			return json_decode(json_decode(json_encode($result->data)));
		}else{
			return null;
		}
	}
}