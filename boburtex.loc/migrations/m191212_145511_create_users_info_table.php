<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_info}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 */
class m191212_145511_create_users_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_info}}', [
            'users_id' => $this->bigInteger(),
            'fio' => $this->string(70),
            'smena' => $this->string(10),
            'tabel' => $this->string(20),
            'lavozim' => $this->string(40),
            'razryad' => $this->smallInteger(),
            'tel' => $this->string(15),
            'adress' => $this->string(70),
            'type' => $this->smallInteger()->defaultValue(1),
            'add_info' => $this->text(),
            'rfid_key' => $this->integer(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY(users_id)',
        ]);

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-users_info-users_id}}',
            '{{%users_info}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-users_info-users_id}}',
            '{{%users_info}}',
            'users_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
        $this->addColumn('{{%user_roles}}', 'department', $this->string(30));
        $this->upsert('{{%user_roles}}', ['id' => 9, 'role_name' => "To'quv hisobchi", 'code' => "TOQUV_HISOBCHI", 'department' => 'toquv'],true);
        $this->upsert('{{%user_roles}}', ['id' => 8, 'role_name' => "To'quvchi", 'code' => "TOQUV_TOQUVCHI", 'department' => 'toquv'],true);
        $this->upsert('{{%user_roles}}', ['id' => 7, 'role_name' => "To'quv kalite", 'code' => "TOQUV_KALITE", 'department' => 'toquv'],true);
        $this->upsert('{{%user_roles}}', ['id' => 6, 'role_name' => "To'quv master", 'code' => "TOQUV_MASTER", 'department' => 'toquv'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-users_info-users_id}}',
            '{{%users_info}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-users_info-users_id}}',
            '{{%users_info}}'
        );

        $this->dropTable('{{%users_info}}');
        $this->delete('{{%user_roles}}', ['id' => 9, 'role_name' => "To'quv hisobchi", 'code' => "TOQUV_HISOBCHI", 'department' => 'toquv']);
        $this->delete('{{%user_roles}}', ['id' => 8, 'role_name' => "To'quvchi", 'code' => "TOQUV_TOQUVCHI", 'department' => 'toquv']);
        $this->dropColumn('{{%user_roles}}', 'department');
    }
}
