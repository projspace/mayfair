<h1>Gift List</h1>

<form method="post" action="#">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Delivery</a></li>
		</ul>
		<div id="tabs-1">
            <div class="form-field clearfix">
				<label>Account</label>
            	<a href="<?= $config['dir'] ?>index.php?fuseaction=admin.editUser&user_id=<?=$gift_list['account_id'] ?>" target="_blank"><?= $gift_list['account'] ?></a>
			</div>
			<div class="form-field clearfix">
				<label>Name</label>
				<input type="text" value="<?= $gift_list['name'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>Date</label>
				<input type="text" value="<?= date('m/d/Y', strtotime($gift_list['date'])) ?>" readonly="readonly"/>
			</div>
			<div class="form-field clearfix">
				<label>Status</label>
				<select disabled="disabled">
					<option>Pending</option>
                    <option <?=($gift_list['status'] == 'completed')?'selected="selected"':'' ?>>Completed</option>
				</select>
			</div>
            <div class="form-field clearfix">
				<label>Type</label>
				<select disabled="disabled">
                <?
                    while($row = $types->FetchRow())
                        echo '<option '.(($gift_list['type_id'] == $row['id'])?'selected="selected"':'').'>'.$row['name'].'</option>';
                ?>
				</select>
			</div>
            <? if($gift_list['type_id'] == 4): ?>
            <div class="form-field clearfix">
				<label>Other type</label>
				<input type="text" value="<?= $gift_list['other_type'] ?>" readonly="readonly"/>
			</div>
            <? endif; ?>
			<div class="form-field clearfix">
				<label>Public list</label>
				<span><?= ($gift_list['public']+0)?'Yes':'No' ?></span><br />
            </div>
		</div>
        <div id="tabs-2">
            <div class="form-field clearfix">
				<label>Name</label>
				<input type="text" value="<?= $gift_list['delivery_name'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>Email</label>
				<input type="text" value="<?= $gift_list['delivery_email'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>Phone</label>
				<input type="text" value="<?= $gift_list['delivery_phone'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>Address 1</label>
				<input type="text" value="<?= $gift_list['delivery_line1'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>Address 2</label>
				<input type="text" value="<?= $gift_list['delivery_line2'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>Address 3</label>
				<input type="text" value="<?= $gift_list['delivery_line3'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>City</label>
				<input type="text" value="<?= $gift_list['delivery_line4'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>Zip code</label>
				<input type="text" value="<?= $gift_list['delivery_postcode'] ?>" readonly="readonly"/>
			</div>
            <div class="form-field clearfix">
				<label>Country</label>
				<select disabled="disabled">
                <?
                    foreach($countries as $row)
                        echo '<option '.(($gift_list['delivery_country_id'] == $row['id'])?'selected="selected"':'').'>'.$row['name'].'</option>';
                ?>
				</select>
			</div>
            <div class="form-field clearfix">
				<label>Delivery after</label>
				<input type="text" value="<?= date('m/d/Y', strtotime($gift_list['delivery_after'])) ?>" readonly="readonly"/>
			</div>
		</div>
	</div>

	<div class="tab-panel-buttons clearfix">
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.giftRegistry"><span>Cancel</span></a>
	</div>
</form>