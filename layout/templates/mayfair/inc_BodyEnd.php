<!--=======================
Footer Section End Here
========================-->
<script type="text/javascript" src="<?=$config['layout_dir'] ?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?=$config['layout_dir'] ?>js/craftyslide.js"></script>
<script type="text/javascript" src="<?=$config['layout_dir'] ?>js/plugins.js"></script>
<script type="text/javascript" src="<?=$config['layout_dir'] ?>js/common.js"></script>
<script type="text/javascript" src="<?=$config['layout_dir'] ?>js/customSelect.jquery.js"></script>
<script type="text/javascript" src="<?=$config['layout_dir'] ?>js/customInput.jquery.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('select.styled').customSelect();
    $('input').customInput();
});
</script>
<?=$elems->placeholder('script')->getContent() ?>