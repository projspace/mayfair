<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Blue Leaves</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=iso-8859-1" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta http-equiv="imagetoolbar" content="no" />
	<link href="<?= $config['dir'] ?>layout/templates/blueleaves/blueleaves.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="container">
		<div id="header">
			<h1 title="<?= $config['company'] ?>"><?= $config['company'] ?></h1>
			<h2 title="<?= $config['slogan'] ?>"><?= $config['slogan'] ?></h2>
		</div>
		<div id="navigation">
			<? $elems->navigation(); ?>
		</div>
		<div id="sidebar">
			<div class="gradient">
				<? $elems->cart(); ?>
			</div>
			<div class="gradient">
				<? $elems->categories(); ?>
			</div>
			<div class="gradient">
				<? $elems->search(); ?>
			</div>
		</div>
		<div id="content">
			<?= $elems->TrailHome(); ?>
			<div class="gradient">
				<?= $elems->content(); ?>
			</div>
		</div>
		<div id="footer">
			<a href="<?= $config['dir'] ?>http://validator.w3.org/check/referer" title="Validates as XHTML 1.1">XHTML</a> |
			<a href="<?= $config['dir'] ?>http://jigsaw.w3.org/css-validator/check/referer?warning=no&amp;profile=css2" title="Validates as CSS">CSS</a> |
			<a href="<?= $config['dir'] ?>&#109;a&#105;l&#116;&#111;:&#106;&#101;&#110;&#110;&#97;&#64;&#103;&#114;&#111;&#119;&#108;
			&#100;&#101;&#115;&#105;&#103;&#110;&#46;&#99;&#111;&#46;&#117;&#107;" title="Contact growldesign">Contact</a>	<br/>
			Copyright &copy; 2005 Company. All Rights Reserved.<br />
			<!-- If you would like to use this template, I ask that you keep the following line of code intact -->
			Design by <a href="<?= $config['dir'] ?>http://www.growldesign.co.uk">growldesign</a>
		</div>
	</div>
</body>
</html>
