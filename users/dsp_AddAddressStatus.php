<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#fancybox-content', parent.document).css('height', ($('#page-wrapper').height()+20)+'px');
		parent.$.fancybox.center(true);
		
		$('#btn_close').click(function(){
			parent.$.fancybox.close();
		});
	});
/* ]]> */
</script>
<? if($_REQUEST['ajax']): ?>
<div class="overlay">
	<div class="header content-box"><h1>Add address</h1></div>
	<div class="content-box">
<? else: ?>
<div id="content-wrapper">
	<article id="fitting-guide">
		<header class="content-box"><h1>Add address</h1></header>
		<section class="content-box">
<? endif; ?>

		<form method="post" action="" class="std-form inner" style="width: auto;">
		<? if($ok): ?>
			<p>The address has been successfully saved.</p>
		<? else: ?>
			<p>There was a problem whilst saving the address, please try again.</p>
		<? endif; ?>
		<? if($_REQUEST['ajax']): ?>
			<div class="submit"><a href="#" class="btn-red" id="btn_close">Close</a></div>
		<? endif; ?>
		</form>
		
<? if($_REQUEST['ajax']): ?>
	</div>
</div>
<? else: ?>
		</section>
	</article>
</div>
<? endif; ?>