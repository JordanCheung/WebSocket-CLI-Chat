@extends('welcome')

@section('content')
    <div class="col-md  ">
        <img class="img-thumbnail" src="{{$picture}}"/>
        <h2>Thanks for signing in {{$name}}.</h2>
        <h2>Here is your cli login code</h2>
        <code >
            {{$code}}
        </code>


        <h2> or </h2>
        <a href="/chat?code={{$code}}" class="btn btn-light btn-lg">
            click here to start web chat!
        </a>
    </div>
@endsection
