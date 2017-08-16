@extends('layouts.trntbl')

@section('title', 'Choose a blog')

@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <h3 class="masthead-brand">{{ strtoupper(env('APP_NAME')) }}</h3>
        </div>
    </div>
    <div class="inner cover">
        <h1 class="cover-heading">{{ strtoupper(env('APP_NAME')) }}</h1>
        <p class="lead">
            Hello! This page was made because <a href="http://www.trntbl.me">trntbl.me</a> is currently down.
            As long as this is the case, this page is intended to give you at least the basic functionality of trntbl.
        </p>
        <form class="lead form-inline" action="" method="POST" id="user-form">
            <div class="form-group">
                <input type="text" class="form-control" id="username" placeholder="Username">
            </div>
            <button type="submit" class="btn btn-default" id="btn-listen">Listen</button>
        </form>
        @if(isset($error))
                <p class="text-danger lead">
                    {{ $error }}
                </p>
        @endif
    </div>
    <div class="mastfoot">
        <div class="inner">
            <p>Original idea by <a href="http://blog.trnrbl.me">trntbl</a>, this page was made by <a href="http://egoisticalgoat.tumblr.com">me</a>.</p>
            <p>If you have any suggestions/bugs/etc., contact me at <a href="http://egoisticalgoat.tumblr.com">my blog</a>.
            I also have a list of <a href="{{ url('/my-site/known-bugs') }}">known bugs</a>.</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#user-form').submit(function () {
                $('#user-form').attr('action', "{{ url('/') }}/" + $('#username').val().toLowerCase());
            })
        });
    </script>
@endsection