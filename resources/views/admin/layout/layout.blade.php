<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> @yield('title')</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/admin.bak/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/admin.bak/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="/admin.bak/css/animate.css" rel="stylesheet">
    <link href="/admin.bak/css/style.css?v=4.1.0" rel="stylesheet">
        <!-- 全局js -->
        <script src="/admin.bak/js/jquery.min.js?v=2.1.4"></script>
        <script src="/admin.bak/js/bootstrap.min.js?v=3.3.6"></script>
</head>

<body class="gray-bg">
    <div class="container" style="margin-top:6%">
        @yield('content')
    </div>
</body>

</html>
