<?php

use yii\db\Migration;

/**
 * Class m200802_073247_add_regions_sql
 */
class m200802_073247_add_regions_sql extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents(__DIR__."/sql_files/region.sql"));
        $this->execute("ALTER TABLE districts ADD INDEX(region_id)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
          return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200802_073247_add_regions_sql cannot be reverted.\n";

        return false;
    }
    */
}
