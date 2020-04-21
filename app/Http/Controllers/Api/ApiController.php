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

	private $project_name;
	private $thumb_width = 280;
	private $thumb_height = 280;
	private $placeholder_height = 20;

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

		if ($api_name === 'api_images') {
			$image = Config::get('app.TEST_API_FOLDER') . "/$project_name/$api_name/$api_id";
			return response()->file($image);
		}

	    $this->project_name = $project_name;

		$api_data = $this->apiRepository->getApiData($api_name);

	    $result_json = $this->filterApiResults($api_data, $_GET, $api_id);

	    return json_encode($result_json);
    }

	public function postApi($project_name, $api_name, $api_id = null, Request $request){

		if ($api_name === 'api_images') {
			$image = Config::get('app.TEST_API_FOLDER') . "/$project_name/$api_name/$api_id";
			return response()->file($image);
		}

		$this->project_name = $project_name;

		$api_data = $this->apiRepository->getApiData($api_name);

		$result_json = $this->filterApiResults($api_data, $request->all(), $api_id);

		return response()->json($result_json, 200);
	}

    private function filterApiResults($api_data, $filters, $api_id){

		$result = [];

	    if (gettype($api_data) == 'array'){

		    foreach ($api_data as $api_item) {

		    	$is_valid = $this->isValidApiResult($api_item, $filters, $api_id);

		    	if (isset($api_item->password)) unset($api_item->password);

				if (($api_id !== null || isset($filters['password'])) && $is_valid){
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

	    $result = $this->saveImages($result);

	    return $result;
    }

    private function saveImages($items){

		$result = [];

	    if (gettype($items) == 'array'){
		    foreach ($items as $item) {
		    	$result[] = $this->getImage($item);
		    }
	    }else{
		    $result = $this->getImage($items);
	    }
	    return $result;
    }

    private function getImage($item){

	    if (isset($item->image) && $item->image){

		    $item->thumbnail = $this->downloadImage($item->image, $this->thumb_width, $this->thumb_height);
		    $item->thumbnail_placeholder = $this->downloadImage($item->image, $this->thumb_width, $this->thumb_height, true);
		    $item->icon = $this->downloadImage($item->image, 60, 60);
		    $item->image_placeholder = $this->downloadImage($item->image, null, null, true);
		    $item->image = $this->downloadImage($item->image);
	    }

	    return $item;
    }

    private function downloadImage($image, $r_width = null, $r_height = null, $scale = false){

		$resize = (!$r_width && !$r_height) ? false : true;

	    $images_folder = Config::get('app.TEST_API_FOLDER') . $this->project_name . '/api_images';

	    if (!is_dir($images_folder)) mkdir($images_folder);

		$filename = preg_replace('/^(\S+)\//', '', $image);
		$filename = preg_replace('/\?\S+/', '', $filename);
		$filename = md5($filename);

	    $filename .= $scale ? "_ph" : "";
	    $filename .= $resize ? "_$r_width" . "x" . "$r_height" : "";

	    $image_name = "$images_folder/$filename.jpg";
	    $image_url = Config::get('app.SITE_URL') . "$this->project_name/api_images/$filename.jpg";

	    if (file_exists($image_name)) return $image_url;

		$img = $this->curlDownloadImage($image);

		if ($this->is_base64_encoded($img)) $img = base64_decode($img);
		$im = imagecreatefromstring($img);

		$width = imagesx($im);
	    $r_width = $r_width ? $r_width : $width;

		$height = imagesy($im);
		$r_height = $r_height ? $r_height : $height;

	    if ($width > $height) {
		    $y = 0;
		    $x = ($width - $height) / 2;
		    $sm_height = $height;
		    $sm_width = $height;
	    } else {
		    $x = 0;
		    $y = ($height - $width) / 2;
		    $sm_height = $width;
		    $sm_width = $width;
	    }


	    if (!$resize){
		    $r_width = $width;
		    $r_height = $height;
		    $sm_width = $width;
		    $sm_height = $height;
		    $x = 0;
		    $y = 0;
	    }
	    if ($scale){
		    $ratio = $r_width / $r_height;
		    $r_width = $ratio * $this->placeholder_height;
		    $r_height = $this->placeholder_height;
	    }


	    $thumb = imagecreatetruecolor($r_width, $r_height);
	    imagecopyresized($thumb, $im, 0, 0, $x, $y, $r_width, $r_height, $sm_width, $sm_height);

	    imagejpeg($thumb, $image_name);
	    imagedestroy($thumb);
	    imagedestroy($im);

		return $image_url;
    }

    private function is_base64_encoded($data){
	    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
		    return TRUE;
	    } else {
		    return FALSE;
	    }
    }

    private function curlDownloadImage($image){

	    header("Content-Type: image/jpeg");

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $image);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
	    $res = curl_exec($ch);
	    $rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch) ;
	    return $res;
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

				if (
					($filter_key === 'password' && $api_item->{$filter_key} !== md5($filter))
					||
					($filter_key === 'login' && ($api_item->email != $filter && $api_item->username != $filter))
					||
					($filter_key !== 'login' && $filter_key !== 'password' && ((!isset($api_item->{$filter_key})) || ($api_item->{$filter_key} != $filter)))
				){
					return false;
				}
			}

			if (isset($filters['password']) && !(isset($filters['login']))){
				return false;
			}
		}

		if ( ($api_id !== null && !isset($api_item->id)) || ($api_id !== null && $api_item->id != $api_id) ) return false;
		return true;
    }


    public function updateApi($project_name, $api_name, $api_id, Request $request){

	    $api_data = $this->apiRepository->getApiData($api_name);

	    $api_request = $request->all();

	    foreach ($api_data as $key => $api_item) {
		    if ($api_item->id == $api_id){
			    $api_data[$key] = $api_request;
		    }
	    }

	    $this->apiRepository->updateApi($api_name, $api_data);

		return $this->getApi($project_name, $api_name, $api_id);
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

	public function uploadImage($project_name, $api_name, $api_id, Request $request){

		$images_folder = Config::get('app.TEST_API_FOLDER') . $project_name . '/api_images';

		if ($request->hasFile('image')){
			$filename = $request->file('image')->getClientOriginalName();
			$request->file('image')->move($images_folder, $filename);

			$image_url = Config::get('app.SITE_URL') . "$project_name/api_images/$filename";

			return ['status' => 'success' ,'url' => $image_url];
		}

		return ['status' => 'error' ,'error' => 'no image'];
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
