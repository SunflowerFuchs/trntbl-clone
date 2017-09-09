@extends('layouts.trntbl')

@section('title', 'Cookie policy')

@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <h3 class="masthead-brand"><a href="{{ url('/') }}">{{ strtoupper(env('APP_NAME')) }}</a></h3>
        </div>
    </div>
    <div class="inner cover cookies">
        <h1 class="cover-heading">Cookie Policy for {{ env('APP_NAME') }}</h1>
        <h2>What Are Cookies</h2>
        As is common practice with almost all professional websites this site uses cookies, which are tiny files that are downloaded to your computer, to improve your experience. This page describes what information they gather, how we use it and why we sometimes need to store these cookies. We will also share how you can prevent these cookies from being stored however this may downgrade or 'break' certain elements of the sites functionality.<br />
        <br />
        For more general information on cookies see the <a href="http://en.wikipedia.org/wiki/HTTP_cookie">Wikipedia article on HTTP Cookies...</a><br />
        <h2>How We Use Cookies</h2>
        We use cookies for a variety of reasons detailed below. Unfortunately is most cases there are no industry standard options for disabling cookies without completely disabling the functionality and features they add to this site. It is recommended that you leave on all cookies if you are not sure whether you need them or not in case they are used to provide a service that you use.<br />
        <h2>Disabling Cookies</h2>
        You can prevent the setting of cookies by adjusting the settings on your browser (see your browser Help for how to do this). Be aware that disabling cookies will affect the functionality of this and many other websites that you visit. Disabling cookies will usually result in also disabling certain functionality and features of the this site. Therefore it is recommended that you do not disable cookies.<br />
        <h2>The Cookies We Set</h2>
        In order to provide you with a great experience on this site we provide the functionality to set your preferences for how this site runs when you use it (e.g. setting volume levels and shuffle function). In order to remember your preferences we need to set cookies so that this information can be called whenever you interact with a page is affected by your preferences.<br />
        <h2>More Information</h2>
        Hopefully that has clarified things for you and as was previously mentioned if there is something that you aren't sure whether you need or not it's usually safer to leave cookies enabled in case it does interact with one of the features you use on our site. However if you are still looking for more information then you can contact us through one of our preferred contact methods.<br />
        <br />
        <strong>Email:</strong> <a href="mailto:egoisticalgoat@gmail.com">egoisticalgoat@gmail.com</a> <br />
        <strong>My blog:</strong> <a href="http://egoisticalgoat.tumblr.com">egoisticalgoat.tumblr.com</a><br />
        <br />
    </div>
@endsection