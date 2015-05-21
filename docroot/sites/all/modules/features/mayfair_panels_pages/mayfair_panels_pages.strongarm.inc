<?php
/**
 * @file
 * mayfair_panels_pages.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function mayfair_panels_pages_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'logo_block_link';
  $strongarm->value = '<front>';
  $export['logo_block_link'] = $strongarm;

  return $export;
}
