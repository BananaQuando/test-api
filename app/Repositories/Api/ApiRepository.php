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

	public function getAllProjectsApi($project_name){

		$result = $this->startConditions()::select('api_name')->leftJoin('api_projects', 'api.project_id', '=', 'api_projects.id')->where(['project_name' => $project_name])->get();

		return $result;
	}
}