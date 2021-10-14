<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head title="XML">
    <script src="/js/app.js"></script>
    <script>
      Echo.channel('App.Models.Row.public.' + '{{ $file_id }}')
        .listen('RowRecordCreated', (e) => {
          console.log('record created', e)
        })
    </script>
</head>
<body>
<h2>Success!</h2>
</body>
</html>