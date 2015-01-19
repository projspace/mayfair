<div id="giftRegister3">
    <? include("inc_GiftBanner.php"); ?>
    <div class="block">
        <div class="tab-wrapper">
            <ul class="tab-nav">
                <li><a href="#" >Step 1 - Event details</a></li>
                <li><a href="#" >Step 2 - your details</a></li>
                <li><a href="#" >Step 3 - delivery address</a></li>
                <li class="active"><a href="#" >Step 4 - your list</a></li>
            </ul>
            <div class="tab-content">
                <!--<h3 class=" capital clear">thank you. Your list number is <?= $_REQUEST['code'] ?>.</h3>-->
                <div class=" detail-section block-full">
                    <p class="top-space bottom-space">Congratulations on establishing your registry.  While logged into your registry, you are able to browse the store to make your selections; simply click on the prompt, “Add to Gift Registry” and the item will be placed into your registry.  If at any time you wish to make amendments to your list, please follow the steps provided:</p>
                    <p class="top-space bottom-space">Your Account > Gift Registry > View List.</p>
                    <p class="top-space bottom-space">Your Registry Account Number is <?= $_REQUEST['code'] ?>.</p>
                    <p class="top-space bottom-space">Please send this link to anyone who wishes to purchase a gift: <a href="<?= $url = $config['dir'].'gift-registry/list/'.$_REQUEST['code'] ?>" class="golden"><?= $url ?></a></p>
                    <div class="block">
                        <a href="<?= $config['dir'] ?>view-by-category" class="btn big-btn green-btn top-space  right-space">Add products to gift list</a>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>