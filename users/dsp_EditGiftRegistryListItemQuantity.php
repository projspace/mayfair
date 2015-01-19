<div class="preview-wrap loginDetails">
    <div class="tab-box">
        <blockquote>
            <div class="loginInfo tabLink activeLink"> <a href="#" class="tabLink activeLink" id="cont-1">GIFT ITEM</a> <a href="#" class="tabLink " id="cont-2">QUANTITY</a> </div>
        </blockquote>
    </div>
    <div class="popup">
        <form method="post" action="" id="frm">
            <input type="hidden" name="is_post" value="1"/>

            <?=$validator->displayMessage() ?>
            <fieldset class="login">
                <ul>
                    <li>
                        <label for="quantity">QUANTITY:</label>
                        <div class="report-box  custom-select text-big">
                            <select class="styled" name="quantity" id="quantity">
                            <?
                                for($i=$item['bought']+0;$i<=$item['stock']+0;$i++)
                                    if($i == disp($_REQUEST['quantity'], $item['quantity']))
                                        echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
                                    else
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                            ?>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </li>
                </ul>
            </fieldset>
            <div class="block"><a class="btn green-btn fl-right omega submit" href="#">UPDATE</a></div>
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
    fields[fields.length] = {'id':'quantity', 'name':"Quantity", 'type':'required'};

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

	$(document).ready(function(){
		$('#frm').submit(validateForm);
		$('#frm .submit').click(function(){
            $('#frm').submit();
            return false;
        });
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>