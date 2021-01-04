<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%boyahane_services}}`.
 */
class m200805_133424_create_boyahane_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%boyahane_services}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
			'status' => $this->tinyInteger(),
			'created_by' => $this->bigInteger(),
			'created_at' => $this->integer(),
			'updated_by' => $this->bigInteger(),
			'updated_at' => $this->integer(),
        ]);
	
		$this->createIndex('idx-boyahane_services-created_by','boyahane_services','created_by');
		$this->addForeignKey('fk-boyahane_services-created_by','boyahane_services','created_by',
			'users','id','CASCADE');
	
		$this->createIndex('idx-boyahane_services-updated_by','boyahane_services','updated_by');
		$this->addForeignKey('fk-boyahane_services-updated_by','boyahane_services','updated_by',
			'users','id','CASCADE');
	
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		/*$this->dropForeignKey('fk-boyahane_services-created_by','boyahane_services');
		$this->dropIndex('idx-boyahane_services-created_by','boyahane_services');
	
		$this->dropForeignKey('fk-boyahane_services-updated_by','boyahane_services');
		$this->dropIndex('idx-boyahane_services-updated_by','boyahane_services');*/
	
		$this->dropTable('{{%boyahane_services}}');
    }
}
