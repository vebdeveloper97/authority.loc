<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_raw_material_attachments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_raw_materials}}`
 * - `{{%attachment}}`
 */
class m200407_014622_create_toquv_raw_material_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_raw_material_attachments}}', [
            'id' => $this->primaryKey(),
            'toquv_raw_materials_id' => $this->integer(),
            'attachment_id' => $this->integer(),
            'is_main' => $this->smallInteger(1),
            'status' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `toquv_raw_materials_id`
        $this->createIndex(
            '{{%idx-toquv_raw_material_attachments-toquv_raw_materials_id}}',
            '{{%toquv_raw_material_attachments}}',
            'toquv_raw_materials_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-toquv_raw_material_attachments-toquv_raw_materials_id}}',
            '{{%toquv_raw_material_attachments}}',
            'toquv_raw_materials_id',
            '{{%toquv_raw_materials}}',
            'id',
            'NO ACTION'
        );

        // creates index for column `attachment_id`
        $this->createIndex(
            '{{%idx-toquv_raw_material_attachments-attachment_id}}',
            '{{%toquv_raw_material_attachments}}',
            'attachment_id'
        );

        // add foreign key for table `{{%attachment}}`
        $this->addForeignKey(
            '{{%fk-toquv_raw_material_attachments-attachment_id}}',
            '{{%toquv_raw_material_attachments}}',
            'attachment_id',
            '{{%attachments}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-toquv_raw_material_attachments-toquv_raw_materials_id}}',
            '{{%toquv_raw_material_attachments}}'
        );

        // drops index for column `toquv_raw_materials_id`
        $this->dropIndex(
            '{{%idx-toquv_raw_material_attachments-toquv_raw_materials_id}}',
            '{{%toquv_raw_material_attachments}}'
        );

        // drops foreign key for table `{{%attachment}}`
        $this->dropForeignKey(
            '{{%fk-toquv_raw_material_attachments-attachment_id}}',
            '{{%toquv_raw_material_attachments}}'
        );

        // drops index for column `attachment_id`
        $this->dropIndex(
            '{{%idx-toquv_raw_material_attachments-attachment_id}}',
            '{{%toquv_raw_material_attachments}}'
        );

        $this->dropTable('{{%toquv_raw_material_attachments}}');
    }
}
