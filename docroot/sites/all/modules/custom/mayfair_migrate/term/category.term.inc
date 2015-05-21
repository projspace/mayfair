<?php
/**
 * @file
 * "Category" taxonomy terms migration for MayFair House website.
 */

/**
 * Mappings for taxonomy terms migration for Category vocabulary.
 */
class MayFairMigrateCategoryTermMigration extends Migration {
  /**
   * Constructor.
   */
  public function __construct($arguments) {
    parent::__construct($arguments);

    $query = Database::getConnection('default', 'legacy')
              ->select('shop_categories', 'c');
    $query->fields('c', array(
        'id',
        'name',
        'content',
        'parent_id'
      )
    );

    $this->source =  new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationTerm('category');
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
    $this->addFieldMapping('description', 'content');
    $this->addFieldMapping('parent', 'parent_id')
      ->sourceMigration('MayfairMigrateCategoryTerms');

    // DNM.
    $this->addUnmigratedDestinations(array(
      'format',
      'weight',
      'path',
      'pathauto',
      'parent_name',
      ), t('DNM'));
  }
}
