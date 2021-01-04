<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notifictions}}`.
 */
class m190826_050558_create_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(1),
            'body' => $this->text(),
            'subject' => $this->string(),
            'dept_from' => $this->integer(),
            'dept_to' => $this->integer(),
            'from' => $this->integer(),
            'to' => $this->integer(),
            'expire' => $this->dateTime(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //doc_id
        $this->createIndex(
            'idx-notifications-dept_from',
            'notifications',
            'doc_id'
        );

        $this->addForeignKey(
            'fk-notifications-dept_from',
            'notifications',
            'dept_from',
            'toquv_departments',
            'id'
        );
        //doc_id
        $this->createIndex(
            'idx-notifications-dept_to',
            'notifications',
            'doc_id'
        );

        $this->addForeignKey(
            'fk-notifications-dept_to',
            'notifications',
            'dept_to',
            'toquv_departments',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //dept_from
        $this->dropForeignKey(
            'fk-notifications-dept_from',
            'notifications'
        );

        $this->dropIndex(
            'idx-notifications-dept_from',
            'notifications'
        );

        //dept_from
        $this->dropForeignKey(
            'fk-notifications-dept_to',
            'notifications'
        );

        $this->dropIndex(
            'idx-notifications-dept_to',
            'notifications'
        );

        $this->dropTable('{{%notifications}}');
    }
}
