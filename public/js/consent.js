$(document).ready(function() {
    var consentIsSet = "unknown";
    var cookieBanner = "#cookieBanner";
    var cookieBannerClose = "#cookieBannerClose";
    var consentString = "cookieConsent=";

    // Sets a cookie granting/denying consent, and displays some text on console/banner
    function setCookie(console_log, banner_text, consent) {
        console.log(console_log);
        $(cookieBanner).text(banner_text);
        $(cookieBanner).fadeOut(2500);
        var d = new Date();
        var exdays = 30*12; //  1 year
        d.setTime(d.getTime()+(exdays*24*60*60*1000));
        var expires = "expires="+d.toGMTString();
        document.cookie = consentString + consent + "; " + expires + ";path=/";
        consentIsSet = consent;
    }

    function denyConsent() {
        // disabled deny cookie; if the user denies, we don't want ANY cookies set
        // setCookie("Consent denied", "You disallowed the use of cookies.", "false");
        // Unbind consent-granting actions
        $(window).unbind("scroll");
        $("a:not(.noconsent)").unbind("click");
    }

    function grantConsent() {
        if (consentIsSet == "true") return; // Don't grant twice
        setCookie("Consent granted", "Thank you for accepting cookies.", "true");
        doConsent();
    }

    $(cookieBannerClose).click(function() {
        grantConsent();
    });

    // Run the consent code. We may be called either from grantConsent() or
    // from the main routine
    function doConsent() {
        console.log("Consent was granted");
    }

    // main routine
    //
    // First, check if cookie is present
    var cookies = document.cookie.split(";");
    for (var i = 0; i < cookies.length; i++) {
        var c = cookies[i].trim();
        if (c.indexOf(consentString) == 0) {
            consentIsSet = c.substring(consentString.length, c.length);
        }
    }

    if (consentIsSet == "unknown") {
        $(cookieBanner).fadeIn();
        // The two cases where consent is granted: scrolling the window or clicking a link
        // Don't set cookies on the "cookies page" on scroll
        var pageName = location.pathname.substr(location.pathname.lastIndexOf("/") + 1);
        if (pageName != "my-site/cookies") $(window).scroll(grantConsent); // XXX you may edit this name
        $("a:not(.noconsent)").click(grantConsent);
        $(".denyConsent").click(denyConsent);
        // allow re-enabling cookies
        $(".allowConsent").click(grantConsent);
    }
    else if (consentIsSet == "true") doConsent();
});
