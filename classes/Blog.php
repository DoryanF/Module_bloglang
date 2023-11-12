<?php

class Blog extends ObjectModel
{
    public $id_blog;
    public $titre;
    public $description;
    public $date_add;

    public static $definition = [
        'table' => 'blog',
        'primary' => 'id_blog',
        'multilang' => true,
        'fields' => [
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],

            /*lang fields */

            'titre' => ['type' => self::TYPE_STRING, 'lang' => true , 'validate' => 'isGenericName', 'required' => true],
            'description' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true]
        ]
    ];
    
    public function getAll()
    {
        return Db::getInstance()->executeS(
            'SELECT * FROM '._DB_PREFIX_.'blog INNER JOIN '._DB_PREFIX_.'blog_lang s ON s.id_blog = '._DB_PREFIX_.'blog.id_blog'
        );
    }
}