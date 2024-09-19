<?php defined('BASE') OR exit('No direct script access allowed');?> 

<div class="form_wrap mt30">
    <?php echo ($data['settings']['formHeading'])? '<h2 class="subheading">'.$data['settings']['formHeading'].'</h2>':'';?>
    <form name="contact" method="POST">
        <ul class="row">
            <li class="col-sm-6">
                <label class="labelWrap">
                    <span>Name</span>
                    <input type="text" name="name">
                </label>
                <label class="labelWrap">
                    <span>Email Id</span>
                    <input type="text" name="email">
                </label>
                <label class="labelWrap">
                    <span>Phone No</span>
                    <input type="text" name="phone">
                </label>
            </li>
            <li class="col-sm-6">
                <label class="labelWrap">
                    <span>Message</span>
                    <textarea class="row3" name="message"></textarea>
                </label>
            </li>
            <li class="col-sm-6">
                <div class="labelWrap">
                    <?php //echo ($data['settings']['isCaptcha'])? '<div class="captcha_img"><div data-sitekey="'.$data['captcha']['googleSiteKey'].'" class="g-recaptcha"></div></div>' : '';?>
                    <div class="btn_wr">
                        <button type="submit" class="btn" data-action='submit' onclick="submitForm(this, event);">Submit</button>
                        <input type="hidden" name="SourceForm" value="contact">
                        <input type="hidden" name="goto" value="<?php echo SITE_LOC_PATH.'/'.$data['pageData']['permalink'].'/thank-you/';?>">
                    </div>
                </div>
            </li>
        </ul>
        <div class="ErrInqMsg"></div>
    </form>
</div>

<script defer type="text/javascript">
    function submitForm(obj, event) { 
        event.preventDefault();

        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $data['captcha']['googleSiteKey']; ?>', {action: 'submit'}).then(function(token) {
                var btn  = $(obj), 
                form     = btn.parents('form'), 
                formData = form.serialize() + '&g-recaptcha-response=' + token,
                msg      = form.find('.ErrInqMsg'),
                url      = "<?php echo SITE_LOC_PATH.'/ajx_action/'.$data['pageData']['permalink'];?>/";

                ajaxFormSubmit(form, formData, btn, msg, url, false);
            });
        });
    }
</script>