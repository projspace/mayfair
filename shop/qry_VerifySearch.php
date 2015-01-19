<?
	$keyword = safe($_GET['keyword']);
	
	/* VERIFY THE NUMBER OF REQUESTS FROM THE IP ADDRESS */
	
	$ip = $_SERVER['REMOTE_ADDR'];
		
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				zip_searches
			WHERE
				ip_address = %s
		"
		,$db->Quote($ip)
		)
	);

	$result = $results->FetchRow();

	if($result === false){
		//insert into the table

		$db->Execute(
			$sql = sprintf("
					INSERT INTO `zip_searches` (
					`id` ,
					`ip_address` ,
					`searches` ,
					`date`
					)
					VALUES (
					NULL , %s, %u, %u
					);
				"
				,$db->Quote($ip)
				,1
				,strtotime(date("d-m-Y"))
			)
		);
		
		$flag = true;
		
	}else{
		//verify and update
		
		if(strtotime(date("d-m-Y")) == $result['date']){
			//same day, verify if the limit of searches has been reached
			if($result['searches'] < 3){
				// we're good, go on to increment the searches field

				$db->Execute(
					$sql = sprintf("
					UPDATE `zip_searches`
					SET `searches` = %u
					WHERE `id` = %u
				"
						,$result['searches']+1
						,$result['id']
					)
				);

				$flag = true;
				
			}else{
				$flag = false;
			}
			
		}else{
			//another day, reset the counter to 1

			$db->Execute(
				$sql = sprintf("
					UPDATE `zip_searches`
					SET `searches` = %u, `date` = %u
					WHERE `id` = %u
				"
					,1
					,strtotime(date("d-m-Y"))
					,$result['id']
				)
			);
			
			$flag = true;
			
		}
		
	}

?>