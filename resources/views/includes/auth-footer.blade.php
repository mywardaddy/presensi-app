@php
    $app = App\Models\Application::first();
@endphp
<footer class="footer-admin mt-auto footer-dark">
    <div class="container-xl px-4">
        <div class="row">
            <div class="col-md-12 small text-center">Copyright &copy; {{ $app->copyright }} {{ date('Y') }}</div>
        </div>
    </div>
</footer>