<h2>My Platform</h2>

<script type="text/javascript" src="{{ asset('auth/plugins/jquery-3.3.1/jquery-3.3.1.js') }}"></script>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '1134116510698377',
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
        if(response == null || response.status !== 'connected'){
            window.location.href = "/webhooks/auth";
        }
    }

    function subscribeApp(page_id, page_access_token) {
        console.log('Subscribing page to app! ' + page_id);
        FB.api('/v13.0/oauth/access_token','get', {
            grant_type: "fb_exchange_token",
            client_id:  "1134116510698377",
            client_secret: "5caef6b92dbc2778bf06a65d35b889b8",
            fb_exchange_token: page_access_token
            },
            function(response) {
                $.ajax({
                    url: "/webhooks/auth/generateToken?token="+ response.access_token, //page_access_token
                    data: null,
                    type: "GET",
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.Success) {
                            alert("Token generado correctamente!");
                        } else {
                            alert("Error al generar el Token!");
                        }
                    },
                    error: function(){
                        alert("Error al generar el Token!");
                    },
                    beforeSend: function () {

                    },
                    complete: function () {

                    }
                });
            }
        );
        
        /*FB.api('/' + page_id + '/subscribed_apps','post', {access_token: page_access_token, subscribed_fields: ['leadgen']}, function(response) {
            if(response.success){
                alert('Successfully subscribed page');
            }else{
                console.log(response);
                alert('ocurrió un error');
            }
        }
      );*/
    }

    // Only works after `FB.init` is called
    function myFacebookLogin() {
        FB.api('/me/accounts', function(response) {
            console.log('Successfully retrieved pages', response);
            var pages = response.data;
            var ul = document.getElementById('list');
            for (var i = 0, len = pages.length; i < len; i++) {
                var page = pages[i];
                var li = document.createElement('li');
                var a = document.createElement('a');
                a.href = "#";
                a.onclick = subscribeApp.bind(this, page.id, page.access_token);
                a.innerHTML = page.name;
                li.appendChild(a);
                ul.appendChild(li);
            }
        });

        /*FB.login(function(response){
            console.log('Successfully logged in', response);

        }, {scope: 'manage_pages'});*/
    }
</script>
<button onclick="myFacebookLogin()">Login with Facebook</button>
<ul id="list"></ul>
