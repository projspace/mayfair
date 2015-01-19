<?
	$page = $elems->qry_Page(35);
?>
<? if($_REQUEST['ajax']): ?>

    <div class="preview-wrap loginDetails">
        <div class="tab-box">
            <blockquote>
                <div class="loginInfo tabLink activeLink"> <a href="#" class="tabLink activeLink" id="cont-1">DETAILS</a> <a href="#" class="tabLink " id="cont-2">SIGN IN</a> </div>
            </blockquote>
        </div>
        <div class="popup" style="margin-top: 20px;">
            <?=$page['content'] ?>
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
                <h1>Forgotten password</h1>
                <div id="tab-content">
                    <?=$page['content'] ?>
                </div>
            </div>
        </div>
    </div>

<? endif; ?>