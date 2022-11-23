<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_token}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m221111_055736_create_auth_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_token}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'token' => $this->string(512)->unique()->notNull(),
            'expired' => $this->timestamp(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-auth_token-user_id}}',
            '{{%auth_token}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-auth_token-user_id}}',
            '{{%auth_token}}',
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
            '{{%fk-auth_token-user_id}}',
            '{{%auth_token}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-auth_token-user_id}}',
            '{{%auth_token}}'
        );

        $this->dropTable('{{%auth_token}}');
    }
}
