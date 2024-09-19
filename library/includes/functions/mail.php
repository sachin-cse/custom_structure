<?php
/*************************************************************************************************
PHP MAILER Send Email Start
*************************************************************************************************/
require(__DIR__.DS."phpmailer".DS."class.phpmailer.php");

function sendEmail($to, $from, $subject, $msg, $opt = []) {
	$arr = array("{to}" => $to);
    $emailTemplate  = strtr(EMAIL_TEMPLATE,$arr);
    $emailBody = str_replace(MSG_BODY, $msg, $emailTemplate);
    
    preg_match('~<(.*?)>~', $from, $fromemail);
	preg_match('~:(.*?)<~', $from, $fromname);
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Mailer 	  = "smtp";
	$mail->SMTPDebug  = 1;  
	$mail->SMTPAuth   = TRUE;
	$mail->SMTPSecure = SMTPSECURE;
	$mail->Port       = PORT;
	$mail->Host       = MAILHOST;
	$mail->Username   = MAILUSERNAME;
	$mail->Password   = MAILPASSWORD;

	$mail->IsHTML(true);
	
	$mail->AddAddress($to, SITE_NAME);
	$mail->SetFrom(trim($fromemail[1]," "), trim($fromname[1]," "));
	$mail->AddReplyTo($to, SITE_NAME);

	if($opt['filepath'] && $opt['filename'])
        $mail->AddAttachment($opt['filepath'], $opt['filename']);
    
    if($opt['bcc'])   
        $mail->AddBCC($opt['bcc'], SITE_NAME);
    
    if($opt['cc'])   
        $mail->AddCC($opt['cc'], SITE_NAME);

	$mail->From 	= trim($fromemail[1]," ");
    $mail->FromName = trim($fromname[1]," ");

	$mail->Subject = $subject;
    $mail->MsgHTML($emailBody);
	$mail->AltBody = $subject;
	
	if(!$mail->Send()) {
	  echo "Error while sending Email.";
	  var_dump($mail);
	} else {
	  return 1;
	}
}

function sendEmailbyphpmailer($to,$to_name,$from,$from_name,$subject,$MessageHTML,$MessageTEXT)
{
    $arr 			= array("{to}" => $to);
    $emailTemplate  = strtr($_SESSION['emailTemplate'],$arr);
    $emailBody 		= str_replace($_SESSION['msgBody'],$MessageHTML,$emailTemplate);    
    
    $mail = new PHPMailer();

    $mail->IsSMTP();                                      // set mailer to use SMTP
    $mail->Host = MAILHOST;  // specify main and backup server
    $mail->SMTPAuth = true;     // turn on SMTP authentication
    $mail->Port     = PORT;       // SMTP PORT
    $mail->Username = MAILUSERNAME;  // SMTP username
    $mail->Password = MAILPASSWORD; // SMTP password

    $mail->From = $from;
    $mail->FromName = $from_name;
    $mail->AddAddress("hasnat@eclickapps.com", SITE_NAME);

    $mail->WordWrap = 50;
    $mail->IsHTML(true);

    $mail->Subject = $subject;
    $mail->Body    = $emailBody;
    $mail->AltBody = $MessageTEXT;

    if(!$mail->Send()) {
       echo "Message could not be sent. <p>";
       echo "Mailer Error: " . $mail->ErrorInfo;
       exit;
    }

    echo 1;
    
}
/*************************************************************************************************
PHP MAILER Send Email Start
*************************************************************************************************/


/*************************************************************************************************
Send Email Start
*************************************************************************************************/
function sendEmailBack($to,$from,$subject,$msg)
{
	$msg .= "<br><br><br><br>---User information--- <br>"; //Title			
	$msg .= "User IP : ".$_SERVER["REMOTE_ADDR"]."<br>"; //Sender's IP			
	$msg .= "Browser info : ".$_SERVER["HTTP_USER_AGENT"]."<br>"; //User agent
	$msg .= "User came from : ".$_SERVER["HTTP_HOST"]; //Referrer
	$msg = str_replace($_SESSION['msgBody'],$msg,$_SESSION['emailTemplate']);	
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	//$headers .= 'To: Administrator <'.$to.'>' . "\r\n";
	$headers .= $from . "\r\n";
	//$headers .= 'Bcc: hasnat@eclickapps.com' . "\r\n";
	@mail($to,$subject,$msg,$headers);
}

