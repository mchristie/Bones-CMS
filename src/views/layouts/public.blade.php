<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width, user-scalable=no">
    <meta charset="utf-8">
    <title>{{$site->title}}</title>
    <link rel="stylesheet" href="/packages/christie/bones/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/packages/christie/bones/styles.css">

</head>
<body>

    <div class="container">
        {{Bones::widgets('main_menu', true)}}

        @yield('main')

    </div>

    <script src="/packages/christie/bones/jquery.min.js"></script>
    <script src="/packages/christie/bones/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>