<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_doc_responsible}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%bichuv_doc}}`
 */
class m200501_193141_create_bichuv_doc_responsible_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_doc_responsible}}', [
            'id' => $this->primaryKey(),
            'users_id' => $this->bigInteger(),
            'bichuv_doc_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-bichuv_doc_responsible-users_id}}',
            '{{%bichuv_doc_responsible}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc_responsible-users_id}}',
            '{{%bichuv_doc_responsible}}',
            'users_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_doc_id`
        $this->createIndex(
            '{{%idx-bichuv_doc_responsible-bichuv_doc_id}}',
            '{{%bichuv_doc_responsible}}',
            'bichuv_doc_id'
        );

        // add foreign key for table `{{%bichuv_doc}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc_responsible-bichuv_doc_id}}',
            '{{%bichuv_doc_responsible}}',
            'bichuv_doc_id',
            '{{%bichuv_doc}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc_responsible-users_id}}',
            '{{%bichuv_doc_responsible}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc_responsible-users_id}}',
            '{{%bichuv_doc_responsible}}'
        );

        // drops foreign key for table `{{%bichuv_doc}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc_responsible-bichuv_doc_id}}',
            '{{%bichuv_doc_responsible}}'
        );

        // drops index for column `bichuv_doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc_responsible-bichuv_doc_id}}',
            '{{%bichuv_doc_responsible}}'
        );

        $this->dropTable('{{%bichuv_doc_responsible}}');
    }
}
