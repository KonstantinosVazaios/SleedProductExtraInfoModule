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

class SleedProductExtraInfoModel extends ObjectModel
{
    public $id_product_extra_info;
    public $id_product;
    public $title;
    public $content;

    public static $definition = array(
        'table' => 'sleedproductextrainfo',
        'primary' => 'id_product_extra_info',
        'multilang' => true,
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true),
            'title' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isString',
                'required' => true
            ),
            'content' => array('type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isString',
                'required' => true
            )
        )
    );

    public static function getExtraInfoIds($productId)
    {
        $sql = 'SELECT id_product_extra_info FROM `'._DB_PREFIX_.'sleedproductextrainfo` extra_info_table
        WHERE extra_info_table.id_product = '.(int)$productId;

        $results = Db::getInstance()->ExecuteS($sql);
        return $results;
    }

    public static function getExtraInfo($productId)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'sleedproductextrainfo` extra_info_table
        LEFT JOIN `'._DB_PREFIX_.'sleedproductextrainfo_lang` extra_info_lang_table
        ON (extra_info_lang_table.id_product_extra_info = extra_info_table.id_product_extra_info)
	    WHERE extra_info_table.id_product = '.(int)$productId;

        $results = Db::getInstance()->ExecuteS($sql);
        return $results;
    }

    public static function getExtraInfoByLangId($productId, $id_lang)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'sleedproductextrainfo` extra_info_table
        RIGHT JOIN `'._DB_PREFIX_.'sleedproductextrainfo_lang` extra_info_lang_table
        ON (extra_info_lang_table.id_product_extra_info = extra_info_table.id_product_extra_info AND extra_info_lang_table.`id_lang` = '.(int)$id_lang.')
	    WHERE extra_info_table.id_product = '.(int)$productId;

        $results = Db::getInstance()->ExecuteS($sql);
        return $results;
    }
}
