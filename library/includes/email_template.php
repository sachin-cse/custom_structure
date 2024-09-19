<?php
define('MSG_BODY', '#~#MailBody#~#');
define('EMAIL_TEMPLATE', '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#f2f2f2; font-family:Verdana, Geneva, sans-serif; font-size:14px; line-height:22px; color:#666;">
	<tr>
		<td>
			<table width="600" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
				<tr>
					<td style="padding:30px 0; text-align:center;"><a href="'.SITE_LOC_PATH.'/"><img src="'.STYLE_FILES_SRC.'/images/logo.png" alt="'.SITE_NAME.'"></a></td>
				</tr>
				<tr>
					<td style="background:#ffffff; padding: 40px;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr>
								<td>
									'.MSG_BODY.'
								</td>
							</tr>
                            <tr>
								<td style="padding:30px 0 0;">
									<p style="margin:0; padding-bottom:15px;">Thanks,</p>
									<p style="margin:0;">The '.SITE_NAME.' Team</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="padding:30px 0; text-align:center;">&copy; '.date('Y').' <a href="'.SITE_LOC_PATH.'/" style="color:#666; text-decoration:none">'.SITE_NAME.'</a>. All Rights Reserved.</td>
				</tr>
			</table>
		</td>
	</tr>
</table>');
?>