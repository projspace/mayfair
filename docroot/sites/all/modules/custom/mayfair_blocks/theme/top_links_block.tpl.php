<?php
/**
 * @file
 * Template file for top links block.
 */
?>
<div class="top-links-block">
  <ul>
    <li><?php print l(t('Home'), '<front>'); ?></li>
    <li><?php print l(t('Corporate Gifts'), '<front>'); ?></li>
    <li><?php print l(t('Gift & Bridal Registry'), '<front>'); ?></li>
    <?php if(!user_is_logged_in()) { ?>
      <li><a class= 'ctools-use-modal ctools-modal-modal-popup-medium' href= 'modal_forms/nojs/register'><?php print t('Sign Up'); ?></a></li>
      <li><a class= 'ctools-use-modal ctools-modal-modal-popup-small' href= 'modal_forms/nojs/login'><?php print t('Login'); ?></a></li>
    <?php } ?>
  </ul>
</div>
