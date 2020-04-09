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
                                <br>Then you need update API by clicking <span class="badge badge-primary">Update</span> button on home page </p>
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
                                <p>After uploading your API will be available on <a href="#">http:test-api.quando.pro/your_project_name/your_API_name</a> it just simple <code>JSON</code></p>
                                <p>You can fetch this data from your app</p>
                                <p><code>URL</code> provides <code>id</code> parameter like this <a href="#">http:test-api.quando.pro/your_project_name/your_API_name/<code>{id}</code></a>. Then <code>API</code> returns only 1 object that contains this <code>id</code> property.</p>
                                <pre class="code">
{
    <span class="key">"id"</span>: <span class="number">1</span>, <span class="comment">// id prop</span>
    <span class="key">"name"</span>: <span class="string">"My json object 1"</span>
}
                                </pre>
                                <p>Also you can add some <code>GET</code> params like this <a href="#">http:test-api.quando.pro/your_project_name/your_API_name<code>?something=1</code></a></p>
                                <p>Or some <code>POST</code> params in your request</p>
                                <p>It returns only objects that contains same property with same value</p>
                                <pre class="code">
[
    {
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 1"</span>,
        <span class="key">"something"</span>: <span class="number">1</span> <span class="comment">// ?something=1</span>
    },
    {
        <span class="key">"id"</span>: <span class="number">2</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 2"</span>,
        <span class="key">"something"</span>: <span class="number">1</span>
    }
]
                                </pre>
                                <p><code>GET</code> or <code>POST</code> params can provide props <code>api_sort_by</code> and <code>api_order_by</code></p>
                                <p><code>api_sort_by</code> accepts the prop name. Returned array will be sorted by value of this field</p>
                                <p>Example <a href="#">http:test-api.quando.pro/your_project_name/your_API_name<code>?api_sort_by=something</code></a></p>
                                <pre class="code">
[
    {
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 1"</span>,
        <span class="key">"something"</span>: <span class="number">1</span> <span class="comment">// sorted by this value</span>
    },
    {
        <span class="key">"id"</span>: <span class="number">3</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 3"</span>,
        <span class="key">"something"</span>: <span class="number">2</span>
    },
    {
        <span class="key">"id"</span>: <span class="number">2</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 2"</span>,
        <span class="key">"something"</span>: <span class="number">3</span>
    }
]
                                </pre>
                                <p><code>api_order_by</code> can provide only <code>ASC</code> and <code>DESC</code>. This value set the sorting order</p>
                                <p>Example <a href="#">http:test-api.quando.pro/your_project_name/your_API_name<code>?api_sort_by=something&api_order_by=DESC</code></a></p>
                                <pre class="code">
[
    {
        <span class="key">"id"</span>: <span class="number">2</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 2"</span>,
        <span class="key">"something"</span>: <span class="number">3</span> <span class="comment">// DESC order by this value</span>
    },
    {
        <span class="key">"id"</span>: <span class="number">3</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 3"</span>,
        <span class="key">"something"</span>: <span class="number">2</span>
    },
    {
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"name"</span>: <span class="string">"My json object 1"</span>,
        <span class="key">"something"</span>: <span class="number">1</span>
    }

]
                                </pre>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingFive">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                        Authentication
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseFive" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <p><code>GET</code> or <code>POST</code> params can provide props <code>password</code> and <code>username</code> or <code>email</code></p>
                                    <p>if you pass prop <code>password</code> you also need to pass <code>login</code> prop that contains <code>username</code> or <code>email</code> field value. It returns only 1 object without field <code>password</code> that contain same username or email and password hash in <code>md5</code></p>
                                    <p><h3>Example:</h3></p>
                                    <p><code>JSON</code> file</p>
                                    <pre class="code">
[
    {
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"username"</span>: <span class="string">"test1"</span>,
        <span class="key">"email"</span>: <span class="string">test1@gmail.com</span>
        <span class="key">"password"</span>: <span class="string">fbb57a2d056c54e2d9ecf0054cf9f0da</span> <span class="comment">// MD5 hash of 654456</span>
    },
    {
        <span class="key">"id"</span>: <span class="number">2</span>,
        <span class="key">"username"</span>: <span class="string">"test2"</span>,
        <span class="key">"email"</span>: <span class="string">test2@gmail.com</span>
        <span class="key">"password"</span>: <span class="string">c8837b23ff8aaa8a2dde915473ce0991</span> <span class="comment">// MD5 hash of 123321</span>
    },
    {
        <span class="key">"id"</span>: <span class="number">3</span>,
        <span class="key">"username"</span>: <span class="string">"demo"</span>,
        <span class="key">"email"</span>: <span class="string">demo@demo.com</span>
        <span class="key">"password"</span>: <span class="string">fe01ce2a7fbac8fafaed7c982a04e229</span> <span class="comment">// MD5 hash of 'demo'</span>
    }

]
                                    </pre>
                                    <p>Request: <a href="#">http:test-api.quando.pro/your_project_name/your_API_name<code>?login=test1&password=654456</code></a></p>
                                    <p>Return:</p>
                                    <pre class="code">
{
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"username"</span>: <span class="string">"test1"</span>,
        <span class="key">"email"</span>: <span class="string">test1@gmail.com</span>
}
                                    </pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

