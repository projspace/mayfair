<div id="giftRegister" class="giftRegister6">
    <? include("inc_GiftBanner.php"); ?>
    <div class="block">
        <div class="tab-wrapper">
            <ul class="tab-nav">
                <li class="active"><a href="#" >CONFIRMATION</a></li>
            </ul>
            <div class="tab-content gift-list">
                <form method="post" action="" id="frmConfirmation">
                    <input type="hidden" name="is_post" value="1"/>
                    <div class="block">
                        <p>Please note, all products that do not belong to this list will be removed from your cart.</p>
                        <p>Please checkout if you wish to purchase these goods before browsing the gift list</p>
                        <p>Are you sure you want to continue?</p>
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <a href="#" class="btn big-btn green-btn top-space right-space submit">YES</a> <a href="<?=$config['dir'] ?>gift-registry" class="btn big-btn green-btn top-space cancel">NO</a>
                        <div class="clear"></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#frmConfirmation a.submit').click(function(){
			$('#frmConfirmation').submit();
			return false;
		});
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>