<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_konveyer}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 */
class m200201_112237_create_tikuv_konveyer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_konveyer}}', [
            'id' => $this->primaryKey(),
            'number' => $this->integer(),
            'code' => $this->string(30),
            'name' => $this->string(50),
            'users_id' => $this->bigInteger(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        // creates index for column `number`
        $this->createIndex(
            '{{%idx-tikuv_konveyer-number}}',
            '{{%tikuv_konveyer}}',
            'number'
        );
        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-tikuv_konveyer-users_id}}',
            '{{%tikuv_konveyer}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-tikuv_konveyer-users_id}}',
            '{{%tikuv_konveyer}}',
            'users_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
        $this->insert('{{%user_roles}}', ['role_name' => "Tikuv Konveyer masteri", 'code' => "TIKUV_KONVEYER", 'department' => 'tikuv']);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 1, 'name' => "1-Konveyer", 'code' => "TIKUV_KONVEYER_1", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 2, 'name' => "2-Konveyer", 'code' => "TIKUV_KONVEYER_2", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 3, 'name' => "3-Konveyer", 'code' => "TIKUV_KONVEYER_3", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 4, 'name' => "4-Konveyer", 'code' => "TIKUV_KONVEYER_4", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 5, 'name' => "5-Konveyer", 'code' => "TIKUV_KONVEYER_5", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 6, 'name' => "6-Konveyer", 'code' => "TIKUV_KONVEYER_6", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 7, 'name' => "7-Konveyer", 'code' => "TIKUV_KONVEYER_7", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 8, 'name' => "8-Konveyer", 'code' => "TIKUV_KONVEYER_8", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 9, 'name' => "9-Konveyer", 'code' => "TIKUV_KONVEYER_9", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 10, 'name' => "10-Konveyer", 'code' => "TIKUV_KONVEYER_10", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 11, 'name' => "11-Konveyer", 'code' => "TIKUV_KONVEYER_11", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 12, 'name' => "12-Konveyer", 'code' => "TIKUV_KONVEYER_12", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 13, 'name' => "13-Konveyer", 'code' => "TIKUV_KONVEYER_13", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 14, 'name' => "14-Konveyer", 'code' => "TIKUV_KONVEYER_14", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 15, 'name' => "15-Konveyer", 'code' => "TIKUV_KONVEYER_15", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 16, 'name' => "16-Konveyer", 'code' => "TIKUV_KONVEYER_16", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 17, 'name' => "17-Konveyer", 'code' => "TIKUV_KONVEYER_17", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 18, 'name' => "18-Konveyer", 'code' => "TIKUV_KONVEYER_18", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 19, 'name' => "19-Konveyer", 'code' => "TIKUV_KONVEYER_19", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 20, 'name' => "20-Konveyer", 'code' => "TIKUV_KONVEYER_20", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 21, 'name' => "21-Konveyer", 'code' => "TIKUV_KONVEYER_21", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 22, 'name' => "22-Konveyer", 'code' => "TIKUV_KONVEYER_22", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 23, 'name' => "23-Konveyer", 'code' => "TIKUV_KONVEYER_23", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 24, 'name' => "24-Konveyer", 'code' => "TIKUV_KONVEYER_24", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 25, 'name' => "25-Konveyer", 'code' => "TIKUV_KONVEYER_25", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 26, 'name' => "26-Konveyer", 'code' => "TIKUV_KONVEYER_26", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 27, 'name' => "27-Konveyer", 'code' => "TIKUV_KONVEYER_27", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
        $this->insert('{{%tikuv_konveyer}}', ['number' => 28, 'name' => "28-Konveyer", 'code' => "TIKUV_KONVEYER_28", 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_konveyer-users_id}}',
            '{{%tikuv_konveyer}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-tikuv_konveyer-users_id}}',
            '{{%tikuv_konveyer}}'
        );

        // drops index for column `number`
        $this->dropIndex(
            '{{%idx-tikuv_konveyer-number}}',
            '{{%tikuv_konveyer}}'
        );

        $this->dropTable('{{%tikuv_konveyer}}');
    }
}
