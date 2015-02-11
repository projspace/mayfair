<div class=" detail-section block-full">
    <table class="product-detail" style="width: 100%;">
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Date</th>
            <th>Status</th>
            <th>Details</th>
        </tr>
        <?
            while($row = $items->FetchRow())
            {
                echo '
                    <tr>
                        <td>'.$row['name'].'</td>
                        <td>'.$row['code'].'</td>
                        <td>'.(($time = strtotime($row['date']))?date('m/d/Y', $time):'--/--/----').'</td>
                        <td>'.$row['status'].'</td>
                        <td><a href="'.$config['dir'].'account/gift-registry/'.$row['id'].'">Edit List</a></td>
                        <td><a href="#" onclick="javascript: parent.window.location = \''.$config['dir'].'gift-registry/list/'.$row['code'].'\'; return false;">View List</a></td>
                    </tr>';
            }
        ?>
    </table>
    <div class="block">
        <a href="#" onclick="javascript: parent.window.location = '<?=$config['dir'] ?>gift-registry/setup'; return false;" class="btn big-btn green-btn top-space">Setup New List</a>
        <div class="clear"></div>
    </div>
</div>
<div class="clear"></div>
    
<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function resizeFB(){
		$('#account-gift-registry', parent.document).css('height', ($('#content').height())+'px');
	}

	$(document).ready(resizeFB);
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>