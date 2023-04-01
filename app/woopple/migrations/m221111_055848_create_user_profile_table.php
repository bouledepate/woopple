<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_profile}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m221111_055848_create_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'first_name' => $this->string(255),
            'second_name' => $this->string(255),
            'last_name' => $this->string(255),
            'birthday' => $this->date(),
            'education' => $this->string(),
            'skills' => $this->string(),
            'notes' => $this->string(),
            'avatar' => $this->string(),
            'position' => $this->string(255)
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_profile-user_id}}',
            '{{%user_profile}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_profile-user_id}}',
            '{{%user_profile}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_profile-user_id}}',
            '{{%user_profile}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_profile-user_id}}',
            '{{%user_profile}}'
        );

        $this->dropTable('{{%user_profile}}');
    }
}
