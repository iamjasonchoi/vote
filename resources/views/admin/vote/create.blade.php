@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('flash::message')
        </div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">创建一个新的投票项目</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('admin.vote.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">项目名称</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('start') ? ' has-error' : '' }}">
                            <label for="start" class="col-md-4 control-label">开始时间</label>

                            <div class="col-md-6">
                                <input id="start" type="text" class="form-control time-select" name="start" value="{{ old('start') }}" required autofocus>

                                @if ($errors->has('start'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('start') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('end') ? ' has-error' : '' }}">
                            <label for="end" class="col-md-4 control-label">截止时间</label>

                            <div class="col-md-6">
                                <input id="end" type="text" class="form-control time-select" name="end" value="{{ old('end') }}" required autofocus>

                                @if ($errors->has('end'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('end') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 后续添加海报 项目简介   --}}

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    创建
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('laydate/laydate.js') }}"></script>

<script>
    laydate.render({
      elem: '#start',
      type: 'datetime'
    });
    laydate.render({
      elem: '#end',
      type: 'datetime'
    });
</script>
@endsection
