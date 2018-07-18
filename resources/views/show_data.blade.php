@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects/{{$project_id}}/specify_data" class="btn">Go back!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                </div>
                @endif
            <div class="card">
                <div class="card-header">
                    <b>Data for:</b> <br>
                    <!-- we can specify the type of data the user asked -->
                </div>
                <div class="card-body">
                    <?php $i = 0 ?>
                    @foreach($response as $entry)
                        <b>{{$i++}}:</b> <br>
                        <b>x:</b> {{$entry->x}} <br>
                        <b>y:</b> {{$entry->y}} <br><br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
