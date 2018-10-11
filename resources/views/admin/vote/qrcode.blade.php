@extends('layouts.admin')

@section('content')
<br>
<div class="container" style="text-align: center;">
    <div class="row">
        @include('flash::message')
        <div class="col-md-6 col-md-offset-3">
            <label for="url">投票链接</label>
            <input type="text" class="form-control" id="url" value="{{ $res[0] }}" />
        </div>
        <div class="col-md-12" style="margin-top: 3%;">
            <label for="url_img">投票二维码</label>
            <br>
            <img src="{{ asset($res[1]) }}" alt="" id="url_img">
        </div>
    </div>
</div>
@endsection
