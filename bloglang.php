<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'bloglang/classes/Blog.php';

class BlogLang extends Module
{
    public function __construct()
    {
        $this->name = 'bloglang';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Doryan Fourrichon';
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];

        parent::__construct();
        $this->bootstrap = true;

        $this->displayName = $this->l('Blog Lang');
        $this->description = $this->l('Module pour créer des articles en fonction de la langue');
        $this->confirmUninstall = $this->l('Do you want to delete this module');
    }

    public function install()
    {
        if(!parent::install() ||
        !$this->createTable() ||
        !$this->createTableLang() ||
        !$this->installTab('AdminBlog','Ajout Article', 'AdminCatalog') ||
        !$this->registerHook('leftColumn')
        )
        {
            return false;
        }

            return true;
    }

    public function uninstall()
    {
        if(!parent::uninstall() ||
        !$this->deleteTable() ||
        !$this->deleteTableLang() ||
        !$this->uninstallTab() ||
        !$this->unregisterHook("leftColumn")    
        )
        {
            return false;
        }

            return true;
    }

    public function createTable()
    {
        return Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'blog(
               id_blog INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
               date_add DATETIME NOT NULL 
            )'
        );
    }

    public function deleteTable()
    {
        return Db::getInstance()->execute(
            'DROP TABLE IF EXISTS '._DB_PREFIX_.'blog'
        );
    }

    public function createTableLang()
    {
        return Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'blog_lang(
                id_blog INT UNSIGNED NOT NULL,
                id_lang INT UNSIGNED NOT NULL,
                titre VARCHAR(255) NOT NULL,
                description VARCHAR(255) NOT NULL,
                PRIMARY KEY (id_blog, id_lang)
            )'
        );
    }

    public function deleteTableLang()
    {
        return Db::getInstance()->execute(
            'DROP TABLE IF EXISTS '._DB_PREFIX_.'blog_lang'
        );
    }

    public function installTab($className, $tabName, $tabParentName = false)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();

        foreach(Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }

        if($tabParentName){

            $tab->id_parent = Tab::getIdFromClassName($tabParentName);
        } else{
            $tab->id_parent =  10;
        }

        $tab->module = $this->name;

        return $tab->add();
    }

    public function uninstallTab()
    {
        $idTab = Tab::getIdFromClassName('AdminBlog');
        $tab =  new Tab($idTab);
        $tab->delete();
    }

    public function hookDisplayLeftColumn($params)
    {
        $articles = new Blog();

        dump($articles->getAll());
        $this->context->smarty->assign([
            'articles' => $articles->getAll(),
        ]);
        return $this->display(__FILE__, 'views/templates/hook/leftcolumnblog.tpl');
    }
}