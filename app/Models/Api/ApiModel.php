<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ApiModel extends Model
{
    protected $table = 'api';

	public $timestamps = true;

	protected $fillable = [
		'project_id',
		'api_name',
		'api_file',
		'data'
	];

	public function project() {

		return $this->hasOne(ApiProjectModel::class, 'project_id');
	}
}
