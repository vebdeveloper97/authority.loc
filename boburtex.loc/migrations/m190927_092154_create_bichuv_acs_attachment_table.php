<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_acs_attachment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_acs}}`
 */
class m190927_092154_create_bichuv_acs_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_acs_attachment}}', [
            'id' => $this->primaryKey(),
            'bichuv_acs_id' => $this->integer(),
            'name' => $this->string(),
            'size' => $this->integer(),
            'extension' => $this->string(10),
            'type' => $this->string(120),
            'path' => $this->string(),
            'isMain' => $this->boolean(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_acs_id`
        $this->createIndex(
            '{{%idx-bichuv_acs_attachment-bichuv_acs_id}}',
            '{{%bichuv_acs_attachment}}',
            'bichuv_acs_id'
        );

        // add foreign key for table `{{%bichuv_acs}}`
        $this->addForeignKey(
            '{{%fk-bichuv_acs_attachment-bichuv_acs_id}}',
            '{{%bichuv_acs_attachment}}',
            'bichuv_acs_id',
            '{{%bichuv_acs}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_acs}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_acs_attachment-bichuv_acs_id}}',
            '{{%bichuv_acs_attachment}}'
        );

        // drops index for column `bichuv_acs_id`
        $this->dropIndex(
            '{{%idx-bichuv_acs_attachment-bichuv_acs_id}}',
            '{{%bichuv_acs_attachment}}'
        );

        $this->dropTable('{{%bichuv_acs_attachment}}');
    }
}
