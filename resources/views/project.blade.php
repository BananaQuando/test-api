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
                    <h1>Project {{ $project_name }} API list</h1>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>API list</h2>
                <div class="card mb-3">
                    <ul class="list-group list-group-flush" id="current-projects">
                        @foreach($api_list as $api)
                            <li class="list-group-item"><a href="/{{ $project_name }}/{{ $api->api_name }}">{{ $api->api_name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

