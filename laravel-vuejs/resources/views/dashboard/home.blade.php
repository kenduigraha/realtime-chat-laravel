@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header">
                    <h3>
                        Home Message
                        <i class="fas fa-home"></i>
                    </h3>
                </div>

                <!-- <div class="card-body"> -->
                    <chat-room></chat-room>
                <!-- </div> -->
            </div>

        </div>
    </div>
</div>
@endsection
