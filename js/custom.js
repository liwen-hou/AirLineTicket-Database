$(document).ready(function() {
    //welcome page effects
    $('.quote').fadeIn(1000).delay(4000).fadeOut(1000);
    $('.welcome').delay(4000).fadeOut(1000, function(){
        $('.main-body').fadeIn(2000);
    });
    //facebook share api
    (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "http://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
                    fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
    
    //twitter sahre api
    window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||        {};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=  [];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
    
    //scroll screen up by 50px to avoiding covering by the navbar
    window.addEventListener("hashchange", function () { scrollBy(0, -50) })
    
    //form validation
    $('#send-email').click(function () {
        // Temp store of values
        var email = $("#inputEmail").val();

        // Regex for email validation
        var validateEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

        // Validation: Fail
        if (!validateEmail.test(email)) {
            $('#inputEmail').attr('data-original-title', 'Please fill in a valid email address');
            $('#inputEmail').tooltip('show');
            }

        // Validation: Success
        else {
            var res = "E-mail sent. Thanks for Sharing!";

            //$('#drop').hide();
            $('#submitted').fadeIn(800);
            $('#send-email').hide();
            $('#inputEmail').hide();

            $('#submitted').html(res);
            }
        });

});