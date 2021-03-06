<?php
/**
 * @file
 * mayfair_panels_pages.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function mayfair_panels_pages_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'site_template__homepage';
  $handler->task = 'site_template';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Homepage',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'name' => 'homepage',
    'access' => array(
      'plugins' => array(
        0 => array(
          'name' => 'front',
          'settings' => NULL,
          'not' => FALSE,
        ),
      ),
      'logic' => 'and',
    ),
  );
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
  $display->uuid = 'e1bd3aa4-798c-4cbd-8b1e-5c82345baec7';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-25af7dd2-7c84-4fe9-a6a7-906cf6768957';
    $pane->panel = 'bottom';
    $pane->type = 'panels_mini';
    $pane->subtype = 'footer';
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
    $pane->uuid = '25af7dd2-7c84-4fe9-a6a7-906cf6768957';
    $display->content['new-25af7dd2-7c84-4fe9-a6a7-906cf6768957'] = $pane;
    $display->panels['bottom'][0] = 'new-25af7dd2-7c84-4fe9-a6a7-906cf6768957';
    $pane = new stdClass();
    $pane->pid = 'new-ad85fbd8-3b2d-4ac9-8e24-db3f499e57d8';
    $pane->panel = 'top';
    $pane->type = 'panels_mini';
    $pane->subtype = 'header';
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
    $pane->locks = array(
      'type' => 'none',
      'regions' => array(
        'top' => 'top',
      ),
    );
    $pane->uuid = 'ad85fbd8-3b2d-4ac9-8e24-db3f499e57d8';
    $display->content['new-ad85fbd8-3b2d-4ac9-8e24-db3f499e57d8'] = $pane;
    $display->panels['top'][0] = 'new-ad85fbd8-3b2d-4ac9-8e24-db3f499e57d8';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = 'new-ad85fbd8-3b2d-4ac9-8e24-db3f499e57d8';
  $handler->conf['display'] = $display;
  $export['site_template__homepage'] = $handler;

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'site_template__panel_context_d07dda8e-273f-4218-85b6-5d071481ea7a';
  $handler->task = 'site_template';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 2;
  $handler->conf = array(
    'title' => 'Product',
    'no_blocks' => 1,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'name' => '',
    'access' => array(
      'plugins' => array(
        0 => array(
          'name' => 'node_type',
          'settings' => array(
            'type' => array(
              'product' => 'product',
            ),
          ),
          'context' => 'node',
          'not' => FALSE,
        ),
      ),
      'logic' => 'and',
    ),
  );
  $display = new panels_display();
  $display->layout = 'twocol_stacked';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'center' => NULL,
      'top' => NULL,
      'left' => NULL,
      'right' => NULL,
      'bottom' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '1166a90d-fb30-41b7-83a5-0fc8972270e2';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-22f8948f-fe4f-4490-a2c3-85b8bfa96bd7';
    $pane->panel = 'bottom';
    $pane->type = 'panels_mini';
    $pane->subtype = 'footer';
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
    $pane->uuid = '22f8948f-fe4f-4490-a2c3-85b8bfa96bd7';
    $display->content['new-22f8948f-fe4f-4490-a2c3-85b8bfa96bd7'] = $pane;
    $display->panels['bottom'][0] = 'new-22f8948f-fe4f-4490-a2c3-85b8bfa96bd7';
    $pane = new stdClass();
    $pane->pid = 'new-4eff8ea2-feaa-48ed-a94e-205b6fb22e34';
    $pane->panel = 'left';
    $pane->type = 'page_content';
    $pane->subtype = 'page_content';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'context' => 'argument_page_content_1',
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
    $pane->uuid = '4eff8ea2-feaa-48ed-a94e-205b6fb22e34';
    $display->content['new-4eff8ea2-feaa-48ed-a94e-205b6fb22e34'] = $pane;
    $display->panels['left'][0] = 'new-4eff8ea2-feaa-48ed-a94e-205b6fb22e34';
    $pane = new stdClass();
    $pane->pid = 'new-39baa94e-83b4-419c-9912-85ec9e92889f';
    $pane->panel = 'top';
    $pane->type = 'panels_mini';
    $pane->subtype = 'header';
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
    $pane->uuid = '39baa94e-83b4-419c-9912-85ec9e92889f';
    $display->content['new-39baa94e-83b4-419c-9912-85ec9e92889f'] = $pane;
    $display->panels['top'][0] = 'new-39baa94e-83b4-419c-9912-85ec9e92889f';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $export['site_template__panel_context_d07dda8e-273f-4218-85b6-5d071481ea7a'] = $handler;

  return $export;
}

/**
 * Implements hook_default_page_manager_pages().
 */
function mayfair_panels_pages_default_page_manager_pages() {
  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'home';
  $page->task = 'page';
  $page->admin_title = 'Home';
  $page->admin_description = 'Front page for Mayfair.';
  $page->path = 'home';
  $page->access = array();
  $page->menu = array(
    'type' => 'none',
    'title' => '',
    'name' => 'navigation',
    'weight' => '0',
    'parent' => array(
      'type' => 'none',
      'title' => '',
      'name' => 'navigation',
      'weight' => '0',
    ),
  );
  $page->arguments = array();
  $page->conf = array(
    'admin_paths' => FALSE,
  );
  $page->default_handlers = array();
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_home__panel_context_1f56dc19-9397-446a-961f-68566c4bb086';
  $handler->task = 'page';
  $handler->subtask = 'home';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Panel',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
  );
  $display = new panels_display();
  $display->layout = 'flexible:3_region_stack';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'top' => NULL,
      'left_above' => NULL,
      'right_above' => NULL,
      'middle' => NULL,
      'left_below' => NULL,
      'right_below' => NULL,
      'bottom' => NULL,
      'center' => NULL,
      'top_' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '8f264973-adb9-4963-b635-d5694f46d8db';
  $display->content = array();
  $display->panels = array();
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $page->default_handlers[$handler->name] = $handler;
  $pages['home'] = $page;

  return $pages;

}
