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

	public function __construct() {

		$this->apiRepository = app(ApiRepository::class);
		$this->apiProjectRepository = app(ApiProjectRepository::class);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    	return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

		$this->apiRepository->clearAllApi();
		$this->apiProjectRepository->clearAllProjects();

		$projects = $this->getProjects();

	    foreach ($projects as $project) {

			$this->apiProjectRepository->createNewProject($project);

	    	$this->getApiFromProject($project['project_folder']);
		}
		echo "<pre>" . print_r($projects, true) . "</pre>";
    }

    private function getApiFromProject($project_dir){


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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
