<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width, user-scalable=no">
    <meta charset="utf-8">
    <title>bones</title>

    {{Bones::cssIncludes()}}

</head>
<body>

    @include('bones::admin.navbar')

    <div class="container">
        @yield('main')
    </div>

    {{Bones::jsIncludes()}}

    @yield('additional_js')

</body>
</html>