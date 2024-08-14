@extends('admin.layout.master')
@section('content')
<div class="container">
    <h2>Import Users</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.import-users') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file">Upload CSV File</label>
            <input type="file" name="csv_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Import Users</button>
    </form>
</div>


@endsection
