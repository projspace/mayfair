<?php
/**
 * @file
 * mayfair_panels_pages.panels_default.inc
 */

/**
 * Implements hook_default_panels_mini().
 */
function mayfair_panels_pages_default_panels_mini() {
  $export = array();

  $mini = new stdClass();
  $mini->disabled = FALSE; /* Edit this to true to make a default mini disabled initially */
  $mini->api_version = 1;
  $mini->name = 'footer';
  $mini->category = '';
  $mini->admin_title = 'Footer';
  $mini->admin_description = 'Footer area for Mayfair house.';
  $mini->requiredcontexts = array();
  $mini->contexts = array();
  $mini->relationships = array();
  $display = new panels_display();
  $display->layout = 'onecol';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'middle' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '6cdb835c-050f-4d3d-85d4-ecd522c5d1de';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-b4c925b8-2faa-4b9f-b4ad-22c1485f93c8';
    $pane->panel = 'middle';
    $pane->type = 'block';
    $pane->subtype = 'menu-menu-customer-service';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'override_title' => 0,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = 'b4c925b8-2faa-4b9f-b4ad-22c1485f93c8';
    $display->content['new-b4c925b8-2faa-4b9f-b4ad-22c1485f93c8'] = $pane;
    $display->panels['middle'][0] = 'new-b4c925b8-2faa-4b9f-b4ad-22c1485f93c8';
    $pane = new stdClass();
    $pane->pid = 'new-7d3eafe8-f793-4bcd-8a68-635fc7e3ffbc';
    $pane->panel = 'middle';
    $pane->type = 'block';
    $pane->subtype = 'menu-menu-online-order-services';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'override_title' => 0,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 1;
    $pane->locks = array();
    $pane->uuid = '7d3eafe8-f793-4bcd-8a68-635fc7e3ffbc';
    $display->content['new-7d3eafe8-f793-4bcd-8a68-635fc7e3ffbc'] = $pane;
    $display->panels['middle'][1] = 'new-7d3eafe8-f793-4bcd-8a68-635fc7e3ffbc';
    $pane = new stdClass();
    $pane->pid = 'new-113d7af9-f971-4b27-b979-feb7c0d90877';
    $pane->panel = 'middle';
    $pane->type = 'block';
    $pane->subtype = 'menu-menu-about-mayfair-house';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'override_title' => 0,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 2;
    $pane->locks = array();
    $pane->uuid = '113d7af9-f971-4b27-b979-feb7c0d90877';
    $display->content['new-113d7af9-f971-4b27-b979-feb7c0d90877'] = $pane;
    $display->panels['middle'][2] = 'new-113d7af9-f971-4b27-b979-feb7c0d90877';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = '0';
  $mini->display = $display;
  $export['footer'] = $mini;

  $mini = new stdClass();
  $mini->disabled = FALSE; /* Edit this to true to make a default mini disabled initially */
  $mini->api_version = 1;
  $mini->name = 'header';
  $mini->category = '';
  $mini->admin_title = 'Header';
  $mini->admin_description = 'Mayfair house header area.';
  $mini->requiredcontexts = array();
  $mini->contexts = array();
  $mini->relationships = array();
  $display = new panels_display();
  $display->layout = 'twocol_stacked';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'top' => NULL,
      'left' => NULL,
      'right' => NULL,
      'bottom' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '714bed99-d99b-431e-9e92-f490164b558e';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-4ddbc0f9-0b94-49e0-a448-03466c1e2be1';
    $pane->panel = 'left';
    $pane->type = 'block';
    $pane->subtype = 'logo_block-logo';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'override_title' => 1,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '4ddbc0f9-0b94-49e0-a448-03466c1e2be1';
    $display->content['new-4ddbc0f9-0b94-49e0-a448-03466c1e2be1'] = $pane;
    $display->panels['left'][0] = 'new-4ddbc0f9-0b94-49e0-a448-03466c1e2be1';
    $pane = new stdClass();
    $pane->pid = 'new-4c4d225f-349f-4cb0-b557-796acd142dde';
    $pane->panel = 'right';
    $pane->type = 'block';
    $pane->subtype = 'search-form';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'override_title' => 1,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '4c4d225f-349f-4cb0-b557-796acd142dde';
    $display->content['new-4c4d225f-349f-4cb0-b557-796acd142dde'] = $pane;
    $display->panels['right'][0] = 'new-4c4d225f-349f-4cb0-b557-796acd142dde';
    $pane = new stdClass();
    $pane->pid = 'new-d1d1c77a-1057-4c75-b9d3-b203126fef3b';
    $pane->panel = 'top';
    $pane->type = 'block';
    $pane->subtype = 'block-1';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'override_title' => 1,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = 'd1d1c77a-1057-4c75-b9d3-b203126fef3b';
    $display->content['new-d1d1c77a-1057-4c75-b9d3-b203126fef3b'] = $pane;
    $display->panels['top'][0] = 'new-d1d1c77a-1057-4c75-b9d3-b203126fef3b';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = '0';
  $mini->display = $display;
  $export['header'] = $mini;

  return $export;
}
