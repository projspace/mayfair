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
    <li><?php print l(t('Sign Up'), 'modal_forms/nojs/register'); ?></li>
    <li><?php print l(t('Login'), 'modal_forms/nojs/login'); ?></li>
  </ul>
</div>
