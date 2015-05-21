<?php
/**
 * @file
 * mayfair_panels_pages.features.menu_custom.inc
 */

/**
 * Implements hook_menu_default_menu_custom().
 */
function mayfair_panels_pages_menu_default_menu_custom() {
  $menus = array();

  // Exported menu: menu-about-mayfair-house.
  $menus['menu-about-mayfair-house'] = array(
    'menu_name' => 'menu-about-mayfair-house',
    'title' => 'About Mayfair House',
    'description' => 'About Mayfair house for footer.',
  );
  // Exported menu: menu-customer-service.
  $menus['menu-customer-service'] = array(
    'menu_name' => 'menu-customer-service',
    'title' => 'Customer Service',
    'description' => 'Customer service menu for footer.',
  );
  // Exported menu: menu-online-order-services.
  $menus['menu-online-order-services'] = array(
    'menu_name' => 'menu-online-order-services',
    'title' => 'Online Order Services',
    'description' => 'Online order services menu for footer.',
  );
  // Translatables
  // Included for use with string extractors like potx.
  t('About Mayfair House');
  t('About Mayfair house for footer.');
  t('Customer Service');
  t('Customer service menu for footer.');
  t('Online Order Services');
  t('Online order services menu for footer.');


  return $menus;
}
