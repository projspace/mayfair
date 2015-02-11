<h1>Fitting Guides</h1>
<div id="tabs" class="form clearfix">
	<ul>
		<li><a href="#tabs-1">Details</a></li>
	</ul>
	<div id="tabs-1">
		<script language="javascript" type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function(){
				$('#fittings td a').live('click', function(){
					var $this = $(this);
					$('#fittings .edit').remove();
					$('#fittings td a:hidden').show();
					$(this).hide().after('<input type="text" class="edit" size_id="'+$this.attr('size_id')+'" value="'+$this.text()+'" style="width: '+$this.width()+'px;"/>');
					$('#fittings td .edit').focus();
					return false;
				});
				
				$('#fittings td .edit').live('keydown', function(e){
					if(e.which == 27) //escape
					{
						var $this = $(this);
						$this.parent().find('a').show();
						$this.remove();
					}
					else
					if(e.which == 13) //enter
					{
						var $this = $(this);
						$.ajax({
							async: false,
							url: '<?= $config['dir'] ?>index.php?fuseaction=admin.fittingGuides&act=save',
							type: 'post',
							dataType: 'text',
							data: 'size_id='+$this.attr('size_id')+'&size='+$this.val(),
							success: function(ret){
								if(ret == 'OK')
								{
									$this.parent().find('a').text($this.val()).show();
									$this.remove();
								}
								else
								{
									alert('There was a problem whilst updating the fitting size, please try again.');
								}
							}
						});
						e.preventDefault();
						e.stopPropagation();
					}
				});
			});
		/* ]]> */
		</script>
		<style type="text/css">
		/* <![CDATA[ */
			#fittings th, #fittings td { padding: 4px; }
			#fittings td a { display: block; height: 100%; }
			#fittings td a:hover { background-color: #abc; }
		/* ]]> */
		</style>
		<div id="fittings" style="overflow: scroll;">
			<table>
				<!--<tr>
					<th></th><th></th>
				<?
					for($j=1;$j<=$fitting_count['column_count'];$j++)
					{
						if($category_fitting_guide_columns[$fitting_guides_columns[$j]['column_id']])
							$checked = 'checked="checked"';
						else
							$checked = '';
							
						echo '<td><input type="checkbox" class="column" name="column_ids[]" value="'.$fitting_guides_columns[$j]['column_id'].'" '.$checked.'/></td>';
					}
				?>
				</tr>-->
			<?
				for($i=1;$i<=$fitting_count['row_count'];$i++)
				{
					if($category_fitting_guides[$fitting_guides_rows[$i]['row_id']])
						$checked = 'checked="checked"';
					else
						$checked = '';
						
					echo '<tr>';
					//echo '<th><input type="checkbox" class="guide" name="guide_ids[]" value="'.$fitting_guides_rows[$i]['row_id'].'" '.$checked.'/></th>';
					echo '<th>'.$fitting_guides_rows[$i]['row_name'].'</th>';
					for($j=1;$j<=$fitting_count['column_count'];$j++)
						echo '<td><a href="#" size_id="'.$fitting_guides[$i.','.$j]['size_id'].'">'.$fitting_guides[$i.','.$j]['size'].'</a></td>';
					echo '</tr>';
				}
			?>
			</table>
		</div>
	</div>
</div>