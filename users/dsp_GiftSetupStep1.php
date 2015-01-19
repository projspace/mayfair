<div id="gift-registry" class="gift-registry">
    <? include("inc_GiftBanner.php"); ?>
    <div class="block">
        <form method="post" action="" id="frm">
            <input type="hidden" name="is_post" value="1"/>
            <?=$validator->displayMessage() ?>
            
            <div class="tab-wrapper" id="tabbing">
                <ul class="tab-nav">
                    <li class="active"><a href="javascript:void(0)" >Step 1 - Event details</a></li>
                    <li><a href="javascript:void(0)" >Step 2 - your details</a></li>
                    <li><a href="javascript:void(0)" >Step 3 - delivery address</a></li>
                    <li><a href="javascript:void(0)" >Step 4 - your list</a></li>
                </ul>
                <div class="tab-content giftRegvistry" id="tabbin-a">
                    <h3 class=" capital clear">Please give us details of your event.</h3>
                    <div class=" detail-section">
                        <div class="block">
                            <label for="type_id">Type of event:</label>
                            <div class="report-box  custom-select text-big">
                                <select class="styled" name="type_id" id="type_id">
                                <?
                                    while($row = $types->FetchRow())
                                        if($row['id'] == $_REQUEST['type_id'])
                                            echo '<option value="'.$row['id'].'" selected="selected">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
                                        else
                                            echo '<option value="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
                                ?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="name">Name of event:</label>
                            <input type="text" class="input-box omega clearable" value="<?=$_REQUEST['name'] ?>" name="name" id="name" placeholder="required" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>date of event:</label>
                            <div class="calender omega">
                                <div class="cal-head">
                                    <input type="text" name="date" id="date" value="<?=disp($_REQUEST['date'], date('d/m/Y')) ?>" class="ui-datepicker-title txtDate" />
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <a href="#" class="btn big-btn green-btn top-space next">Your details ></a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <h3 class=" capital clear">Please give us details on how we contact you</h3>
                    <div class=" detail-section block-full">
                        <div class="block">
                            <label>TITLE:</label>
                            <input type="text" value="<?=$_REQUEST['title'] ?>" name="title" id="title" placeholder="MR/MRS/MISS"  class="input-box omega clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>NAME:</label>
                            <input type="text" value="<?=$_REQUEST['first_name'] ?>" name="first_name" id="first_name" placeholder="FIRST NAME"  class="input-box clearable" />
                            <input type="text" value="<?=$_REQUEST['middle_name'] ?>" name="middle_name" id="middle_name" placeholder="MIDDLE"  class="input-box clearable" />
                            <input type="text" value="<?=$_REQUEST['surname'] ?>" name="surname" id="surname" placeholder="LAST NAME"  class="input-box omega clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>PHONE:</label>
                            <input type="text" value="<?=$_REQUEST['primary_phone'] ?>" name="primary_phone" id="primary_phone" placeholder="PRIMARY"  class="input-box clearable" />
                            <input type="text" value="<?=$_REQUEST['secondary_phone'] ?>" name="secondary_phone" id="secondary_phone" placeholder="ALTERNATIVE"  class="input-box omega clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>EMAIL:</label>
                            <input type="text" value="<?=$_REQUEST['email'] ?>" name="email" id="email" placeholder="EMAIL ADDRESS"  class="input-box clearable" />
                            <input type="text" value="" name="confirm_email" id="confirm_email" placeholder="CONFIRM EMAIL ADDRESS"  class="input-box omega clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>PREFERRED METHOD:</label>
                            <div class="custom-radio top-space">
                                <input type="radio" value="phone" id="contact_method_phone" name="contact_method" <? if(disp($_REQUEST['contact_method'], 'phone') == 'phone'): ?>checked="checked"<? endif; ?>/>
                                <label for="contact_method_phone" class="">PHONE</label>
                            </div>
                            <div class="custom-radio top-space">
                                <input type="radio" value="email" id="contact_method_email" name="contact_method" <? if($_REQUEST['contact_method'] == 'email'): ?>checked="checked"<? endif; ?>/>
                                <label for="contact_method_email" class="">email</label>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <div class="custom-checkbox">
                                <input type="checkbox" name="newsletter" id="newsletter" value="1" <? if($_REQUEST['newsletter']): ?>checked="checked"<? endif; ?>/>
                                <label for="newsletter" class=" small-caps">Yes, please send me information about new products and special events.</label>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <a href="#" class="btn big-btn green-btn top-space right-space prev"> &lt; BACK</a> <a href="#" class="btn big-btn green-btn top-space next">DELIVERY ADDRESS ></a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" >
                    <h3 class=" capital clear">Please give us details on where we should deliver your gifts. <a class="link clear" href="#">More delivery info here.</a></h3>
                    <div class=" detail-section block-full">
                        <div class="block">
                            <label>address 1:</label>
                            <input type="text" value="<?=$_REQUEST['address1'] ?>" name="address1" id="address1" placeholder="address" class="input-box omega clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>address 2:</label>
                            <input type="text" value="<?=$_REQUEST['address2'] ?>" name="address2" id="address2" placeholder="address" class="input-box clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>address 3:</label>
                            <input type="text" value="<?=$_REQUEST['address3'] ?>" name="address3" id="address3" placeholder="address" class="input-box clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>zip code:</label>
                            <input type="text" value="<?=$_REQUEST['postcode'] ?>" name="postcode" id="postcode" placeholder="zip"  class="input-box clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>country:</label>
                            <div class="report-box  custom-select text-big">
                                <select class="styled" name="area_id" id="area_id">
                                    <option value="">Please select</option>
                                <?
                                    foreach($areas as $row)
                                        if($row['id'] == $_REQUEST['area_id'])
                                            echo '<option value="'.$row['id'].'" selected="selected">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
                                        else
                                            echo '<option value="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
                                ?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>state:</label>
                            <div class="report-box  custom-select text-big">
                                <select class="styled" name="country_id" id="country_id">
                                    <option value="">Please select</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>deliver after:</label>
                            <div class="calender omega">
                                <div class="cal-head">
                                    <input type='text' value="<?=disp($_REQUEST['delivery_after'], date('d/m/Y')) ?>" name="delivery_after" id="delivery_after" class="ui-datepicker-title txtDate" />
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <a href="#" class="btn big-btn green-btn top-space  right-space prev"> &lt; BACK</a> <a href="#" class="btn big-btn green-btn top-space next">your list ></a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" >
                    <h3 class=" capital clear">Please give us details about your list</h3>
                    <div class=" detail-section block-full">
                        <div class="block">
                            <label>PUBLIC LIST:</label>
                            <div class="custom-radio top-space">
                                <input type="radio" value="1" id="public_yes" name="public" <? if($_REQUEST['public']): ?>checked="checked"<? endif; ?>/>
                                <label for="public_yes" class="">yes</label>
                            </div>
                            <div class="custom-radio top-space">
                                <input type="radio" value="0" id="public_no" name="public" <? if(!disp($_REQUEST['public'], 0)): ?>checked="checked"<? endif; ?>/>
                                <label for="public_no" class="">no</label>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block caps-all">
                            <label>password:</label>
                            <input type="password" value="<?=$_REQUEST['password'] ?>" name="password" id="password"  class="input-box " />
                            <input type="password" value="" name="confirm_password" id="confirm_password"  class="input-box " />
                            <div class="clear"></div>
                        </div>
                        <div class="block top-space">
                            <div class="custom-checkbox">
                                <input type="checkbox" name="terms" id="terms" value="1">
                                <label for="terms" class=" small-caps">I’ve read and agree with the <a href="<?= $config['dir'] ?>terms-conditions" class="golden">Terms &amp; Conditions</a></label>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <a href="#" class="btn big-btn green-btn top-space  right-space prev"> &lt; BACK</a> <a href="#" class="btn big-btn green-btn top-space submit">complete ></a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var fields = [];
	fields[fields.length] = {'id':'type_id', 'name':"Type of event", 'type':'required'};
	fields[fields.length] = {'id':'name', 'name':"Name of event", 'type':'required'};
	fields[fields.length] = {'id':'date', 'name':"Date of event", 'type':'required'};

    fields[fields.length] = {'id':'title', 'name':"Title", 'type':'required'};
	fields[fields.length] = {'id':'first_name', 'name':"First name", 'type':'required|custom_name'};
	fields[fields.length] = {'id':'middle_name', 'name':"Middle name", 'type':'custom_name'};
	fields[fields.length] = {'id':'surname', 'name':"Surname", 'type':'required|custom_name'};
	fields[fields.length] = {'id':'primary_phone', 'name':"Primary phone", 'type':'required|custom_phone'};
	fields[fields.length] = {'id':'secondary_phone', 'name':"Secondary phone", 'type':'custom_phone'};
	fields[fields.length] = {'id':'email', 'name':"Email", 'type':'required|email'};
	fields[fields.length] = {'id':'confirm_email', 'name':"Confirm Email", 'type':'required|email'};
	fields[fields.length] = {'id':'contact_method', 'name':"Preferred method", 'type':'required'};

    fields[fields.length] = {'id':'address1', 'name':"Address 1", 'type':'required'};
	fields[fields.length] = {'id':'postcode', 'name':"Zip code", 'type':'required'};
	fields[fields.length] = {'id':'area_id', 'name':"Country", 'type':'required'};
	fields[fields.length] = {'id':'country_id', 'name':"State", 'type':'required'};
	fields[fields.length] = {'id':'delivery_after', 'name':"Deliver after", 'type':'required'};

    fields[fields.length] = {'id':'public', 'name':"Public list", 'type':'required'};
	fields[fields.length] = {'id':'password', 'name':"Password", 'type':'required|password'};
	fields[fields.length] = {'id':'confirm_password', 'name':"Confirm Password", 'type':'required|password'};

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
        validation.addFieldType(
			'custom_name'
			, function(){
				if(jQuery.trim($(this).val()) == '')
					return true;
				if(jQuery.trim($(this).val()).length >= <?= GIFT_NAME_MIN_LENGTH+0 ?>)
					return true;
				else
					return false;
			}
			, 'CE1'
			, 'Value must be at least <?= GIFT_NAME_MIN_LENGTH+0 ?> characters.'
		);
		validation.addFieldType(
			'custom_phone'
			, function(){
				var phone = jQuery.trim($(this).val());
				if(!phone) {
					return true;
				}

				if( phone.match(/^([^-\(\)]){10}$/) ) { //math xxxxxxxxxx
					return true;
				}

				if( phone.match(/^.{3}-.{3}-.{4}$/) ) { //math xxx-xxx-xxxx
					return true;
				}

				if( phone.match(/^(\()+.{3}\) .{3}-.{4}$/) ) { //math (xxx) xxx-xxxx
					return true;
				}

				return false;
			}
			, 'CE2'
            //, 'Value must have at least <?= GIFT_PHONE_MIN_DIGITS+0 ?> digits.'
			, 'Accepted formats: 123-456-7890 <br/>OR (123) 456-7890 OR 1234567890'
		);

		for(i=0;i<fields.length;i++)
			validation.addField(fields[i].id, fields[i].name, fields[i].type);

		var ret = validation.validate();

		for(i=0;i<fields.length;i++)
			if(!$('#'+fields[i].id+' ~ label.error').length)
				$('#'+fields[i].id).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');

		if(!ret)
			return false;

		return true;
	}

	function validateInput()
	{
		var validation = new Validator(function(errors){});
        validation.addFieldType(
			'custom_name'
			, function(){
				if(jQuery.trim($(this).val()) == '')
					return true;
				if(jQuery.trim($(this).val()).length >= <?= GIFT_NAME_MIN_LENGTH+0 ?>)
					return true;
				else
					return false;
			}
			, 'CE1'
			, 'Value must be at least <?= GIFT_NAME_MIN_LENGTH+0 ?> characters.'
		);
		validation.addFieldType(
			'custom_phone'
			, function(){
				var phone = jQuery.trim($(this).val());
				if(!phone) {
					return true;
				}

				if( phone.match(/^([^-\(\)]){10}$/) ) { //math xxxxxxxxxx
					return true;
				}

				if( phone.match(/^.{3}-.{3}-.{4}$/) ) { //math xxx-xxx-xxxx
					return true;
				}

				if( phone.match(/^(\()+.{3}\) .{3}-.{4}$/) ) { //math (xxx) xxx-xxxx
					return true;
				}

				return false;
			}
			, 'CE2'
            //, 'Value must have at least <?= GIFT_PHONE_MIN_DIGITS+0 ?> digits.'
			, 'Accepted formats: 123-456-7890 <br/>OR (123) 456-7890 OR 1234567890'
		);
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
        $('#frm a.submit').click(function(){
            $('#frm').submit();
            return false;
        });
		$('#date').datepicker('option', 'minDate', <?= GIFT_DAYS_ADVANCE+0 ?>);
		$('#name').keyup(validateInput).change(validateInput);
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>