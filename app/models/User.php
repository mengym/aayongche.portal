<?php

/**
 * 数据库User的实体模型
 */
class User extends \ActiveRecord\Model
{
    static $table_name = 'user';
    static $primary_key = 'user_id';
    static $connection = 'main';
    static $db = 'aayongche_main';
}