<?
	if($_POST['generate']=="on")
	{
		$pwmaker=new Password();
		$password=$pwmaker->generate(8);
		$match=true;
	}
	else
	{
		if($_POST['password']==$_POST['confirm'])
			$match=true;
		else
			$match=false;
	}
?>