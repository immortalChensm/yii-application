<?php

class m181226_084735_create_news_table extends CDbMigration
{
	public function up()
	{
	    $this->dropTable('tbl_news');
        $this->createTable('tbl_news', array(
            'id' => 'pk',
            'title' => 'string NOT NULL',
            'content' => 'text',
            "addTime"=>"int"
        ));
	}

	public function down()
	{
		//echo "m181226_084735_create_news_table does not support migration down.\n";
		//return false;
        $this->dropTable('tbl_news');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}