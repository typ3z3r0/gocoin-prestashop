{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}{l s='Gocoin payment.' mod='gocoin'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Order summary' mod='gocoin'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='gocoin'}</p>
{else}

<h3>{l s='Gocoin payment.' mod='gocoin'}</h3>

<p>
	<img src="{$this_path_bw}logo.png" alt="{l s='Gocoin' mod='gocoin'}" width="86" height="49" style="float:left; margin: 0px 10px 5px 0px;" />
	{l s='You have chosen to pay by bank wire.' mod='gocoin'}
	<br/><br />
	{l s='Here is a short summary of your order:' mod='gocoin'}
</p>
<p style="margin-top:20px;">
	- {l s='The total amount of your order is' mod='gocoin'}
	<span id="amount" class="price">{displayPrice price=$total}</span>
	{if $use_taxes == 1}
    	{l s='(tax incl.)' mod='gocoin'}
    {/if}
</p>

{if $_result eq 'error'}
    
    <p style="padding-left:20px;padding-top:20px; margin-top:20px;background:#CC0000;border: 1px solid #900 ;color: #ffffff; ">
        - <span >{$_messages}</span>
   </p>
    
{/if}
<br>
<table width='100%'>
        <tr>
            <td valign="top" class="cart_navigation" id="cart_navigation">
                
                    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button_large">{l s='Other payment methods' mod='gocoin'}</a>
                
            </td>
            <td valign="top">
                {if $_result eq 'success'}
                    {if $_result != ''}
                        <a href="{$_redirect}" >  
                          <span class="exclusive_large">{l s='Place my order' mod='gocoin'}  </span>
                        </a>
                    {/if}    
                {/if}
            </td>
        </tr>    
</table>
{/if}
