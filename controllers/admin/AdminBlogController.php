<?php

require_once _PS_MODULE_DIR_.'bloglang/classes/Blog.php';

class AdminBlogController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = Blog::$definition['table'];
        $this->className = Blog::class;
        $this->module = Module::getInstanceByName('bloglang');
        $this->identifier = Blog::$definition['primary'];
        $this->_orderBy = Blog::$definition['primary'];
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = [
            'id_blog' => [
                'title' => 'Id', 
                'search' => true
            ],
            'titre' => [
                'title' => 'Titre',
                'search' => true,
                'filter_key' => 'b!titre'
            ],
            'description' => [
                'title' => 'Description',
                'search' => true
            ],
            'date_add' => [
                'title' => 'Date Ajout',
                'search' => true
            ]
        ];
        $this->_select .= 's.titre, s.description';
        $this->_join .= 'INNER JOIN '._DB_PREFIX_.'blog_lang s ON s.id_blog = a.id_blog';

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->addRowAction('view');
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Article'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => 'Titre',
                    'name' => 'titre',
                    'required' => true,
                    'lang' => true
                ],
                [
                    'type' => 'textarea',
                    'label' => 'Description',
                    'name' => 'description',
                    'required' => true,
                    'lang' => true
                ],
                [
                    'type' => 'date',
                    'label' => 'Date d\'ajout',
                    'name' => 'date_add',
                    'required' => true
                ],
            ],
            'submit' => [
                'title' => 'Ajouter',
                'class' => 'btn btn-primary'
            ]
        ];


        return parent::renderForm();
    }
}