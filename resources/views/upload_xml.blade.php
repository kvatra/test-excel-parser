<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head title="XML">
        <script src="{{ mix('/js/app.js') }}"></script>
        <script>
            Echo.channel('App.Models.Row.public')
                .listen('RowRecordCreated', (e) => {
                  console.log('record created', e)
                })
        </script>
    </head>
    <body>
        <form action="{{ route('upload-xml') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            File:
            <br />
            <input type="file" name="xml" />
            <br /><br />
            <input type="submit" value=" Save " />
        </form>
    </body>
</html>
