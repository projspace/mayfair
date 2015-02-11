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
<?
	error_reporting(E_ALL);
	$old_error_handler = set_error_handler("errorHandler");
	function errorHandler($no,$string,$file,$line)
	{
		switch ($no)
		{
			case E_ERROR:
				showError("Fatal Error",$file,$line,$string);
				break;

			case E_WARNING:
				showError("Warning",$file,$line,$string);
				break;

			case E_PARSE:
				showError("Parser Error",$file,$line,$string);
				break;

			case E_COMPILE_ERROR:
				showError("Compile Error",$file,$line,$string);
				break;

			case E_COMPILE_WARNING:
				showError("Compile Warning",$file,$line,$string);
				break;

			case E_USER_ERROR:
				showError("Library Error",$file,$line,$string);
				break;

			case E_USER_WARNING:
				showError("Library Warning",$file,$line,$string);
				break;

			default:
				//echo showError("Notice",$file,$line,$string);
				break;
		}
		return true;
	}

	function showError($type,$file,$line,$string)
	{
?>
<style type="text/css">
	div.systemError {
		font-family: Verdana, Arial, sans-serif !important;
		width: 600px;border-top: 1px solid #E0B2B2 !important;
		border-left: 1px solid #E0B2B2 !important;
		border-bottom: 1px solid #990000 !important;
		border-right: 1px solid #990000 !important;
		background: #FFF0F0 !important;
		color: #990000 !important;
		padding: 5px !important;
		margin: 10px 0px 10px 0px !important;
		text-align: left !important;
	}
	div.systemError h3 {
		font-size: 12px;
		font-weight: bold;
		color: #990000;
		margin: 0px;
		text-transform: none;
	}
	div.systemError hr {
		border-bottom: 1px solid #E0B2B2;
		border-top: none;
		border-left: none;
		border-right: none;
		height: 1px;
		margin: 0px;
	}
	div.systemError p {
		font-size: 10px;
		margin: 4px 0px 4px 0px;
		color: #990000;
	}
	div.systemError table {
		width: 100%;
		font-size: 10px;
		border-collapse: collapse;
	}
	div.systemError table td {
		padding-right: 10px;
		text-align: left;
		color: #990000;
	}
	div.systemError table th {
		padding-right: 10px;
		text-align: left;
		background: #990000;
		color: #FFFFFF;
	}
	div.systemError .number {
		text-align: right;
	}
</style>
<div class="systemError">
	<h3><?= $type ?></h3><hr />
	<p>occured in <?= $file ?> on line <?= $line ?></p>
	<p><?= $string ?></p>
	<table>
		<tr>
			<th>File</th>
			<th class="number">Line</th>
			<th>Function</th>
		</tr>
<?
		$trace=debug_backtrace();
		for($i=count($trace)-1;$i>=0;$i--)
		{
?>
		<tr>
			<td><?= preg_replace("^.*[\\/]([^\\/]*)$","\\1",$trace[$i]['file']) ?></td>
			<td class="number"><?= $trace[$i]['line'] ?></td>
			<td><?= $trace[$i]['function'] ?></td>
		</tr>
<?
			if($trace[$i]['line']==$line)
				break;
		}
?>
	</table>
</div>
<?
	}
?>