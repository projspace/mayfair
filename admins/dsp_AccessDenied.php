<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<div id="accessDenied">
<?
	error("
	<p><strong>Sorry</strong>, you cannot access this page, this may be because:
	<ul>
		<li>You entered an incorrect username and/or password.  Please re-check your login details and <a href=\"{$config['dir']}index.php?fuseaction=admin.login\">try&nbsp;again</a>.</li>
		<li>Your session has expired.  This happens if you do not access anything in the admin section for a long period.  Please <a href=\"{$config['dir']}index.php?fuseaction=admin.login\">login&nbsp;again</a>.</li>
		<li>You are trying to access something you do not have permission to.  Please note that all operations in ".PRODUCT_NAME." ".PRODUCT_VERSION." are logged and any suspicious activity automatically flagged and investigated as a matter of course.</li>
	</ul>

	","Access Denied");
?>
</div>