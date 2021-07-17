<?php

use Phalcon\Db\Index;
use Phalcon\Db\Column;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UserMigration_100
 */
class UserMigration_100 extends Migration
{
    const TABLE_NAME = 'user';

    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable(
            self::TABLE_NAME,
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_CHAR,
                            'default' => "uuid_generate_v4()",
                            'notNull' => true,
                            'size' => 36,
                            'comment' => "Staff ID",
                            'first' => true
                        ]
                    ),
                    new Column(
                        'firstname',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "unammed",
                            'notNull' => false,
                            'size' => 50,
                            'comment' => "FullName",
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'lastname',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 50,
                            'comment' => "Last Name",
                            'after' => 'firstname'
                        ]
                    ),
                    new Column(
                        'username',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 100,
                            'comment' => "Username",
                            'after' => 'lastname'
                        ]
                    ),
                    new Column(
                        'email',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'comment' => "Email Address",
                            'after' => 'username'
                        ]
                    ),
                    new Column(
                        'password',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'comment' => "User Password",
                            'after' => 'email'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_SMALLINTEGER,
                            'default'=> 0,
                            'notNull' => true,
                            'size' => 11,
                            'comment' => "User account status",
                            'after' => 'password'
                        ]
                    ),
                    new Column(
                        'avatar',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => false,
                            'comment' => "Avatar Src Image",
                            'after' => 'status'
                        ]
                    ),
                    new Column(
                        'phone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size'=> 15,
                            'notNull' => false,
                            'after' => 'email'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'comment' => "Created At",
                            'after' => 'phone'
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => false,
                            'comment' => "Row Updated At Time",
                            'after' => 'created_at'
                        ]
                    ),
                    new Column(
                        'created_ip',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 40,
                            'comment' => "Created ip",
                            'after' => 'updated_at'
                        ]
                    ),
                    new Column(
                        'updated_ip',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 40,
                            'comment' => "Update by Ip",
                            'after' => 'created_ip'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('pk_user_id', ['id'], 'PRIMARY KEY'),
                    new Index('unq_user', ['email'], 'UNIQUE')
                ],
            ]
        );
    }
}
