<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width, user-scalable=no">
    <meta charset="utf-8">
    <title>bones</title>

    {{Bones::cssIncludes()}}

</head>
<body>

    @include('bones::admin.fragments.navbar')

    <div class="container">
        @yield('main')
    </div>



    <div class="row">
        <div class="cols-sm-12">

            <hr>

            <div class="container">

                <p class="text-muted">Bones CMS</p>

            </div>

        </div>
    </div>

    <p>&nbsp;</p>

    {{Bones::jsIncludes()}}

    @yield('additional_js')

</body>
</html>