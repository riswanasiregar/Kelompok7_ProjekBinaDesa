<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sign in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('template/images/logos/favicon.png') }}">
    <link type="text/css" href="{{ asset('template/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('template/css/styles.min.css') }}" rel="stylesheet">
</head>
<body>
    <main>
        <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('template/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

