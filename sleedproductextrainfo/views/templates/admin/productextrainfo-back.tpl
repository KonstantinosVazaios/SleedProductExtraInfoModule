{*
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
    * @author    PrestaShop SA <contact@prestashop.com>
    * @copyright 2007-2022 PrestaShop SA
    * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
    * International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
    <div class="panel-heading">
        <i class="icon-sitemap"></i> {l s='Product Extra Information' mod='sleedproductextrainfo'}
    </div>
    <div class="form-wrapper">
        <input type="hidden" name="submitted_tabs[]" value="sleedproductextrainfo">
        <div style="width: 100%" class="form-group col-12">
            <label for="rowtitle">Infomartion Title</label>
            <input name="title" id="row-title" type="text" class="form-control">
        </div>
        <div style="width: 100%" class="form-group">
            <label for="exampleFormControlTextarea1">Information Content</label>
            <textarea name="content" id="row-content" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" name="submitAddproductAndStay" id="add-row" class="btn btn-success">ADD</button>
    </div>

    {foreach from=$extraInfo item=info}
    <div class="tab-container">
        <hr>
        <div style="width: 100%" class="form-group col-12">
                <label for="rowtitle">Information Title</label>
                <input name="title_extra_info_{$info['id_product_extra_info']|escape:'htmlall':'UTF-8'}" type="text" class="form-control" aria-describedby="emailHelp" value="{$info['title']|escape:'htmlall':'UTF-8'}">
        </div>
        <div style="width: 100%" class="form-group">
            <label for="exampleFormControlTextarea1">Information Content</label>
            <textarea name="content_extra_info_{$info['id_product_extra_info']|escape:'htmlall':'UTF-8'}" class="form-control" rows="3">{$info['content']|escape:'htmlall':'UTF-8'}</textarea>
        </div>
        <button type="submit" value="{$info['id_product_extra_info']|escape:'htmlall':'UTF-8'}" name="submitUpdateInfo" class="btn btn-info">Update</button>
        <button type="submit" value="{$info['id_product_extra_info']|escape:'htmlall':'UTF-8'}" name="submitDeleteInfo" class="btn btn-danger">Remove</button>
    </div>
    {/foreach}
</div>



