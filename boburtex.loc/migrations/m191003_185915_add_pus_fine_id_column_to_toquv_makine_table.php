<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_makine}}`.
 */
class m191003_185915_add_pus_fine_id_column_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_makine','pus_fine_id', $this->integer());

        // creates index for column `pus_fine_id`
        $this->createIndex(
            '{{%idx-toquv_makine-pus_fine_id}}',
            '{{%toquv_makine}}',
            'pus_fine_id'
        );

        // add foreign key for table `{{%toquv_makine}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine-pus_fine_id}}',
            '{{%toquv_makine}}',
            'pus_fine_id',
            '{{%toquv_pus_fine}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_makine}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine-pus_fine_id}}',
            '{{%toquv_makine}}'
        );

        // drops index for column `pus_fine_id`
        $this->dropIndex(
            '{{%idx-toquv_makine-pus_fine_id}}',
            '{{%toquv_makine}}'
        );

        $this->dropColumn('toquv_makine','pus_fine_id');
    }
}
