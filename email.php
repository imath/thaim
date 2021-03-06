<?php
/**
 * Thaim email reply template
 * Based on https://github.com/InterNations/antwort
 *
 * @package thaim
 *
 * @since  2.1.0
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
	<meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
	<title>{{pagetitle}}</title>

	<style type="text/css">
body {
	margin: 0;
	padding: 0;
	-ms-text-size-adjust: 100%;
	-webkit-text-size-adjust: 100%;
}
table {
	border-spacing: 0;
}
table td {
	border-collapse: collapse;
}
.ExternalClass {
	width: 100%;
}
.ExternalClass,
.ExternalClass p,
.ExternalClass span,
.ExternalClass font,
.ExternalClass td,
.ExternalClass div {
	line-height: 100%;
}
.ReadMsgBody {
	width: 100%;
	background-color: #ebebeb;
}
table {
	mso-table-lspace: 0pt;
	mso-table-rspace: 0pt;
}
img {
	-ms-interpolation-mode: bicubic;
}
.yshortcuts a {
	border-bottom: none !important;
}

.container-padding.header {
	line-height: 80px;
}

.container-padding.header span {
	color: #23282d;
}

hr {
	margin: 18px 0;
	border: 0;
    height: 0;
    border-top: 1px solid #f0f0f0;
}

@media screen and (max-width: 599px) {
	.force-row,
	.container {
		width: 100% !important;
		max-width: 100% !important;
	}
}
@media screen and (max-width: 400px) {
	.container-padding {
		padding-left: 12px !important;
		padding-right: 12px !important;
	}
}
.ios-footer a {
	color: #aaaaaa !important;
	text-decoration: underline;
}
</style>
</head>

<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- 100% background wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
	<tr>
		<td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

			<br>

			<!-- 600px container (white background) -->
			<table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
				<tr>
					<td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;padding-bottom:12px;color:#0073aa;padding-left:24px;padding-right:24px">
						<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td style="font-family:Helvetica, Arial, sans-serif;color:#C6C6C6;font-size:48px;font-weight:bold;vertical-align:middle;text-align:center"><?php thaim_blogname() ;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
	<table border="0" width="100%" cellpadding="0" cellspacing="0" class="container" style="width:100%;max-width:100%">
		<tr>
			<td align="left" style="font-family:Helvetica, Arial, sans-serif;padding-bottom:12px;color:#23282d;"><strong><?php esc_html_e( 'Your message:', 'thaim' ); ?></td>
		</tr>
		<tr>
			<td style="font-family:Helvetica, Arial, sans-serif;padding-bottom:12px;">{{question}}</td>
		</tr>
	</table>

	<hr/>

	<table border="0" width="100%" cellpadding="0" cellspacing="0" class="container" style="width:100%;max-width:100%">
		<tr>
			<td style="font-family:Helvetica, Arial, sans-serif;padding-bottom:12px;color:#23282d;"><strong><?php esc_html_e( 'My reply:', 'thaim' ); ?></td>
		</tr>
		<tr>
			<td style="font-family:Helvetica, Arial, sans-serif;padding-bottom:12px;">{{reply}}</td>
		</tr>
	</table>
</div>

					</td>
				</tr>
				<tr>
					<td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
						<br><br>

						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#aaaaaa"><?php echo esc_html( home_url() ); ?></a><br>

						<br><br>

					</td>
				</tr>
			</table>
<!--/600px container -->


		</td>
	</tr>
</table>
<!--/100% background wrapper-->

</body>
</html>
