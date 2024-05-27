<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '1134116510698377',
            cookie     : true,
            xfbml      : true,
            version    : 'v13.0'
        });
        FB.AppEvents.logPageView();

        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function statusChangeCallback(response)
    {
        console.log(response);
        if(response.status === 'connected'){
            window.location.href = "/webhooks/auth/platform";
        }
    }

    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
    }
</script>

<fb:login-button
    scope="public_profile,email,pages_show_list,pages_manage_metadata,pages_manage_ads,ads_management,leads_retrieval,pages_read_engagement"
    onlogin="checkLoginState();">
</fb:login-button>
