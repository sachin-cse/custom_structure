<?php defined('BASE') or exit('No direct script access allowed');
if (isset($this->_request['pageType']))
    echo '</div></div>'; ?>
</main>

<footer class="mainFooter">
    <div class="container">
        <nav class="fnav">
            <?php $this->hook('theme', 'nav', array('permalink' => 'footer', 'css' => 'clearfix')); ?>

        </nav>
        <section class="copyright">
            <p><?php echo COPY_RIGHT; ?></p>
            <p><?php echo DEVELOPED_BY; ?></p>
        </section>
    </div>
</footer>

<?php echo $this->essentialFooter(); ?>

<script defer type="text/javascript">
    document.body.className = document.body.className.replace("clicked", "");

    /** FACEBOOK_LOGIN START */
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '<?php echo FB_APPID;?>',
            cookie     : true,
            xfbml      : true,
            version    : '<?php echo FB_VERSION;?>'
        });
    };

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function testAPI() {
        
        FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
        function (response) {
            //document.getElementById('fblogout').setAttribute("onclick","fbLogout()");
            var btn      = '',
                form     = '',
                formData = {'SourceForm': 'socialLogin', 'rgstrType': 'FACEBOOK', 'response': response},
                msg      = $('#status'),
                url      = "<?php echo SITE_LOC_PATH.'/ajx_action/dashboard/';?>";
            ajaxFormSubmit(form, formData, btn, msg, url, false);
        });
    }

    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected')
                return 1;
            else
                return 0;
        });
    }

    function fbLogout() {
        FB.logout(function() {
            document.getElementById('fbLink').setAttribute("onclick","fbLogin()");
        });
    }
    /** FACEBOOK_LOGIN END */
</script>

</body>

</html>