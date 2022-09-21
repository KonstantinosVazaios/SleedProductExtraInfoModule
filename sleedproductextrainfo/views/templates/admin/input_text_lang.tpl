{*
* 2007-2022 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2022 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{foreach from=$languages item=language}
	{if $languages|count > 1}
	<div class="translatable-field row lang-{$language.id_lang|escape:'html':'UTF-8'}">
		<div class="col-lg-9">
			<input type="text"
			id="{$input_name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}"
			{if isset($input_class)}class="{$input_class|escape:'html':'UTF-8'}"{/if}
			name="{$input_name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}"
			value="{$input_value[$language.id_lang|escape:'html':'UTF-8']|htmlentitiesUTF8|default:''}" />
		</div>
		<div class="col-lg-2">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
				{$language.iso_code|escape:'html':'UTF-8'}
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				{foreach from=$languages item=language}
				<li>
					<a href="javascript:hideOtherLanguage({$language.id_lang|escape:'html':'UTF-8'});">{$language.name|escape:'html':'UTF-8'}</a>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>
	{/if}
{/foreach}
