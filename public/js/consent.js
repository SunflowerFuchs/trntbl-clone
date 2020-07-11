var Consent = {
    cookie: "cookieConsent",

    // Sets a cookie granting/denying consent
    setConsentCookie: function(consent) {
        if (consent === false) {
            deleteAllCookies();
        }

        var exdays = 30*12; // 1 year
        if (consent === true) { // if consent was explicitly given, just delete the cookie again
            exdays = -1;
        }

        // map to string for clean saving
        consent = consent === true ? "true" : "false";
        setCookie(Consent.cookie, consent, exdays);
    },

    hasConsent: function() {
        return getCookie(Consent.cookie) !== "false";
    },

    changeCookieConsent: function() {
        Consent.setConsentCookie(!Consent.hasConsent());
    },

    initButtons: function() {
        Consent.updateConsentButton();
        $(".changeCookieConsent").click(function() {
            Consent.changeCookieConsent();
            Consent.updateConsentButton();
        });
    },

    updateConsentButton: function() {
        if (Consent.hasConsent()) {
            $(".changeCookieConsent").text('Revoke cookie consent');
        } else {
            $(".changeCookieConsent").text('Give cookie consent');
        }
    },
};
