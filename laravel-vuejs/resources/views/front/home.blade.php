@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <!-- @foreach($message as $msg)
                <public-message :home-message="{{ $msg }}"></public-message>
            @endforeach -->
            <public-message></public-message>
        </div>
    </div>
</div>
@endsection
