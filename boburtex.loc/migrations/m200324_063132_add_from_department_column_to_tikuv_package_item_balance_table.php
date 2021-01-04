<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_package_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%from_department}}`
 */
class m200324_063132_add_from_department_column_to_tikuv_package_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_package_item_balance}}', 'from_department', $this->integer());

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-from_department}}',
            '{{%tikuv_package_item_balance}}',
            'from_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-from_department}}',
            '{{%tikuv_package_item_balance}}',
            'from_department',
            '{{%toquv_departments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%from_department}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_package_item_balance-from_department}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `toquv_departments`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-from_department}}',
            '{{%tikuv_package_item_balance}}'
        );

        $this->dropColumn('{{%tikuv_package_item_balance}}', 'from_department');
    }
}
