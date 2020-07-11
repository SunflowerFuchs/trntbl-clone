@extends('layouts.general')

@section('title', strtoupper(env('APP_NAME')))

@section('content')
    <h1 class="cover-heading">{{ strtoupper(env('APP_NAME')) }}</h1>
    <p class="lead">
        This app will generate you a playlist out of all of your tumblr audio posts, be it original or retweeted.<br/>
        Just enter your username, and off you go.
    </p>
    <form class="lead form-inline" action="" method="POST" id="user-form">
        <div class="form-group">
            <input type="text" class="form-control" id="username" placeholder="Username">
            <input type="text" class="form-control" id="tag" placeholder="Tag (optional)">
        </div>
        <button type="submit" class="btn btn-default allowConsent" id="btn-listen">Listen</button>
    </form>
    @if(isset($error))
        <p class="text-danger lead">
            {{ $error }}
        </p>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var userform  = $('#user-form');
            var userfield = $('#username');
            var tagfield  = $('#tag');
            userform.submit(function () {
                var usernameregex = /(?:https?:\/\/)?([\w\-]+)(?:\.tumblr\.com\/?)?/;
                var username = userfield.val().toLowerCase().replace(usernameregex, '$1');
                var tag      = tagfield.val().toLowerCase();
                if (username !== "") {
                    userform.attr('action', "{{ url('/') }}/" + username + "/" + encodeURIComponent(tag));
                    setCookie("username", username, 90);
                    setCookie("tag", tag, 90);
                }
            });

            var userCookie = getCookie("username");
            if (userCookie !== "") {
                userfield.val(userCookie);
            }

            var tagCookie = getCookie("tag");
            if (tagCookie !== "") {
                tagfield.val(tagCookie);
            }
        });
    </script>
@endsection
