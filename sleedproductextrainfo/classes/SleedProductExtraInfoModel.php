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
        'multilang' => false,
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true),
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isString',
                'required' => true
            ),
            'content' => array('type' => self::TYPE_STRING,
                'lang' => false,
                'validate' => 'isString',
                'required' => true
            )
        )
    );

    public static function getExtraInfoByProductId($productId)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'sleedproductextrainfo` WHERE id_product = '. $productId;
        $results = Db::getInstance()->ExecuteS($sql);
        return $results;
    }
}
