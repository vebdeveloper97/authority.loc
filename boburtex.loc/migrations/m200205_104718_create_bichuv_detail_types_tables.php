<?php

use yii\db\Migration;

/**
 * Class m200205_104718_create_bichuv_detail_types_tables
 */
class m200205_104718_create_bichuv_detail_types_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_detail_types}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'type_order' => $this->integer(5)->defaultValue(1),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bichuv_detail_types}}');
    }

}
