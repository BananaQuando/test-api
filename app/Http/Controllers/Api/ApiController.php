<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ApiProjectModel;
use App\Repositories\Api\ApiProjectRepository;
use App\Repositories\Api\ApiRepository;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Config;

class ApiController extends Controller
{

	private $apiRepository;
	private $apiProjectRepository;
	private $sortBy;
	private $orderBy;

	public function __construct() {

		$this->apiRepository = app(ApiRepository::class);
		$this->apiProjectRepository = app(ApiProjectRepository::class);
	}

    public function index()
    {

    	$projects = $this->apiProjectRepository->getAllProjects();

    	return view('index', compact('projects'));
    }

    public function show($project_name){

    	$api_list = $this->apiRepository->getAllProjectsApi($project_name);

	    return view('project', compact('api_list', 'project_name'));
    }

    public function getApi($project_name, $api_name, $api_id = null){

		$api_data = $this->apiRepository->getApiData($api_name);

		$filters = $_GET;

	    $result_json = $this->filterApiResults($api_data, $filters, $api_id);

	    return $result_json;
    }

	public function postApi($project_name, $api_name, $api_id = null, Request $request){

		$api_data = $this->apiRepository->getApiData($api_name);

		$filters = array_merge($_GET, $request->all());

		$result_json = $this->filterApiResults($api_data, $request->all(), $api_id);

		return response()->json($result_json, 200);
	}

    private function filterApiResults($api_data, $filters, $api_id){

		$result = [];

	    if (gettype($api_data) == 'array'){

		    foreach ($api_data as $api_item) {

		    	$is_valid = $this->isValidApiResult($api_item, $filters, $api_id);

		    	if ($api_id !== null && $is_valid){
				    $result = $api_item;
			    }else if($is_valid){
				    $result[] = $api_item;
			    }
	    	}
	    }else if(gettype($api_data) == 'object'){
			if ($this->isValidApiResult($api_data, $filters, $api_id)){
				$result = $api_data;
			}
	    }

	    if (gettype($result) == 'array' && isset($filters['api_sort_by'])){
	    	$result = $this->sortArray($result, $filters);
	    }

	    return $result;
    }

    private function sortArray($array, $filters){

		$this->orderBy = 'ASC';
	    $this->sortBy = $filters['api_sort_by'];

		if (isset($filters['api_order_by']) && ($filters['api_order_by'] == 'ASC' || $filters['api_order_by'] == 'DESC')) $this->orderBy = $filters['api_order_by'];

	    usort($array, array($this, "sortCompare"));

	    return $array;
    }

    private function sortCompare($a, $b){

		if (!isset($a->{$this->sortBy})) return -1;
		if (!isset($b->{$this->sortBy})) return 1;

		if ($this->orderBy === 'ASC'){
			return strcmp($a->{$this->sortBy}, $b->{$this->sortBy});
		} else {
			return strcmp($b->{$this->sortBy}, $a->{$this->sortBy});
		}

    }

    private function isValidApiResult($api_item, $filters, $api_id){

		if ($filters){
			foreach ($filters as $filter_key => $filter) {

				if ($filter_key === 'api_sort_by' || $filter_key === 'api_order_by') continue;

				if (!isset($api_item->{$filter_key}) || $api_item->{$filter_key} != $filter){
					return false;
				}
			}
		}

		if ( ($api_id !== null && !isset($api_item->id)) || ($api_id !== null && $api_item->id != $api_id) ) return false;
		return true;
    }


    public function update(Request $request){

		$this->apiRepository->clearAllApi();
		$this->apiProjectRepository->clearAllProjects();

		$projects = $this->getProjects();

	    foreach ($projects as $key => $project) {

		    $projects[$key] = $this->apiProjectRepository->createNewProject($project);

	    	$api_list = $this->getApiFromProject($projects[$key]);

		    foreach ($api_list as $api) {
			    $this->apiRepository->createNewApi($api);
	    	}
		}

		return json_decode(json_encode($projects));
    }

    private function getApiFromProject($project){

    	$api_array = [];

	    foreach (new \DirectoryIterator($project['project_folder']) as $fileInfo) {
	    	$file_name = $fileInfo->getFilename();
	    	$api_file = $project['project_folder'] . '/' . $file_name;
	    	$api_name = preg_replace('/\.json/', '', $file_name);

	    	if (!preg_match('/\.json/', $file_name)) continue;

	    	$file_content = file_get_contents($api_file);

		    $api_array[] = [
				'project_id' => $project['project_id'],
				'api_name' => $api_name,
				'api_file' => $api_file,
				'data' => $file_content,
		    ];
	    }

	    return $api_array;
    }

    private function getProjects(){

	    $root_folder = Config::get('app.TEST_API_FOLDER');
	    $path_with_slashes = preg_replace('/([^A-Za-z0-9\s])/', '\\\\$1', $root_folder);

	    $folders = array_filter(glob($root_folder.'*'), 'is_dir');
	    $projects = [];

	    foreach ($folders as $folder) {
		    $projects[] = [
		    	'project_name' => strtolower(preg_replace('/' . $path_with_slashes . '/', '', $folder)),
			    'project_folder' => $folder
		    ];
		}

		return $projects;
    }

}
