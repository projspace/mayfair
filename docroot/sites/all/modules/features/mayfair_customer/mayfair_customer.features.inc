<?php
/**
 * @file
 * mayfair_customer.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function mayfair_customer_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
