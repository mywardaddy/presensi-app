@php
    $app = App\Models\Application::first();
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title> {{ $title }} - {{ $app->app_name }}</title>
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($app->favicon) }}"/>
        @stack('prepend-style')
            <link href="admin/css/styles.css" rel="stylesheet" />
            <link rel="manifest" href="{{ url('admin/manifest.json') }}">
        @stack('addon-style')
        <style>
            .bg-custom {
                background: {{ $app->color }};
            }
        </style>
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-custom">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                @yield('main')
            </div>
            <div id="layoutAuthentication_footer">
                @include('includes.auth-footer')
            </div>
        </div>
        @stack('prepend-script')
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src="{{ url('admin/js/scripts.js') }}"></script>
            <script>
                window.addEventListener("load", () => {
                    if ("serviceWorker" in navigator) {
                        navigator.serviceWorker.register("{{ url('admin/js/sw.js') }}")
                    }
                })
            </script>
        @stack('addon-script')
    </body>
</html>
