<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tayyorlov_nastel_acs}}`.
 */
class m200819_201603_create_tayyorlov_nastel_acs_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%tayyorlov_nastel_acs}}', [
            'id' => $this->primaryKey(),
            'nastel_no' => $this->string(20)->notNull(),
            'acs_doc_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        $this->createIndex(
            '{{%idx-tayyorlov_nastel_acs-nastel_no}}',
            '{{%tayyorlov_nastel_acs}}',
            'nastel_no'
        );

        // creates index for column `acs_doc_id`
        $this->createIndex(
            '{{%idx-tayyorlov_nastel_acs-acs_doc_id}}',
            '{{%tayyorlov_nastel_acs}}',
            'acs_doc_id'
        );

        // add foreign key for table `{{%acs_doc}}`
        $this->addForeignKey(
            '{{%fk-tayyorlov_nastel_acs-acs_doc_id}}',
            '{{%tayyorlov_nastel_acs}}',
            'acs_doc_id',
            '{{%bichuv_doc}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%acs_doc}}`
        $this->dropForeignKey(
            '{{%fk-tayyorlov_nastel_acs-acs_doc_id}}',
            '{{%tayyorlov_nastel_acs}}'
        );

        // drops index for column `acs_doc_id`
        $this->dropIndex(
            '{{%idx-tayyorlov_nastel_acs-acs_doc_id}}',
            '{{%tayyorlov_nastel_acs}}'
        );

        // drops index for column `nastel_no`
        $this->dropIndex(
            '{{%idx-tayyorlov_nastel_acs-nastel_no}}',
            '{{%tayyorlov_nastel_acs}}'
        );

        $this->dropTable('{{%tayyorlov_nastel_acs}}');
    }
}
