<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    @include('admin.layouts.partials.head')

</head>

<body>
    @if (!View::hasSection('hidesidebar'))
    @include('admin.layouts.partials.menu-left')
    @endif

    @yield('content')


    <div id="footer" class="hidden-print">

        <!--  Copyright Line -->
        <div class="copy">© 2023 - All Rights Reserved.</a></div>
        <!--  End Copyright Line -->

    </div>
</body>

</html>