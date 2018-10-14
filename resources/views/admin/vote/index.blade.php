@extends('layouts.admin')

@section('content')
<div class="container" style="text-align: center;">
    <div class="row">
        <div class="col-md-12">
        @if (session('status'))
            <div class="alert alert-success">
                <h3>{{ session('status') }}</h3>
                <a href="{{ route('admin.logout') }}"
                >退出登录</a>
            </div>
        @endif
        @include('flash::message')
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    签到人数
                </div>
                <div class="panel-body">
                    <h2><strong>{{ $signnum }}</strong></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    投票总人数 / 未投票人数
                </div>
                <div class="panel-body">
                    <h2><strong>{{ $votepeople['voteTotal'] }} / 
                    {{ $votepeople['notVote'] }}</strong></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    生成投票链接/二维码
                </div>
                <div class="panel-body">
                    <a href="{{ route('admin.vote.show_vote_url', $id) }}"><h2><strong>点击生成</strong></h2></a>
                </div>
            </div>
        </div>
    
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                代表管理
            </div>
            <div class="panel-body">
                <a href="{{ route('admin.member.index', 1) }}">
                    <button class="btn btn-default">新增代表</button>
                </a>
                <a href="{{ route('admin.excel.import.index', [$id, 1])}}">
                    <button class="btn btn-default">导入代表</button>
                </a>
                <a href="{{ route('admin.excel.model.export', 1) }}">
                    <button class="btn btn-default">导入模版</button>
                </a> 
                <a href="{{ route('vote.show.realtime', $id) }}">
                    <button class="btn btn-default">查看票数</button>
                </a> 
                <br><br>
                <p><strong>表格中是否签到/投票 => 1表示已完成, 0表示未完成</strong></p>
                <table class="table table-bordered table-hover" 
                    id="behalf">
                    <thead>
                    <tr>
                        <th>代表姓名</th>
                        <th>代表学号</th>
                        <th>是否签到</th>
                        <th>是否投票</th>
                        <th>操作</th>
                    </tr>
                    @if(count($behalf) > 0)
                    @foreach($behalf as $k => $v)
                    <tr>
                        <td>
                            {{ $v['name'] }}
                        </td>
                        <td>
                            {{ $v['student_id'] }}
                        </td>
                        <td>
                            {{ $v['is_sign'] }}
                        </td>
                        <td>
                            {{ $v['is_vote'] }}
                        </td>
                        <td>
                            TODO
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    </thead>
                </table>
                {{ $behalf->links() }}   
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                候选人管理
            </div>
            <div class="panel-body">
                <a href="{{ route('admin.member.index') }}">
                    <button class="btn btn-default">新增候选人</button>
                </a>
                <a href="{{ route('admin.excel.import.index', [$id, 0]) }}">
                    <button class="btn btn-default">导入候选人</button>
                </a>
                <a href="{{ route('admin.excel.model.export', 0) }}">
                    <button class="btn btn-default">导入模版</button>
                </a>
                <a href="{{ route('admin.flush.cache') }}">
                    <button class="btn btn-danger">清空缓存</button>
                </a>
                <br><br>
                <p><strong>修改参与投票的候选人后, 记得清空缓存</strong></p>
                <table class="table table-bordered table-hover" 
                    id="vote">
                    <thead>
                    <tr>
                        <th>候选人姓名</th>
                        <th>候选人票数</th>
                        <th>操作</th>
                    </tr>
                    @if(count($vote) > 0)
                    @foreach($vote as $k => $v)
                    <tr>
                        <td>
                            {{ $v['name'] }}
                        </td>
                        <td>
                            {{ $v['vote_num'] }}
                        </td>
                        <td>
                            TODO
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    </thead>
                </table>
                {{ $vote->links() }}
            </div>
        </div>
    </div>

    </div>
</div>
@endsection
