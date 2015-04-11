<div id="giftRegister" class="giftRegister6">
    <? include("inc_GiftBanner.php"); ?>
    <div class="block">
        <div class="tab-wrapper">
            <ul class="tab-nav">
                <li class="active"><a href="#" >GIFT REGISTRY</a></li>
            </ul>
            <div class="tab-content gift-list">
                <div class="block">
                    <a href="<?=$config['dir'] ?>gift-registry/setup" class="btn big-btn green-btn top-space">CREATE NEW REGISTRY</a>
                    <div class="clear"></div>
                    <table class="product-detail available-itmes">
                        <tr class=" heading">
                            <td class="first">Name</td>
                            <td>Code</td>
                            <td>Date</td>
                            <td>Status</td>
                            <td>Details</td>
                        </tr>
                        <?
                            while($row = $items->FetchRow())
                            {
                                echo '
                                    <tr>
                                        <td class="first">'.$row['name'].'</td>
                                        <td>'.$row['code'].'</td>
                                        <td>'.(($time = strtotime($row['date']))?date('m/d/Y', $time):'--/--/----').'</td>
                                        <td>'.$row['status'].'</td>
                                        <td><a href="'.$config['dir'].'gift-registry/list/'.$row['code'].'">View List</a></td>
                                    </tr>';
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>