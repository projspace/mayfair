<?php
/**
 * @file
 * mayfair_panels_pages.features.menu_links.inc
 */

/**
 * Implements hook_menu_default_menu_links().
 */
function mayfair_panels_pages_menu_default_menu_links() {
  $menu_links = array();

  // Exported menu link: menu-about-mayfair-house_privacy-policy:<front>
  $menu_links['menu-about-mayfair-house_privacy-policy:<front>'] = array(
    'menu_name' => 'menu-about-mayfair-house',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Privacy Policy',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-about-mayfair-house_privacy-policy:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -49,
    'customized' => 1,
  );
  // Exported menu link: menu-about-mayfair-house_return-policy:<front>
  $menu_links['menu-about-mayfair-house_return-policy:<front>'] = array(
    'menu_name' => 'menu-about-mayfair-house',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Return Policy',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-about-mayfair-house_return-policy:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -47,
    'customized' => 1,
  );
  // Exported menu link: menu-about-mayfair-house_shipping-policy:<front>
  $menu_links['menu-about-mayfair-house_shipping-policy:<front>'] = array(
    'menu_name' => 'menu-about-mayfair-house',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Shipping Policy',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-about-mayfair-house_shipping-policy:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -48,
    'customized' => 1,
  );
  // Exported menu link: menu-about-mayfair-house_terms--conditions:<front>
  $menu_links['menu-about-mayfair-house_terms--conditions:<front>'] = array(
    'menu_name' => 'menu-about-mayfair-house',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Terms & Conditions',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-about-mayfair-house_terms--conditions:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -50,
    'customized' => 1,
  );
  // Exported menu link: menu-customer-service_888-280-1988:<nolink>
  $menu_links['menu-customer-service_888-280-1988:<nolink>'] = array(
    'menu_name' => 'menu-customer-service',
    'link_path' => '<nolink>',
    'router_path' => '<nolink>',
    'link_title' => '888-280-1988',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-customer-service_888-280-1988:<nolink>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -50,
    'customized' => 1,
  );
  // Exported menu link: menu-customer-service_contact-us:<front>
  $menu_links['menu-customer-service_contact-us:<front>'] = array(
    'menu_name' => 'menu-customer-service',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Contact Us',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-customer-service_contact-us:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -49,
    'customized' => 1,
  );
  // Exported menu link: menu-customer-service_gift-registry:<front>
  $menu_links['menu-customer-service_gift-registry:<front>'] = array(
    'menu_name' => 'menu-customer-service',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Gift Registry',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-customer-service_gift-registry:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -47,
    'customized' => 1,
  );
  // Exported menu link: menu-customer-service_store-location:<front>
  $menu_links['menu-customer-service_store-location:<front>'] = array(
    'menu_name' => 'menu-customer-service',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Store Location',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-customer-service_store-location:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -48,
    'customized' => 1,
  );
  // Exported menu link: menu-online-order-services_gift-cards:<front>
  $menu_links['menu-online-order-services_gift-cards:<front>'] = array(
    'menu_name' => 'menu-online-order-services',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Gift Cards',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-online-order-services_gift-cards:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -49,
    'customized' => 1,
  );
  // Exported menu link: menu-online-order-services_international-visitors:<front>
  $menu_links['menu-online-order-services_international-visitors:<front>'] = array(
    'menu_name' => 'menu-online-order-services',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'International Visitors',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-online-order-services_international-visitors:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -48,
    'customized' => 1,
  );
  // Exported menu link: menu-online-order-services_my-cart:cart
  $menu_links['menu-online-order-services_my-cart:cart'] = array(
    'menu_name' => 'menu-online-order-services',
    'link_path' => 'cart',
    'router_path' => 'cart',
    'link_title' => 'My Cart',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'menu-online-order-services_my-cart:cart',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -50,
    'customized' => 1,
  );
  // Translatables
  // Included for use with string extractors like potx.
  t('888-280-1988');
  t('Contact Us');
  t('Gift Cards');
  t('Gift Registry');
  t('International Visitors');
  t('My Cart');
  t('Privacy Policy');
  t('Return Policy');
  t('Shipping Policy');
  t('Store Location');
  t('Terms & Conditions');


  return $menu_links;
}
