<?php
/**
 * @file
 * "Colors" taxonomy terms migration for MayFair House website.
 */

/**
 * Mappings for taxonomy terms migration for Colors vocabulary.
 */
class MayFairMigrateColorTermMigration extends Migration {
  /**
   * Constructor.
   */
  public function __construct($arguments) {
    parent::__construct($arguments);

    $query = Database::getConnection('default', 'legacy')
              ->select('shop_colors', 'c');
    $query->fields('c', array(
        'id',
        'name',
        'code',
      )
    );

    $this->source =  new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationTerm('color');
    $this->map = new MigrateSQLMap(
      $this->machineName,
      array(
      'id' => array(
        'type' => 'int',
        'unsigned' => TRUE,
        'not null' => TRUE,
        'alias' => 'c',
      ),
      ),
      MigrateDestinationTerm::getKeySchema()
    );

    $this->addFieldMapping('name', 'name');
    $this->addFieldMapping('description', 'code');

    // DNM.
    $this->addUnmigratedDestinations(array(
      'parent',
      'parent_name',
      'format',
      'weight',
      'path',
      'pathauto',
      ), t('DNM'));
  }
}
