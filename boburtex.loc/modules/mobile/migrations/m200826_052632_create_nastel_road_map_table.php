<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%nastel_road_map}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_process}}`
 */
class m200826_052632_create_nastel_road_map_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%nastel_road_map}}', [
            'id' => $this->primaryKey(),
            'nastel_no' => $this->string(50),
            'mobile_process_id' => $this->integer(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `mobile_process_id`
        $this->createIndex(
            '{{%idx-nastel_road_map-mobile_process_id}}',
            '{{%nastel_road_map}}',
            'mobile_process_id'
        );

        // add foreign key for table `{{%mobile_process}}`
        $this->addForeignKey(
            '{{%fk-nastel_road_map-mobile_process_id}}',
            '{{%nastel_road_map}}',
            'mobile_process_id',
            '{{%mobile_process}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_process}}`
        $this->dropForeignKey(
            '{{%fk-nastel_road_map-mobile_process_id}}',
            '{{%nastel_road_map}}'
        );

        // drops index for column `mobile_process_id`
        $this->dropIndex(
            '{{%idx-nastel_road_map-mobile_process_id}}',
            '{{%nastel_road_map}}'
        );

        $this->dropTable('{{%nastel_road_map}}');
    }
}
