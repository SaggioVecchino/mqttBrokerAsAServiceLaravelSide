@extends('layouts.app')

@section('content')

    <div class="container" >
    <a href="/" class="btn btn btn-outline-danger">QUIT!</a>
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
            <projectlist :projects="{{($projects)->toJson() }}" :userid="{{$user_id}}">

            </projectlist>

        </div>
    </div>
</div>
@endsection