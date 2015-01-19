<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Password Reminder</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<style type="text/css">
		body { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #375F86; background-color: #FFFFFF; vertical-align: top; text-align: justify; margin: 0px; padding: 0px;}
		h1 { font-size: 18px; font-weight: bold; margin: 0px;}
		h2 { font-size: 16px; font-weight: bold; margin: 10px 0px 0px;}
		h3 { font-size: 14px; font-weight: bold; margin: 0px;}
		p { margin-top: 10px; margin-bottom: 0px;}
		a { font-size: 10px; font-weight: bold; color: #243F59; text-decoration: none; cursor: pointer;}
		a:hover { font-size: 10px; font-weight: bold; color: #243F59; text-decoration: underline; cursor: pointer;}
		img { border: none; }
		hr { border-top: 1px solid #375F86; border-bottom: none; border-left: none; border-right: none; height: 1px;}
		hr.faint { border-top: 1px solid #CCCCCC; border-bottom: none; border-left: none; border-right: none; height: 1px;}
		ul { padding: 0px 0px 0px 13px; margin: 0px;}

		.logo { background-color: #598ABB}
		.actions { background-color: #598ABB; vertical-align: bottom;}
		.content { font-size: 10px; color: #375F86; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #375F86; padding: 5px; background-color: #FFFFFF; vertical-align: top; text-align: justify; margin-bottom: 10px; }
		.border { border-left-width: 1px; border-left-style: solid; border-left-color: #375686; border-right-width: 1px; border-right-style: solid; border-right-color: #375686; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #375686; padding: 7px;}
		.right { text-align: right;}
		.center { text-align: center;}
		.copyright { text-align: center;}
		.field { font-size: 10px; font-weight: bold; color: #375F86; text-align: left; vertical-align: middle; padding: 3px; 3px 3px 3px; }
	</style>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td class="logo" width="166"><img src="cid:logo.gif" width="166" height="50" alt="<?= PRODUCT_NAME." ".PRODUCT_VERSION ?>" /></a></td>
		<td class="actions"><img src="cid:gradientbg.jpg" width="200" height="50" /></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td colspan="2"><img src="cid:headergradient.gif" width="100%" height="13" /></td>
	</tr>
	<tr>
		<td colspan="2" class="content">
			<p>Here is your account information:</p>
			<div class="center">
				<table border="0">
					<tr>
						<td class="field">Login URL</td>
						<td><a href="<?= $vars['loginurl'] ?>"><?= $vars['loginurl'] ?></a></td>
					</tr>
					<tr>
						<td class="field">Username</td>
						<td><?= $vars['username'] ?></td>
					</tr>
					<tr>
						<td class="field">Password</td>
						<td><?= $vars['password'] ?></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="copyright"><a href="<?= COPYRIGHT_URL ?>" target="_new"><img src="cid:copyright.png" width="264" height="7" alt="&copy;<?= COPYRIGHT_YEAR ?> <?= COPYRIGHT ?>" /></a></td>
	</tr>
</table>
</body>
</html>