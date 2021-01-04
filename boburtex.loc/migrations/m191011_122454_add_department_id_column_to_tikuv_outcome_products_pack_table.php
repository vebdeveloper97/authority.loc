<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%department}}`
 */
class m191011_122454_add_department_id_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'department_id', $this->integer());

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-department_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'department_id'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-department_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'department_id',
            '{{%toquv_departments}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-department_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-department_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'department_id');
    }
}
