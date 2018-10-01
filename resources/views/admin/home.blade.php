@extends('layouts.admin')

@section('content')
<div class="container" style="text-align: center;">
    <div class="row">
        <div class="col-md-12">
        <div class="alert">
            @if(!Auth::guest())
                {{Auth::user()->username}}
            @endif
            </div> 
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    访问量
                </div>
                <div class="panel-body">
                    <h2><strong>123</strong></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    投票总人数
                </div>
                <div class="panel-body">
                    <h2><strong>123</strong></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    未投票人数
                </div>
                <div class="panel-body">
                    <h2><strong>123</strong></h2>
                </div>
            </div>
        </div>
    
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                投票情况
            </div>
            <div class="panel-body">

            </div>
        </div>
    </div>

    </div>
</div>
@endsection
