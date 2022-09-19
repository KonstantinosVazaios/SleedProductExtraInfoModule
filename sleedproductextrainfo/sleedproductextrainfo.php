<?php

/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__).'/classes/SleedProductExtraInfoModel.php');

class SleedProductExtraInfo extends Module
{
    public function __construct()
    {
        $this->name = 'sleedproductextrainfo';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Sleed';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Extra Information For Products');
        $this->description = $this->l('Add additional information to your products');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
            $this->prepareDatabase() &&
            $this->registerHook('displayAdminProductsExtra') &&
            $this->registerHook('actionProductUpdate') &&
            $this->registerHook('displayFooterProduct');
    }

    protected function prepareDatabase()
    {
        $sql = array();

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'sleedproductextrainfo` (
            `id_product_extra_info` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_product` int(10) unsigned NOT NULL,
            `title` VARCHAR(100) NOT NULL,
            `content` VARCHAR(100) NOT NULL,
            PRIMARY KEY (`id_product_extra_info`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        return true;
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            $this->cleanDatabase();
    }

    protected function cleanDatabase()
    {
        $sql = array();

        $sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'sleedproductextrainfo`;';

        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }
        return true;
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        if (Tools::isSubmit('submitUpdateInfo')) {
            $extraInfoId = Tools::getValue('submitUpdateInfo');
            $title = Tools::getValue("title_extra_info_{$extraInfoId}");
            $content = Tools::getValue("content_extra_info_{$extraInfoId}");

            if ($title && $content) {
                $this->updateProductInfo($extraInfoId, $title, $content);
            } else {
                $this->context->controller->errors[] = "Title & Content fields are required";
            }
        }

        if (Tools::isSubmit('submitDeleteInfo')) {
            $extraInfoId = Tools::getValue('submitDeleteInfo');

            $this->deleteProductInfo($extraInfoId);
        }

        return $this->displayProductExtraInfo($params);
    }

    public function displayProductExtraInfo($params)
    {
        $id_product = !empty($params['id_product']) ? $params['id_product'] : Tools::getValue('id_product');

        $extraInfo = $this->getProductExtraInfo($id_product);

        $this->context->smarty->assign(array(
            'extraInfo' => $extraInfo
        ));

        return $this->display(__FILE__, 'views/templates/admin/productextrainfo-back.tpl');
    }


    public function getProductExtraInfo($id_product)
    {
        $productId = pSQL(Tools::getValue('id_product'));

        return SleedProductExtraInfoModel::getExtraInfoByProductId($productId);
    }

    public function hookActionProductUpdate($params)
    {
        if (!Tools::isSubmit('submitted_tabs') ||
            !in_array('sleedproductextrainfo', Tools::getValue('submitted_tabs'))
        ) {
            return;
        }

        $title = Tools::getValue("title");
        $content = Tools::getValue("content");

        if ($title && $content) {
            $this->addProductInfo($params['id_product'], $title, $content);
        } else {
            $this->context->controller->errors[] = "Title & Content fields are required";
        }
    }

    public function addProductInfo($id_product, $title, $content)
    {
        $extraInfoModel = new SleedProductExtraInfoModel();
        $extraInfoModel->id_product = $id_product;
        $extraInfoModel->title = $title;
        $extraInfoModel->content = $content;
        $extraInfoModel->add();
    }

    public function updateProductInfo($extraInfoId, $title, $content)
    {
        $extraInfoModel = new SleedProductExtraInfoModel($extraInfoId);
        $extraInfoModel->title = $title;
        $extraInfoModel->content = $content;
        $extraInfoModel->update();
    }

    public function deleteProductInfo($extraInfoId)
    {
        $extraInfoModel = new SleedProductExtraInfoModel($extraInfoId);
        if ($extraInfoModel) {
            $extraInfoModel->delete();
        }
    }


    // FRONT OFFICE
    public function hookDisplayFooterProduct()
    {
        $id_product = !empty($params['id_product']) ? $params['id_product'] : Tools::getValue('id_product');

        $this->context->smarty->assign(array(
            'extraInfo' => $this->getProductExtraInfo($id_product)
        ));
        return $this->display(__FILE__, 'views/templates/hook/productextrainfo-front.tpl');
    }
}
