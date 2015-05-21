<?php
/**
 * @file
 * mayfair_product.features.inc
 */

/**
 * Implements hook_commerce_product_default_types().
 */
function mayfair_product_commerce_product_default_types() {
  $items = array(
    'product' => array(
      'type' => 'product',
      'name' => 'Product',
      'description' => 'A basic product type.',
      'help' => '',
      'revision' => 1,
    ),
  );
  return $items;
}
