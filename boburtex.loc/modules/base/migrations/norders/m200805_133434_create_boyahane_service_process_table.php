<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%boyahane_service_process}}`.
 */
class m200805_133434_create_boyahane_service_process_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%boyahane_service_process}}', [
            'id' => $this->primaryKey(),
            'service_id' => $this->integer(),
            'process_id' => $this->integer(),
            'name' => $this->string(255),
            'description' => $this->text(),
			'status' => $this->tinyInteger(),
			'created_by' => $this->bigInteger(),
			'created_at' => $this->integer(),
			'updated_by' => $this->bigInteger(),
			'updated_at' => $this->integer(),
        ]);

		$this->createIndex('idx-boyahane_service_process-service_id','boyahane_service_process','service_id');
		$this->addForeignKey('fk-boyahane_service_process-service_id','boyahane_service_process','service_id',
			'boyahane_services','id','CASCADE');
	
		$this->createIndex('idx-boyahane_service_process-process_id','boyahane_service_process','process_id');
		$this->addForeignKey('fk-boyahane_service_process-process_id','boyahane_service_process','process_id',
			'process','id','CASCADE');
	
		$this->createIndex('idx-boyahane_service_process-created_by','boyahane_service_process','created_by');
		$this->addForeignKey('fk-boyahane_service_process-created_by','boyahane_service_process','created_by',
			'users','id','CASCADE');
	
		$this->createIndex('idx-boyahane_service_process-updated_by','boyahane_service_process','updated_by');
		$this->addForeignKey('fk-boyahane_service_process-updated_by','boyahane_service_process','updated_by',
			'users','id','CASCADE');
	
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		/*$this->dropForeignKey('fk-boyahane_service_process-created_by','boyahane_service_process');
		$this->dropIndex('idx-boyahane_service_process-created_by','boyahane_service_process');
	
		$this->dropForeignKey('fk-boyahane_service_process-updated_by','boyahane_service_process');
		$this->dropIndex('idx-boyahane_service_process-updated_by','boyahane_service_process');
	
		$this->dropForeignKey('fk-boyahane_service_process-process_id','boyahane_service_process');
		$this->dropIndex('idx-boyahane_service_process-process_id','boyahane_service_process');
	
		$this->dropForeignKey('fk-boyahane_service_process-service_id','boyahane_service_process');
		$this->dropIndex('idx-boyahane_service_process-service_id','boyahane_service_process');*/
	
		$this->dropTable('{{%boyahane_service_process}}');
    }
}
