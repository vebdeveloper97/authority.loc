<?php

use yii\db\Migration;

/**
 * Class m191216_105751_craete_bichuv_given_rolls_table
 */
class m191216_105751_create_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_given_rolls}}', [
            'id' => $this->primaryKey(),
            'reg_date' => $this->date(),
            'doc_number' => $this->string(50),
            'add_info' => $this->text(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('bichuv_given_rolls');
    }
}
