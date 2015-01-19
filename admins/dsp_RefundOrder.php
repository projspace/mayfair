<? if($ok): ?>
<h1>Refund Completed</h1>
<p>The order has now been completely refunded.</p>
<? else: ?>
<h1>Refund Error</h1>
<p><?php print $params['error']; ?></p>
<p>&nbsp;</p>
<p>There was a problem whilst refunding the order, please try again. If this persists please notify your designated support contact.</p>

<? endif; ?>