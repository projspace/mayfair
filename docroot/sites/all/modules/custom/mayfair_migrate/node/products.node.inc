<?php
/**
 * @file
 * Product migration support for MayFair house.
 */

/**
 * Mappings for "Node" content migration fields.
 */
class MayFairMigrateProductsNodeMigration extends Migration {
  /**
   * Constructor.
   */
  public function __construct($arguments) {
    parent::__construct($arguments);

    $query = Database::getConnection('default', 'legacy')
      ->select('shop_products', 'p');

    $query->fields('p', array(
      'id',
      'name',
      'description',
      'inserted',
      'updated',
      'category_id',
      'brand_id',
      )
    );

    $this->source =  new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationNode('product');
    $this->map = new MigrateSQLMap(
      $this->machineName,
      array(
      'id' => array(
        'type' => 'int',
        'unsigned' => TRUE,
        'not null' => TRUE,
        'alias' => 'p',
      ),
      ),
      MigrateDestinationNode::getKeySchema()
    );

    $this->addFieldMapping('title', 'name');

    $this->addFieldMapping('body', 'description');
    $this->addFieldMapping('changed', 'inserted');
    $this->addFieldMapping('created', 'updated');

    $this->addFieldMapping('field_category', 'category_id')
      ->sourceMigration('MayfairMigrateCategoryTerms');
    $this->addFieldMapping('field_category:source_type')
     ->defaultValue('tid');

     $this->addFieldMapping('field_brand', 'brand_id')
      ->sourceMigration('MayfairMigrateBrandTerms');
    $this->addFieldMapping('field_brand:source_type')
     ->defaultValue('tid');

    // DNM.
    $this->addUnmigratedDestinations(array(
      'sticky',
      'revision',
    ), t('DNM'));

  }

  /**
   * Review a data row after fetch.
   *
   * @param object $row
   *   Data Row to prepare.
   *
   * @return bool
   *   FALSE to skip this row.
   */
  public function prepareRow($row) {

    // We need to do this processing because drupal needs unix timestamps
    $changed_date = new DateTime($row->updated, new DateTimeZone(date_default_timezone()));
    $row->updated = $changed_date->getTimestamp();

    $created_date = new DateTime($row->inserted, new DateTimeZone(date_default_timezone()));
    $row->inserted = $created_date->getTimestamp();
  }
}
