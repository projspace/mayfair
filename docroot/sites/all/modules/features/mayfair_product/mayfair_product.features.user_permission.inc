<?php
/**
 * @file
 * mayfair_product.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function mayfair_product_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'access checkout'.
  $permissions['access checkout'] = array(
    'name' => 'access checkout',
    'roles' => array(
      'administrator' => 'administrator',
    ),
    'module' => 'commerce_checkout',
  );

  return $permissions;
}
