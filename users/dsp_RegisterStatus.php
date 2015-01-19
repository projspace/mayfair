<?
	$page = $elems->qry_Page(21);
?>
<? if($_REQUEST['ajax']): ?>

    <div class="preview-wrap loginDetails">
        <div class="tab-box">
            <blockquote>
                <div class="loginInfo tabLink activeLink"> <a href="#" class="tabLink activeLink" id="cont-1">DETAILS</a> <a href="#" class="tabLink " id="cont-2">SIGN UP</a> </div>
            </blockquote>
        </div>
        <div class="popup" style="margin-top: 20px;">
            <? if($ok): ?>
            <?=$page['content'] ?>
            <? else: ?>
                <p>There was a problem whilst registering, please try again.</p>
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

<? else: ?>

    <div id="checkOut">
        <div class="block">
            <div class="tab-wrapper">
                <h1>Sign Up</h1>
                <div id="tab-content">
                    <? if($ok): ?>
                    <?=$page['content'] ?>
                    <? else: ?>
                        <p>There was a problem whilst registering, please try again.</p>
                    <? endif; ?>
                </div>
            </div>
        </div>
    </div>

<? endif; ?>