function sendEmailOld($to, $from, $subject, $msg, $opt = [])
{
	$arr = array("{to}" => $to);
    $emailTemplate  = strtr(EMAIL_TEMPLATE,$arr);
    $emailBody = str_replace(MSG_BODY, $msg, $emailTemplate);
    
    preg_match('~<(.*?)>~', $from, $fromemail);
    preg_match('~:(.*?)<~', $from, $fromname);
    
    $mail = new PHPMailer();
    
    /*$mail->SMTPSecure = 'ssl';*/
    
    if($opt['filepath'] && $opt['filename'])
        $mail->AddAttachment($opt['filepath'], $opt['filename']);
    
    if($opt['bcc'])   
        $mail->AddBCC($opt['bcc'], SITE_NAME);
    
    if($opt['cc'])   
        $mail->AddCC($opt['cc'], SITE_NAME);
   
    $mail->IsSMTP();                                      // set mailer to use SMTP
    $mail->Host = MAILHOST;  // specify main and backup server
    $mail->SMTPAuth = true;     // turn on SMTP authentication
    $mail->Port     = PORT;       // SMTP PORT
    $mail->Username = MAILUSERNAME;  // SMTP username
    $mail->Password = MAILPASSWORD; // SMTP password
    
    $mail->From 	= trim($fromemail[1]," ");
    $mail->FromName = trim($fromname[1]," ");
    $mail->AddAddress($to, SITE_NAME);
    
    $mail->WordWrap = 50;
    $mail->IsHTML(true);
    
    $mail->Subject = $subject;
    $mail->Body    = $emailBody;
    $mail->AltBody = $subject;
    
    if($mail->Send())
        return 1;
    else
        return 0;
}
/*************************************************************************************************
Send Email End
*************************************************************************************************/
function mailWithAttachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
	$file = $path.$filename;
	$file_size = filesize($file);
	$handle = fopen($file, "r");
	$content = fread($handle, $file_size);
	fclose($handle);
	$content = chunk_split(base64_encode($content));
	$uid = md5(uniqid(time()));
	$name = basename($file);
	$header = "From: ".$from_name." <".$from_mail.">\r\n";
	$header .= "Reply-To: ".$replyto."\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
	$header .= "This is a multi-part message in MIME format.\r\n";
	$header .= "--".$uid."\r\n";
	$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
	$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
	$header .= $message."\r\n\r\n";
	$header .= "--".$uid."\r\n";
	$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
	$header .= "Content-Transfer-Encoding: base64\r\n";
	$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
	$header .= $content."\r\n\r\n";
	$header .= "--".$uid."--";
	@mail($mailto, $subject, "", $header);
}
/*************************************************************************************************
Send Newsletter Start
*************************************************************************************************/
function sendNewsletter($to, $from, $subject, $msg, $subscriberId)
{
   $msg .= "<br><br><br><br>---User information--- <br>"; //Title			
	$msg .= "User IP : ".$_SERVER["REMOTE_ADDR"]."<br>"; //Sender's IP			
	$msg .= "Browser info : ".$_SERVER["HTTP_USER_AGENT"]."<br>"; //User agent
	$msg .= "User came from : ".$_SERVER["HTTP_HOST"]; //Referrer
    $msg .= '<br><br><table style="width:100%;border:none; border-collapse:collapse;">
            <tr><td style="vertical-align:middle; text-align:center; background:#323a45;"><strong style="display:block; font-weight:normal;"><a href="'.SITE_LOC_PATH.'/unsubscribe/?unsubscribe='.base64_encode($to).'&sid='.base64_encode($subscriberId).'" target="_blank" style="color: #fff; text-decoration: none; display:inline-block">Click here to unsubscribe<br/></a></strong></td>
            </tr></table>';    
    $msg  = str_replace("/userfiles/", "http://".$_SESSION["SITE_URL"]."/userfiles/", $msg);
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	//$headers .= 'To: Administrator <'.$to.'>' . "\r\n";
	$headers .= $from . "\r\n";
    //$headers .= 'Bcc: hasnat@eclickapps.com' . "\r\n";
	@mail($to, $subject, $msg, $headers);
}

/*************************************************************************************************
Send Newsletter End
*************************************************************************************************/
function sendmailWithAttachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {

	$file = $path.$filename;
	$file_size = filesize($file);
	$file_type = filetype($file);
	$handle = fopen($file, "r");
	$content = fread($handle, $file_size);
	fclose($handle);
	$encoded_content = chunk_split(base64_encode($content));
	$boundary = md5("sanwebe");
	//header
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: ".$from_name." <".$from_mail.">\r\n";
	$headers .= "Reply-To: ".$replyto."" . "\r\n";
	$headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";
	//plain text
	$body = "--$boundary\r\n";
	$body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
	$body .= chunk_split(base64_encode($message));
	//attachment
	$body .= "--$boundary\r\n";
	$body .="Content-Type: $file_type; name=\"$filename\"\r\n";
	$body .="Content-Disposition: attachment; filename=\"$filename\"\r\n";
	$body .="Content-Transfer-Encoding: base64\r\n";
	$body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n";
	$body .= $encoded_content;
	$sentMail = @mail($mailto, $subject, $body, $headers);
}
?>
