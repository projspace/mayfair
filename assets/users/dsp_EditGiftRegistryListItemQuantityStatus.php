<div class="preview-wrap loginDetails">
    <div class="tab-box">
        <blockquote>
            <div class="loginInfo tabLink activeLink"> <a href="#" class="tabLink activeLink" id="cont-1">GIFT ITEM</a> <a href="#" class="tabLink " id="cont-2">QUANTITY</a> </div>
        </blockquote>
    </div>
    <div class="popup">
    <? if($ok): ?>
        <p>The quantity has been successfully updated.</p>
    <? else: ?>
        <p>There was a problem whilst updating the quantity, please try again.</p>
    <? endif; ?>
    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function resizeFB(){
		$('#fancybox-content', parent.document).css('height', ($('#content').height())+'px');
		parent.$.fancybox.center(true);
	}

	$(document).ready(resizeFB);
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>