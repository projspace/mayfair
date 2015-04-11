<style type="text/css">
    #other_type_container { display: none; }
</style>
<div id="gift-registry" class="gift-registry">
    <? include("inc_GiftBanner.php"); ?>
    <div class="block">
        <form method="post" action="" id="frm">
            <input type="hidden" name="is_post" value="1"/>
            <?=$validator->displayMessage() ?>
            
            <div class="tab-wrapper" id="tabbing">
                <ul class="tab-nav">
                    <li class="active"><a href="javascript:void(0)" >Step 1 - Event details</a></li>
                    <li><a href="javascript:void(0)" >Step 2 - delivery address</a></li>
                    <li><a href="javascript:void(0)" >Step 3 - your list</a></li>
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
                        <div class="block" id="other_type_container">
                            <label for="other_type">Type*:</label>
                            <input type="text" class="input-box omega clearable" value="<?=$_REQUEST['other_type'] ?>" name="other_type" id="other_type" placeholder="required" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="name">Name of event*:</label>
                            <input type="text" class="input-box omega clearable" value="<?=$_REQUEST['name'] ?>" name="name" id="name" placeholder="required" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>date of event:</label>
                            <div class="calender omega">
                                <div class="cal-head">
                                    <input type="text" name="date" id="date" value="<?=disp($_REQUEST['date'], date('m/d/Y')) ?>" class="ui-datepicker-title txtDate" />
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>* REQUIRED</label>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <a href="#" class="btn big-btn green-btn top-space next">Your details ></a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" >
                    <h3 class=" capital clear">Please give us details on where we should deliver your gifts. <a class="link clear" href="<?= $config['dir'] ?>registry-delivery" target="_blank">More delivery info here.</a></h3>
                    <div class=" detail-section block-full">
                        <div class="block">
                            <label>address 1*:</label>
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
                            <label>city*:</label>
                            <input type="text" value="<?=$_REQUEST['address4'] ?>" name="address4" id="address4" placeholder="city" class="input-box clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>zip code*:</label>
                            <input type="text" value="<?=$_REQUEST['postcode'] ?>" name="postcode" id="postcode" placeholder="zip"  class="input-box clearable" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>country*:</label>
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
                            <label>state*:</label>
                            <div class="report-box  custom-select text-big">
                                <select class="styled" name="country_id" id="country_id">
                                    <option value="">Please select</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>deliver after*:</label>
                            <div class="calender omega">
                                <div class="cal-head">
                                    <input type='text' value="<?=disp($_REQUEST['delivery_after'], date('d/m/Y')) ?>" name="delivery_after" id="delivery_after" class="ui-datepicker-title txtDate" />
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>* REQUIRED</label>
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
                            <label>
                                PUBLIC LIST:<br />
                                <em style="font-size: 14px; text-transform: none !important;">
                                    Do you wish for your registry to be public?<br />
                                    If your registry is public, it is viewable by anyone.<br />
                                    If your registry is private, it is only viewable by anyone you provide your customized URL
                                </em>
                            </label>
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
                        <div class="block top-space">
                            <div class="custom-checkbox">
                                <input type="checkbox" name="terms" id="terms" value="1">
                                <label for="terms" class=" small-caps">I’ve read and agree with the <a href="<?= $config['dir'] ?>terms-and-conditions" class="golden">Terms &amp; Conditions</a>*</label>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label>* REQUIRED</label>
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
    var countries = [];
	<?
		foreach($areas as $area)
			echo 'countries['.$area['id'].']='.json_encode($area['countries']).";\n";
	?>
    $(document).ready(function(){
		$('#area_id').change(function(){
			var area_id = parseInt($(this).val());
            var options = '';
			if(!isNaN(area_id) && countries[area_id])
            {
                for(var country_id in countries[area_id])
                    if(area_id == <?=$_REQUEST['area_id']+0 ?> && country_id == <?= $_REQUEST['country_id']+0 ?>)
                        options += '<option value="'+country_id+'" selected="selected">'+countries[area_id][country_id]+'</option>';
                    else
                        options += '<option value="'+country_id+'">'+countries[area_id][country_id]+'</option>';
            }
			else
                options = '<option value="">Please select</option>';
            $('#country_id').html(options);
		}).change();
	});
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var fields = [];
	fields[fields.length] = {'id':'type_id', 'name':"Type of event", 'type':'required', 'step': 1};
	fields[fields.length] = {'id':'name', 'name':"Name of event", 'type':'required', 'step': 1};
	fields[fields.length] = {'id':'date', 'name':"Date of event", 'type':'required', 'step': 1};

    fields[fields.length] = {'id':'address1', 'name':"Address 1", 'type':'required', 'step': 2};
    fields[fields.length] = {'id':'address4', 'name':"Address 4", 'type':'required', 'step': 2};
	fields[fields.length] = {'id':'postcode', 'name':"Zip code", 'type':'required', 'step': 2};
	fields[fields.length] = {'id':'area_id', 'name':"Country", 'type':'required', 'step': 2};
	fields[fields.length] = {'id':'country_id', 'name':"State", 'type':'required', 'step': 2};
	fields[fields.length] = {'id':'delivery_after', 'name':"Deliver after", 'type':'required', 'step': 2};

    fields[fields.length] = {'id':'public', 'name':"Public list", 'type':'required', 'step': 3};

	function validateForm(step)
	{
        step = parseInt(step);
        if(isNaN(step))
            step = 'all';
        
		$('#frm input, #frm textarea').removeClass('error').removeClass('valid');
		$('#frm label.error, #frm label.valid').remove();

		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+errors[i].errorMsg+'</label>');
			}
		});

		for(i=0;i<fields.length;i++)
            if(step == 'all' || step == fields[i].step)
                validation.addField(fields[i].id, fields[i].name, fields[i].type);

        if($('#type_id').val() == 4)
            validation.addField('other_type', 'Type', 'required');

		var ret = validation.validate();

		for(i=0;i<fields.length;i++)
			if(!$('#'+fields[i].id+' ~ label.error').length)
				$('#'+fields[i].id).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');

		if(!ret)
			return false;

        if(step == 'all' || step == 3)
        {
            if(!$('#terms').is(':checked'))
            {
                alert('Please agree with the terms and conditions.');
                return false;
            }
        }

		return true;
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
        $('#frm a.submit').click(function(){
            $('#frm').submit();
            return false;
        });
        
		$('#name').keyup(validateInput).change(validateInput);
        $('#address1, #postcode, #area_id, #country_id, #delivery_after').keyup(validateInput).change(validateInput);

        $('#date, #delivery_after').mousedown().blur();
        $('#date, #delivery_after').datepicker('option', 'minDate', <?= GIFT_DAYS_ADVANCE+0 ?>);

        $('#frm a.next').click(function(){
            var index = $(this).closest('.tab-content').index();
            if(validateForm(index))
                $('ul.tab-nav li:eq('+index+') a').click();
            return false;
        });
        $('#frm a.prev').click(function(){
            var index = $(this).closest('.tab-content').index();
            $('ul.tab-nav li:eq('+(index-2)+') a').click();
            return false;
        });

        $('#type_id').change(function(){
			var type_id = parseInt($(this).val());
			if(type_id == 4)
                $('#other_type_container').show();
            else
                $('#other_type_container').hide();
		}).change();

        $('#date').live('change', function(){
            var date = $(this).datepicker('getDate');
            date.setDate(date.getDate() + 1);
            $('#delivery_after').datepicker('setDate', date);
        });
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>