<? ob_start(); ?>
<?
	if(trim($custom)!="")
	{
		$custom=stripslashes_if($custom);
		$custom=unserialize($custom);
		$keys=array_keys($custom);
		foreach($keys as $key)
			$custom[$key]=str_replace("\"","&quot;",stripslashes_if($custom[$key]));
	}

	switch(str_replace("[AND]","&",$category))
	{
		case "Computers":
			include("kelkoo_computers.php");
			break;

		case "Computer Accessories":
			include("kelkoo_computer_accessories.php");
			break;

		case "Memory":
			include("kelkoo_memory.php");
			break;

		case "Monitors":
			include("kelkoo_monitors.php");
			break;

		case "PDA":
			include("kelkoo_pda.php");
			break;

		case "Printers":
			include("kelkoo_printers.php");
			break;

		case "Software":
			include("kelkoo_software.php");
			break;

		case "Storage":
			include("kelkoo_storage.php");
			break;

		case "ISPs":
			include("kelkoo_isp.php");
			break;

		case "Electronics":
			include("kelkoo_electronics.php");
			break;

		case "Health & Beauty Electronics":
			include("kelkoo_health_beauty_electronics.php");
			break;

		case "Household Appliances":
			include("kelkoo_household_appliances.php");
			break;

		case "In-Car Entertainment":
			include("kelkoo_incar_entertainment.php");
			break;

		case "Mobile Phones":
			include("kelkoo_mobile_phones.php");
			break;

		case "Communication":
			include("kelkoo_communication.php");
			break;

		case "Books":
			include("kelkoo_books.php");
			break;

		case "Consoles":
			include("kelkoo_consoles.php");
			break;

		case "Films/Genre":
			include("kelkoo_films.php");
			break;

		case "Music Downloads":
			include("kelkoo_music_downloads.php");
			break;

		case "Video Games":
			include("kelkoo_video_games.php");
			break;

		case "Chocolate":
			include("kelkoo_chocolate.php");
			break;

		case "Fashion":
			include("kelkoo_fashion.php");
			break;

		case "Flowers":
			include("kelkoo_flowers.php");
			break;

		case "Gadgets":
			include("kelkoo_gadgets.php");
			break;

		case "Home & Garden":
			include("kelkoo_home_garden.php");
			break;

		case "Musical Instruments":
			include("kelkoo_musical_instruments.php");
			break;

		case "Office Supplies":
			include("kelkoo_office_supplies.php");
			break;

		case "Perfume":
			include("kelkoo_perfume.php");
			break;

		case "Sport":
			include("kelkoo_sport.php");
			break;

		case "Toys":
			include("kelkoo_toys.php");
			break;

		case "Wine":
			include("kelkoo_wine.php");
			break;

		case "Property":
			include("kelkoo_property.php");
			break;

		case "Car Parts":
			include("kelkoo_car_parts.php");
			break;

		default:
			die();
	}
?>

	<label for="custom_kelkoo_delivery_cost">Delivery Cost</label>
	<input type="text" id="custom_kelkoo_delivery_cost" id="" name="custom[kelkoo_delivery_cost]" value="<?= $custom["kelkoo_delivery_cost"] ?>" /><br />

	<label for="custom_kelkoo_delivery_time">Delivery Time</label>
	<input type="text" id="custom_kelkoo_delivery_time" id="" name="custom[kelkoo_delivery_time]" value="<?= $custom["kelkoo_delivery_time"] ?>" /><br />

	<label for="custom_kelkoo_availability">Availability</label>
	<select id="custom_kelkoo_availability" id="" name="custom[kelkoo_availability]">
<?
	if(trim($custom["kelkoo_availability"])!="")
		echo "<option>{$custom["kelkoo_availability"]}</option>";
?>
		<option>In Stock</option>
		<option>Stock on Order</option>
		<option>Available on Order</option>
		<option>Pre-Order</option>
		<option>Check Site</option>
	</select><br />

	<label for="custom_kelkoo_warranty">Warranty</label>
	<input type="text" id="custom_kelkoo_warranty" id="" name="custom[kelkoo_warranty]" value="<?= $custom["kelkoo_warranty"] ?>" /><br />

	<label for="custom_kelkoo_condition">Condition</label>
	<select id="custom_kelkoo_condition" id="" name="custom[kelkoo_condition]">
<?
	if(trim($custom["kelkoo_condition"])!="")
		echo "<option>{$custom["kelkoo_condition"]}</option>";
?>
		<option>New</option>
		<option>Secondhand</option>
		<option>Refurbished</option>
	</select><br />

	<label for="custom_kelkoo_offer_type">Offer Type</label>
	<select id="custom_kelkoo_offer_type" id="" name="custom[kelkoo_offer_type]">
<?
	if(trim($custom["kelkoo_offer_type"])!="")
		echo "<option>{$custom["kelkoo_offer_type"]}</option>";
?>
		<option>Single Product</option>
		<option>Product Bundle</option>
		<option>Accessory</option>
	</select><br />
<? ob_end_flush(); ?>