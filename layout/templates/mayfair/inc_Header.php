<!--================
Header Section
==================-->
<div id="header">
    <div class="inner-header">
    <div class="main">
        <ul class="top-nav">
            <li><a href="<?=$config['dir'] ?>" class="gold" title="Back To HomePage">WELCOME TO MAYFAIR HOUSE</a></li>
            <? if($user_session->check()): ?>
            <li><a href="<?=$config['dir'] ?>account" class="grey" title="Account">MY ACCOUNT</a></li>
            <li><a href="<?=$config['dir'] ?>logout" class="grey" title="Logout">LOGOUT</a></li>
            <? else: ?>
            <li><a href="<?=$config['dir'] ?>register?ajax=1" class="grey signUp" title="Register">SIGN UP</a></li>
            <li><a href="<?=$config['dir'] ?>login?ajax=1" class="grey signIn" title="Sign In">SIGN IN</a></li>
            <? endif; ?>
            <li style="width: 160px;"><a href="<?=$config['dir'] ?>category/corporate-gifts/62" class="golden" title="Corporate Gift">CORPORATE GIFTS</a></li>
            <li><a href="<?=$config['dir'] ?>category/sale-items/63" class="golden" title="Sale Items">SALE ITEMS</a></li>
            <li style="width: 190px;"><a href="<?=$config['dir'] ?>gift-registry" class="golden" title="Gift Registry">Gift & Bridal Registry</a></li>
        </ul>
        <div class="mid-header"> <a href="<?=$config['dir'] ?>" class="logo" title="Welcome To MayFair | HomePage"><img src="<?=$config['layout_dir'] ?>images/img-logo.png" width="395" height="43" alt="img-logo" /></a>
            <div class="cart-box" id="cart">
                <div class="info-cart">
                    <span class="heading-cart-box">Your basket</span><br />
                    <? $cart = $elems->qry_CartSummary(); ?>
                    <span><span class="cart_count"><?=$cart['items']+0 ?></span> items - <span class="cart_total"><?=price($cart['total']) ?></span></span>
                </div>
                <a href="<?=$config['dir'] ?>cart" class="button bg-golden">checkout</a>
            </div>
            <div class="search-box">
                <form method="get" action="<?=$config['dir'] ?>search" id="search">
                    <label for="keywords">SEARCH</label>
                    <input type="text" name="keyword" id="keywords" value="Keyword" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue;" />
                    <input type="submit" class="button" value="GO" />
                </form>
            </div>
        </div>
        <ul class="main-nav" id="main-nav">
        <?
            if($Fusebox["circuit"]=="shop" && $Fusebox["fuseaction"] == "category")
                $top_category = $elems->qry_TopCategory($category['id']);
            if($Fusebox["circuit"]=="shop" && $Fusebox["fuseaction"] == "product")
                $top_category = $elems->qry_TopCategory($product['category_id']);

			$menu = $elems->qry_Menu();
			foreach($menu['categories'] as $row)
			{
                if($top_category['id'] == $row['id'])
                    $class = 'class="active"';
                else
                    $class = '';

				echo '<li '.$class.'><a href="'.category_url($row['id'], $row['name']).'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a>';
				if(count($row['children']))
				{
                    echo '<ul class="sub-menu">';
                    foreach($row['children'] as $child)
                        echo '<li><a href="'.category_url($child['link_category_id']?$child['link_category_id']:$child['id'], $child['name']).'">'.htmlentities($child['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
                    echo '</ul>';
				}
				echo '</li>';
			}
			foreach($menu['pages'] as $key=>$row)
            {
                $class = ($key+1 == count($menu['pages']))?'class="omega"':'';
                echo '<li><a href="'.$config['dir'].$row['url'].'" '.$class.'>'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
            }
		?>
            <li <?=($Fusebox["circuit"]=="shop" && $Fusebox["fuseaction"] == "shop_by_brand")?'class="active"':'' ?>><a href="<?= $config['dir'] ?>shop-by-brand" title="Shop by Brand">Shop<br />by Brand </a></li>
            <li class="omega <?=($Fusebox["circuit"]=="shop" && $Fusebox["fuseaction"] == "view_by_category")?'active':'' ?>"><a href="<?= $config['dir'] ?>view-by-category" title="View by Category">VIEW BY CATEGORY</a></li>
        </ul>
    </div>
    </div>
</div>
<!--===================
Header Section End Here
======================--> 