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
            PRIMARY KEY (`id_product_extra_info`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'sleedproductextrainfo_lang` (
            `id_product_extra_info` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_lang` int(10) unsigned NOT NULL ,
            `title` VARCHAR(250) NOT NULL,
            `content` VARCHAR(250) NOT NULL,
            PRIMARY KEY (`id_product_extra_info`, `id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

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

        $sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'sleedproductextrainfo_lang`;';

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
            $this->updateProductInfo();
        }

        if (Tools::isSubmit('submitDeleteInfo')) {
            $extraInfoId = Tools::getValue('submitDeleteInfo');
            $this->deleteProductInfo($extraInfoId);
        }

        return $this->displayProductExtraInfo($params);
    }

    public function displayProductExtraInfo($params)
    {
        $id_product = Tools::getValue('id_product');

        $extraInfo = SleedProductExtraInfoModel::getExtraInfo(pSQL($id_product));

        $results = $this->handleLangFields($extraInfo); 
        
        $this->context->smarty->assign(array(
            'extraInfo' => $results,
            'id_lang' => $this->context->language->id,
            'languages' => Language::getLanguages(),
            'default_form_language' => (int)Configuration::get('PS_LANG_DEFAULT')
        ));

        return $this->display(__FILE__, 'views/templates/admin/productextrainfo-back.tpl');
    }


    public function hookActionProductUpdate($params)
    {
        if (!Tools::isSubmit('submitted_tabs') ||
            !in_array('sleedproductextrainfo', Tools::getValue('submitted_tabs'))
        ) {
            return;
        }

        $data = $this->handleRequestData();

        if ($data) {
            $this->addProductInfo($params['id_product'], $data);
        } 
    }

    public function handleRequestData()
    {
        $languages = Language::getLanguages(false);
        $values_per_lang = array();
        $validated = true;

        foreach ($languages as $lang)
        {
            $title = Tools::getValue('title_'.$lang['id_lang']);
            $content = Tools::getValue('content_'.$lang['id_lang']);

            if ($title && $content) {
                $values_per_lang['title'][$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
                $values_per_lang['content'][$lang['id_lang']] = Tools::getValue('content_'.$lang['id_lang']);
            } else {
                $validated = false;
            }
        }

        if (!$validated) {
            $this->context->controller->errors[] = "Title & Content fields are required for all languages";
        }

        return $validated ? $values_per_lang : array();
    }

    // Grouping buy columns to use with lang input type
    public function handleLangFields($results)
    {
        if (count($results) == 0) return;
        
        $prev_id = $results[0]['id_product_extra_info'];
        $modified_results[] = $prev_id;

        foreach ($results as $result) {
            $result_id = $result['id_product_extra_info'];

            if ($result_id != $prev_id) {
                $modified_results[] = $result_id;
                $prev_id = $result_id;
            }
        }

        $resultsByLang = array_fill_keys($modified_results, array());
        $results_count = count($modified_results);

        for ($i=0; $i < $results_count; $i++) { 
            
            foreach ($results as $result) {
                $id_lang = $result['id_lang'];
                $infoId = $modified_results[$i];

                if ($infoId == $result['id_product_extra_info']) {
                    $resultsByLang[$infoId]['title'][$id_lang] = $result['title'];
                    $resultsByLang[$infoId]['content'][$id_lang] = $result['content'];
                }  
            } 

        }    

        return $resultsByLang;
        
    }

    public function addProductInfo($id_product, $data)
    {
        $id_product = Tools::getValue('id_product');

        $extraInfoModel = new SleedProductExtraInfoModel();
        $extraInfoModel->id_product = $id_product;

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang)
        {
            $extraInfoModel->title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            $extraInfoModel->content[$lang['id_lang']] = Tools::getValue('content_'.$lang['id_lang']);
        }

        $extraInfoModel->add();
    }

    public function updateProductInfo()
    {
        $id_product = Tools::getValue('id_product');
        $extraInfoIds = array_merge(...SleedProductExtraInfoModel::getExtraInfoIds($id_product));
        $languages = Language::getLanguages(false);

        foreach ($extraInfoIds as $info_id) {

            $extraInfoModel = new SleedProductExtraInfoModel($info_id);

            foreach ($languages as $lang) {
                $lang_id = $lang['id_lang'];
                $title = Tools::getValue("title_${info_id}_${lang_id}");
                $content = Tools::getValue("content_${info_id}_${lang_id}");

                if ($title && $content) {
                    $extraInfoModel->title[$lang_id] = Tools::getValue("title_${info_id}_${lang_id}");
                    $extraInfoModel->content[$lang_id] = Tools::getValue("content_${info_id}_${lang_id}");
                }
            }

            $extraInfoModel->update();

        }
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
        $id_language = $this->context->language->id;

        $extraInfo = SleedProductExtraInfoModel::getExtraInfoByLangId(pSQL($id_product), $id_language);

        $this->context->smarty->assign(array(
            'extraInfo' => $extraInfos
        ));
        return $this->display(__FILE__, 'views/templates/hook/productextrainfo-front.tpl');
    }
}
