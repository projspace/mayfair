<?php
/**
 * @file
 * "Brands" taxonomy terms migration for MayFair House website.
 */

/**
 * Mappings for taxonomy terms migration for Brands vocabulary.
 */
class MayFairMigrateBrandTermMigration extends Migration {
  /**
   * Constructor.
   */
  public function __construct($arguments) {
    parent::__construct($arguments);

    $query = Database::getConnection('default', 'legacy')
              ->select('shop_brands', 'b');
    $query->fields('b', array(
        'id',
        'name',
        'supplier_id',
      )
    );

    $this->source =  new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationTerm('brands');
    $this->map = new MigrateSQLMap(
      $this->machineName,
      array(
      'id' => array(
        'type' => 'int',
        'unsigned' => TRUE,
        'not null' => TRUE,
        'alias' => 'b',
      ),
      ),
      MigrateDestinationTerm::getKeySchema()
    );

    $this->addFieldMapping('name', 'name');

    // For now setting "supplier_id" in description. We need to confirm what
    // should we keep here.
    $this->addFieldMapping('description', 'supplier_id');

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
