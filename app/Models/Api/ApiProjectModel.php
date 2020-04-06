<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ApiProjectModel extends Model
{
	protected $table = 'api_projects';

	public $timestamps = true;

	protected $fillable = [
		'project_name',
		'project_folder',
	];

	public function api() {

		return $this->hasMany(ApiModel::class, 'id');
	}
}
