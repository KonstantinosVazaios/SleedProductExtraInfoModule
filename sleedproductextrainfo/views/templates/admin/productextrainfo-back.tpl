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
        <i class="icon-sitemap"></i> Add Product Extra Information
    </div>
    <div class="form-wrapper">
        <input type="hidden" name="submitted_tabs[]" value="sleedproductextrainfo">
        <div class="form-group">
            <label class="control-label col-lg-3 required" for="title_{$id_lang|escape:'htmlall':'UTF-8'}">
                <span class="label-tooltip" data-toggle="tooltip"
                title="Required Field">
                Information Title
                </span>
            </label>
            <div class="col-lg-9">
                {include file="controllers/products/input_text_lang.tpl"
                    languages=$languages
                    input_value=""
                    input_name="title"
                }
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 required" for="content_{$id_lang|escape:'htmlall':'UTF-8'}">
                <span class="label-tooltip" data-toggle="tooltip"
                title="Required Field">
                Information Content
                </span>
            </label>
            <div class="col-lg-9">
                {include file="controllers/products/input_text_lang.tpl"
                    languages=$languages
                    input_value=""
                    input_name="content"
                }
            </div>
        </div>
        <button type="submit" name="submitAddproductAndStay" id="add-row" class="btn btn-success" style="width: 100%">ADD</button>
    </div>
</div>
{if $extraInfo}
<div class="panel">
    <div class="panel-heading">
        <i class="icon-sitemap"></i> Update Product Extra Information
    </div>
{/if}
    {foreach from=$extraInfo key=extra_info_id item=info}
    <div class="tab-container">
        <hr>
        <div class="form-group">
            <label class="control-label col-lg-3 required" for="title_{$extra_info_id|escape:'htmlall':'UTF-8'}_{$id_lang|escape:'htmlall':'UTF-8'}">
                <span class="label-tooltip" data-toggle="tooltip"
                title="Required Field">
                Information Title
                </span>
            </label>
            <div class="col-lg-9">
                {include file="controllers/products/input_text_lang.tpl"
                    languages=$languages
                    input_value=$info['title']
                    input_name="title_{$extra_info_id|escape:'htmlall':'UTF-8'}"
                }
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 required" for="content_{$extra_info_id|escape:'htmlall':'UTF-8'}_{$id_lang|escape:'htmlall':'UTF-8'}">
                <span class="label-tooltip" data-toggle="tooltip"
                title="Required Field">
                Information Content
                </span>
            </label>
            <div class="col-lg-9">
                {include file="controllers/products/input_text_lang.tpl"
                    languages=$languages
                    input_value=$info['content']
                    input_name="content_{$extra_info_id|escape:'htmlall':'UTF-8'}"
                }
            </div>
            <button type="submit" value="{$extra_info_id|escape:'htmlall':'UTF-8'|escape:'htmlall':'UTF-8'}" name="submitDeleteInfo" class="btn btn-danger pull-right">Delete</button>
        </div>
    </div>
    {/foreach}
    {if $extraInfo}
    <button type="submit" name="submitUpdateInfo" class="btn btn-info" style="width:100%; margin-top: 20px">Update</button>
</div>
{/if}



