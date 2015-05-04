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
    $pane->pid = 'new-4220b8fb-aa0c-49dc-ae29-3cbbf1e3033a';
    $pane->panel = 'middle';
    $pane->type = 'block';
    $pane->subtype = 'mayfair_blocks-customer_service';
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
    $pane->uuid = '4220b8fb-aa0c-49dc-ae29-3cbbf1e3033a';
    $display->content['new-4220b8fb-aa0c-49dc-ae29-3cbbf1e3033a'] = $pane;
    $display->panels['middle'][0] = 'new-4220b8fb-aa0c-49dc-ae29-3cbbf1e3033a';
    $pane = new stdClass();
    $pane->pid = 'new-ae76bc68-1bd0-4643-920b-e24470252ba7';
    $pane->panel = 'middle';
    $pane->type = 'block';
    $pane->subtype = 'mayfair_blocks-online_order';
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
    $pane->uuid = 'ae76bc68-1bd0-4643-920b-e24470252ba7';
    $display->content['new-ae76bc68-1bd0-4643-920b-e24470252ba7'] = $pane;
    $display->panels['middle'][1] = 'new-ae76bc68-1bd0-4643-920b-e24470252ba7';
    $pane = new stdClass();
    $pane->pid = 'new-d77c7545-cdd1-42a2-8622-e9f7029edc32';
    $pane->panel = 'middle';
    $pane->type = 'block';
    $pane->subtype = 'mayfair_blocks-about_mayfair';
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
    $pane->uuid = 'd77c7545-cdd1-42a2-8622-e9f7029edc32';
    $display->content['new-d77c7545-cdd1-42a2-8622-e9f7029edc32'] = $pane;
    $display->panels['middle'][2] = 'new-d77c7545-cdd1-42a2-8622-e9f7029edc32';
    $pane = new stdClass();
    $pane->pid = 'new-e1070f95-3d54-49d8-af83-b5e3e750980d';
    $pane->panel = 'middle';
    $pane->type = 'block';
    $pane->subtype = 'mayfair_blocks-stay_connected';
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
    $pane->position = 3;
    $pane->locks = array();
    $pane->uuid = 'e1070f95-3d54-49d8-af83-b5e3e750980d';
    $display->content['new-e1070f95-3d54-49d8-af83-b5e3e750980d'] = $pane;
    $display->panels['middle'][3] = 'new-e1070f95-3d54-49d8-af83-b5e3e750980d';
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
    $pane->pid = 'new-225da00d-ba34-43fb-a9e1-a7496b6f4e43';
    $pane->panel = 'top';
    $pane->type = 'block';
    $pane->subtype = 'mayfair_blocks-top_links';
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
    $pane->uuid = '225da00d-ba34-43fb-a9e1-a7496b6f4e43';
    $display->content['new-225da00d-ba34-43fb-a9e1-a7496b6f4e43'] = $pane;
    $display->panels['top'][0] = 'new-225da00d-ba34-43fb-a9e1-a7496b6f4e43';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = '0';
  $mini->display = $display;
  $export['header'] = $mini;

  return $export;
}
