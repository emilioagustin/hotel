<!doctype html>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
    <title>CMS</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
     <!-- Main -->
     <main class="main">
            <!--Contenedor-->
            <div class="container">
                <div id="app"></div>
            </div>
            <!--/Contenedor -->
        </main>
        <!-- Main -->
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>