<script>
    window.AppAlerts = {{ Illuminate\Support\Js::from([
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
        'validationErrors' => $errors->all(),
    ]) }};
</script>