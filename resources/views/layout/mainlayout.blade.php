<!DOCTYPE html>

<html lang="en">

<head>

    @include('layout.partials.head')

</head>


<body class="d-flex flex-column min-vh-100">


    @include('layout.partials.headerscript')


    <main class="flex-grow-1">
    @yield('content')
    </main>

    @include('layout.partials.footer')

    @include('layout.partials.footerscript')

</body>

</html>