<?php
if (!defined('IN_WPRO')) exit();
if (WPRO_SESSION_ENGINE=='PHP'&&!isset($_SESSION)) {
	
	/*
	* WysiwygPro custom session handler setup file.
	* If your application uses custom session handlers (http://www.php.net/manual/en/function.session-set-save-handler.php) 
	* then include your session handler functions into this file.
	* 
	* Or if your session requires a specific name you will need to set it here.
	*
	* If you want to add your application's user authentication routine to WysiwygPro then it should be added to this file.
	*
	* SIMPLIFIED EXAMPLE:
	*/

        $pj_sess_save_path="../../../sess";
function pj_open($save_path, $session_name)
{
  return(true);
}

function pj_close()
{
  return(true);
}

function pj_read($id)
{
  global $pj_sess_save_path;

  $sess_file = "$pj_sess_save_path/sess_$id";
  return (string) @file_get_contents($sess_file);
}

function pj_write($id, $sess_data)
{
  global $pj_sess_save_path;

  $sess_file = "$pj_sess_save_path/sess_$id";
  if ($fp = @fopen($sess_file, "w")) {
    $return = fwrite($fp, $sess_data);
    fclose($fp);
    return $return;
  } else {
    return(false);
  }

}

function pj_destroy($id)
{
  global $pj_sess_save_path;

  $sess_file = "$pj_sess_save_path/sess_$id";
  return(@unlink($sess_file));
}

function pj_gc($maxlifetime)
{
  global $pj_sess_save_path;

  foreach (glob("$pj_sess_save_path/sess_*") as $filename) {
    if (filemtime($filename) + $maxlifetime < time()) {
      @unlink($filename);
    }
  }
  return true;
}

session_set_save_handler("pj_open", "pj_close", "pj_read", "pj_write", "pj_destroy", "pj_gc");

	
	// include custom session handler functions:
//	include_once('mySessionHandlers.php');
//	session_set_save_handler("myOpen", "myClose", "myRead", "myWrite", "myDestroy", "myGC");
	
	// start the session with a specific name if required:
//	session_name('SessionName');
//	session_start();
	
	session_start();
	
}
?>
