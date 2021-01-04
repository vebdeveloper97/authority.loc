<?php

use yii\db\Migration;

/**
 * Class m200515_065447_add_to_department_column_tikuv_outcome_products_pack_table
 */
class m200515_065447_add_to_department_column_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_outcome_products_pack','to_department', $this->integer());

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-to_department}}',
            '{{%tikuv_outcome_products_pack}}',
            'to_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-to_department}}',
            '{{%tikuv_outcome_products_pack}}',
            'to_department',
            '{{%toquv_departments}}',
            'id'
        );
        $this->addColumn('{{%tikuv_outcome_products_pack}}','type', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-to_department}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-to_department}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        $this->dropColumn('tikuv_outcome_products_pack','to_department');
        $this->dropColumn('{{%tikuv_outcome_products_pack}}','type');
    }
}
