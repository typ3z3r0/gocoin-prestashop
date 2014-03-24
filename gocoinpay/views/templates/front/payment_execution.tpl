{**}

{capture name=path}{l s='Gocoin payment.' mod='gocoin'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
 <style type="text/css">
#module-gocoin-payment #left_column {ldelim} display:none {rdelim}
#module-gocoin-payment #center_column {ldelim} width:757px {rdelim}
</style>
<h2>{l s='Order summary' mod='gocoin'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='gocoin'}</p>
{else}

<h3>{l s='Gocoin payment.' mod='gocoin'}</h3>

<p>
	<img src="{$this_path_bw}logo.png" alt="{l s='Gocoin' mod='gocoin'}" width="86" height="49" style="float:left; margin: 0px 10px 5px 0px;" />
	{l s='You have chosen to pay by Gocoin Payment Gateway.' mod='gocoin'}
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
