<?
	$page = $elems->qry_Page(34);
?>
<div class="preview-wrap loginDetails">
    <div class="tab-box">
        <blockquote>
            <div class="loginInfo tabLink activeLink"> <a href="#" class="tabLink activeLink" id="cont-1">DETAILS</a> <a href="#" class="tabLink " id="cont-2">SIGN IN</a> </div>
        </blockquote>
    </div>
    <div class="popup">
        <form method="post" action="<?=$config['dir'] ?>forgotten-password?act=mail&amp;ajax=1" id="frm">
            <input type="hidden" name="is_post" value="1"/>
            <input type="hidden" name="return_url" value="<?=$_REQUEST['return_url'] ?>"/>

            <?=$page['content'] ?>
            <?=$validator->displayMessage() ?>
            <fieldset class="login">
                <ul>
                    <li>
                        <label for="email">EMAIL:</label>
                        <input type="text" value="" name="email" id="email" class="input-field" />
                        <div class="clear"></div>
                    </li>
                </ul>
            </fieldset>
            <div class="block"><a class="btn green-btn fl-right omega submit" href="#">SUBMIT</a></div>
        </form>
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
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var fields = [];
	fields[fields.length] = {'id':'email', 'name':"Email", 'type':'required|email'};

	function validateForm()
	{
		$('#frm input, #frm textarea').removeClass('error').removeClass('valid');
		$('#frm label.error, #frm label.valid').remove();

		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+errors[i].errorMsg+'</label>');
			}
		});

		for(i=0;i<fields.length;i++)
			validation.addField(fields[i].id, fields[i].name, fields[i].type);

		var ret = validation.validate();

		for(i=0;i<fields.length;i++)
			if(!$('#'+fields[i].id+' ~ label.error').length)
				$('#'+fields[i].id).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');
		return ret;
	}

	function validateInput()
	{
		var validation = new Validator(function(errors){});
		for(i=0;i<fields.length;i++)
			validation.addField(fields[i].id, fields[i].name, fields[i].type);

		var ret = validation.validateInput($(this).attr('id'));
		$(this).removeClass('error').removeClass('valid').find(' ~ label.error').remove();
		if(!ret.status)
			$(this).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+ret.details.errorMsg+'</label>');
		else
			$(this).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');
	}

	$(document).ready(function(){
		$('#frm').submit(validateForm);
		$('#frm .submit').click(function(){
            $('#frm').submit();
            return false;
        });

		$('#email').keyup(validateInput).change(validateInput);
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>