<form action="{{ route('upload-xml') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    File:
    <br/>
    <input type="file" name="xml"/>
    <br/><br/>
    <input type="submit" value=" Save "/>
</form>
