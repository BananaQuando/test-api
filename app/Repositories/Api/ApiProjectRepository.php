<?php
/**
 * Created by PhpStorm.
 * User: Oden
 * Date: 06.04.2020
 * Time: 13:06
 */

namespace App\Repositories\Api;

use App\Repositories\CoreRepository;
use App\Models\Api\ApiProjectModel as Model;

class ApiProjectRepository extends CoreRepository {
	/**
	 * @return string
	 */
	protected function getModelClass() {

		return Model::class;
	}

	public function clearAllProjects() {

		$this->startConditions()::whereNotNull('id')->delete();
	}

	public function createNewProject($project){

		$result = $this->startConditions()->create($project);

		if ($result){
			return [
				'project_id' => $result->id,
				'project_name' => $result->project_name,
				'project_folder' => $result->project_folder
			];
		}else{
			return null;
		}
	}
}