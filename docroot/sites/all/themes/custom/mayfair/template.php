<?php

/**
 * Override CSS files
 *
 * @param $css
 *   An array of CSS files
 */
function mayfair_css_alter(&$css) {
    unset($css [drupal_get_path('module', 'modal_forms') . '/css/modal_forms_popup.css']);
    unset($css [drupal_get_path('module', 'ctools') . '/css/modal.css']);
}
