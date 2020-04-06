<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Placeholder API for tests</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>

    <header>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Placeholder API for tests</h1>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>Prjects list</h2>
                <div class="card mb-3">
                    <ul class="list-group list-group-flush" id="current-projects">
                        @foreach($projects as $project)
                            <li class="list-group-item"><a href="/{{ $project->project_name }}">{{ $project->project_name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <form id="update_projects" action="{{ \Illuminate\Support\Facades\URL::to('/update') }}">
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                <div id="update_message_target" class="mb-5 mt-3"></div>
            </div>
            <div class="col-sm-12">
                <h2>How to start</h2>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    New project
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <p>To start new placeholder <span class="badge badge-secondary">project</span>, create new folder with name <code>your_project_name</code> in <code>{{ \Illuminate\Support\Facades\Config::get('app.TEST_API_FOLDER') }}</code>
                                    <br>Then you need update API by clicking <span class="badge badge-primary">Update</span> button on home page </p>
                                Your project will available on <a href="#">http:test-api.quando.pro/your_project_name</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    New API
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                                <p>To create new placeholder <span class="badge badge-secondary">API</span> add new JSON file with name <code>your_API_name.json</code> in your project folder.</p>
                                Your project will available on <a href="#">http:test-api.quando.pro/your_project_name/your_API_name</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    JSON format
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body">
                                Your <code>JSON</code> file must be an array of objects like this:
                                <pre class="code">
[
    {
        <span class="key">"id"</span>: <span class="number">1</span>, <span class="comment">// property id in object must be unique</span>
        <span class="key">"name"</span>: <span class="string">"My json object 1"</span>
    },
    {
        <span class="key">"id"</span>: <span class="number">2</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 2"</span>
    }
]
                                </pre>
                                or just a object like this:
                                <pre class="code">
{
    <span class="key">"id"</span>: <span class="number">1</span>,
    <span class="key">"name"</span>: <span class="string">"My json object"</span>,
    <span class="key">"description"</span>: <span class="string">"This is my json object"</span>
}
                                </pre>
                                <p>From your request it returns in this format</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Usage
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                            <div class="card-body">
                                ...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

