<?
	require("../lib/cfg_Config.php");
	$vcfg = array();
	
	// database connection details
	$vcfg['db']['driver']			=	$config['db']['driver'];
	$vcfg['db']['server']			=	$config['db']['server'];
	$vcfg['db']['username']			=	$config['db']['username'];
	$vcfg['db']['password']			=	$config['db']['password'];
	$vcfg['db']['database']			=	$config['db']['database'];
	
	//cookie details
	$vcfg['cookie']['timeout']		=	0;
	$vcfg['cookie']['session_id']	=	"validator_session_id";
	$vcfg['cookie']['path']			=	$config['dir'];
	
	//security
	$vcfg['sha_key']['in']			=	'abcdef';
	$vcfg['sha_key']['out']			=	'abcdef';
	$vcfg['contact']				=	'marian@webstarsltd.com';
	
	//p3p
	$vcfg['p3p']					=	'CP="NOI NID ADMa OUR IND UNI COM NAV"';
?>