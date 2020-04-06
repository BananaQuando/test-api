<?php
/**
 * Created by PhpStorm.
 * User: Oden
 * Date: 19.04.2019
 * Time: 1:40
 */

namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;

abstract class CoreRepository {

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * CoreRepository constructor.
	 */
	public function __construct() {

		$this->model = app($this->getModelClass());
	}

	/**
	 * @return mixed
	 */
	abstract protected function getModelClass();

	/**
	 * @return \Illuminate\Contracts\Foundation\Application|Model|mixed
	 */
	protected function startConditions(){

		return clone $this->model;
	}
}