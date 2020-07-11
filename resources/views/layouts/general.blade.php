<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, shrink-to-fit=no">

    @section('stylesheets')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('/') }}/css/cover.css">
    @show

    <title>@yield('title')</title>
</head>
<body>

    <div class="site-wrapper">
        <div class="site-wrapper-inner">
            <div class="cover-container">

                @yield('content')

            </div>
        </div>

        <div id="cookieBanner">
            <div id="cookieBannerClose"><a href="#">x</a></div>
            By continuing to use this site you consent to the use of cookies on your device as described in our cookie policy unless you have disabled them.
            <div id="cookieBannerActions">
                <a class="noconsent" href="{{ url('/my-site/cookies') }}">Learn about this website's cookies</a>
                &mdash;
                <a class="denyConsent noconsent" href="#">Disallow cookies</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="{{ asset('js/cookies.js') }}?cbr={{ File::lastModified( public_path() . '/js/cookies.js' )  }}"></script>
    <script src="{{ asset('js/consent.js') }}?cbr={{ File::lastModified( public_path() . '/js/consent.js' )  }}"></script>

    @yield('scripts')

</body>
</html>
