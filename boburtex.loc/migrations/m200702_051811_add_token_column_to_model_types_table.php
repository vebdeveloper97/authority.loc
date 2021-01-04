<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_types}}`.
 */
class m200702_051811_add_token_column_to_model_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%model_types}}', 'token', $this->string(50)->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%model_types}}', 'token');
    }
}
