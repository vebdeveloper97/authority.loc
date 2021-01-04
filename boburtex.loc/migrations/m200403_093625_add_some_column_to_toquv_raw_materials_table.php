<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_raw_materials}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%color}}`
 */
class m200403_093625_add_some_column_to_toquv_raw_materials_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_raw_materials}}', 'color_id', $this->integer());

        // creates index for column `color_id`
        $this->createIndex(
            '{{%idx-toquv_raw_materials-color_id}}',
            '{{%toquv_raw_materials}}',
            'color_id'
        );

        // add foreign key for table `{{%color}}`
        $this->addForeignKey(
            '{{%fk-toquv_raw_materials-color_id}}',
            '{{%toquv_raw_materials}}',
            'color_id',
            '{{%toquv_raw_material_color}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%color}}`
        $this->dropForeignKey(
            '{{%fk-toquv_raw_materials-color_id}}',
            '{{%toquv_raw_materials}}'
        );

        // drops index for column `color_id`
        $this->dropIndex(
            '{{%idx-toquv_raw_materials-color_id}}',
            '{{%toquv_raw_materials}}'
        );

        $this->dropColumn('{{%toquv_raw_materials}}', 'color_id');
    }
}
