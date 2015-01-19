<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain -->

<meta name="author" content="Webstars ltd // staff@webstarsltd.com" />

<?=$elems->qry_ShopVariable('fb_meta') ?>

<link href="<?=$config['layout_dir'] ?>css/yui3.css" media="screen" rel="stylesheet">
<link href="<?=$config['layout_dir'] ?>css/styles.css" media="screen" rel="stylesheet">

<!-- JavaScript libraries -->
<script src="<?=$config['layout_dir'] ?>js/libs/css_browser_selector.js" type="text/javascript"></script>
<script src="<?=$config['layout_dir'] ?>js/libs/modernizr-1.7.min.js" type="text/javascript"></script>
<script src="<?=$config['layout_dir'] ?>js/libs/jquery-latest.min.js" type="text/javascript"></script>
<script src="<?=$config['layout_dir'] ?>js/libs/cufon.js" type="text/javascript"></script>
<!--[if IE 6]>
<script src="<?=$config['layout_dir'] ?>js/libs/dd_belatedpng.js" type="text/javascript"></script>
<![endif]-->

<script src="<?=$config['layout_dir'] ?>js/plugins.js" type="text/javascript"></script>
<script src="<?=$config['layout_dir'] ?>js/scripts.js" type="text/javascript"></script>

<script type="text/javascript" src="<?=$config['protocol'] ?>use.typekit.com/sux0ngt.js"></script>
<script type="text/javascript">try {Typekit.load();} catch( e ) {}</script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var config_dir = '<?=$config['dir'] ?>';
/* ]]> */
</script>
<? if(!($Fusebox["circuit"] == 'shop' && $Fusebox['fuseaction'] == 'finished')): ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33040780-1']);
  _gaq.push(['_setDomainName', '.bloch-usa.dev4.clientproof.co.uk']);
  _gaq.push(['_setAllowLinker', true]);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<? endif; ?>

<meta name="viewport" content="width=device-width" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	(function(document,navigator,standalone) {
            // prevents links from apps from oppening in mobile safari
            // this javascript must be the first script in your <head>
            if ((standalone in navigator) && navigator[standalone]) {
                var curnode, location=document.location, stop=/^(a|html)$/i;
                document.addEventListener('click', function(e) {
                    curnode=e.target;
                    while (!(stop).test(curnode.nodeName)) {
                        curnode=curnode.parentNode;
                    }
                    // Condidions to do this only on links to your own app
                    // if you want all links, use if('href' in curnode) instead.
                    if('href' in curnode && ( curnode.href.indexOf('http') || ~curnode.href.indexOf(location.host) ) ) {
                        e.preventDefault();
                        location.href = curnode.href;
                    }
                },false);
            }
        })(document,window.navigator,'standalone');
/* ]]> */
</script>