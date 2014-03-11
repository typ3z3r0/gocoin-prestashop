{if $paypal_usa_ps_14}
<script type="text/javascript">
		{literal}
		$(document).ready(function() {
			var scripts = [{/literal}{$paypal_usa_js_files}{literal}];
			for(var i = 0; i < scripts.length; i++) {
				$.getScript(scripts[i], function() {paypal_usa_init()});
			}
		});
		{/literal}
</script>
{/if} 
<div class="paypal_usa-module-wrapper">
	 	{if $gocoin_validation}
		<div class="conf">
			{foreach from=$gocoin_validation item=validation}
				{$validation|escape:'htmlall':'UTF-8'}<br />
			{/foreach}
		</div>
	{/if}
	{if $gocoin_error}
		<div class="error">
			{foreach from=$gocoin_error item=error}
				{$error|escape:'htmlall':'UTF-8'}<br />
			{/foreach}
		</div>
	{/if}
	{if $gocoin_warning}
		<div class="info">
			{foreach from=$gocoin_warning item=warning}
				{$warning|escape:'htmlall':'UTF-8'}<br />
			{/foreach}
		</div>
	{/if}
	{if isset($gocoin_advanced_only_us) && $gocoin_advanced_only_us}
		<div class="warn">{l s='You enabled PayPal Payments Advanced however this product only works in the USA' mod='paypalusa'}</div>
	{/if}
	<form action="{$paypal_usa_form_link|escape:'htmlall':'UTF-8'}" method="post" id="paypal_usa_paypal_api_settings" class="half-form L">
		<fieldset>
			<legend><img src="{$module_dir}img/settings.gif" alt="" /><span>{l s='GoCoin API Settings' mod='gocoin'}</span></legend>
			<div id="paypal-usa-basic-settings-table">
				<label for="gocoin_merchant_id">{l s=' Merchant ID :' mod='gocoin'}</label></td>
				<div class="margin-form">
					<input type="text" name="gocoin_merchant_id" id="gocoin_merchant_id" class="input-text" value="{if $gocoin_configuration.GOCOIN_MERCHANT_ID}{$gocoin_configuration.GOCOIN_MERCHANT_ID|escape:'htmlall':'UTF-8'}{/if}" /> <sup>*</sup>
				</div>
				<label for="gocoin_access_key">{l s='Access Key:' mod='gocoin'}</label></td>
				<div class="margin-form">
					<input type="text" name="gocoin_access_key" id="gocoin_access_key" class="input-text" value="{if $gocoin_configuration.GOCOIN_ACCESS_KEY}{$gocoin_configuration.GOCOIN_ACCESS_KEY|escape:'htmlall':'UTF-8'}{/if}" /> <sup>*</sup>
				</div>
        
				<label for="gocoin_token">{l s='Token:' mod='gocoin'}</label></td>
				<div class="margin-form">
					<input type="text" name="gocoin_token" class="input-text" value="{if $gocoin_configuration.GOCOIN_TOKEN}{$gocoin_configuration.GOCOIN_TOKEN|escape:'htmlall':'UTF-8'}{/if}" /> <sup>*</sup>
				</div>
				<!--label for="gocoin_pay_type">{l s='Payment Type:' mod='gocoin'}</label></td>
				<div class="margin-form">
					<input type="text" name="gocoin_pay_type" class="input-text" value="{if $gocoin_configuration.GOCOIN_PAY_TYPE}{$gocoin_configuration.GOCOIN_PAY_TYPE|escape:'htmlall':'UTF-8'}{/if}" /> <sup>*</sup>
				</div-->
            <script type="text/javascript">
                function get_api_token() {
                        var client_id = document.getElementById('gocoin_merchant_id').value;
                        var client_secret = document.getElementById('gocoin_access_key').value;
                        if (!client_id) {
                            alert('Please input Merchant ID!');
                            return;
                        }
                        if (!client_secret) {
                            alert('Please input Access Key!');
                            return;
                        }
                        var currentUrl = window.location.origin + '/prestashop/index.php?fc=module&module=gocoin&controller=callback';
                        
                        var url = "https://dashboard.gocoin.com/auth?response_type=code"
                                    + "&client_id=" + client_id
                                    + "&scope=user_read+merchant_read+invoice_read_write"
                                    + "&redirect_uri=" + encodeURIComponent(currentUrl);
                        var strWindowFeatures = "location=yes,height=570,width=520,scrollbars=yes,status=yes";
                        var win = window.open(url, "_blank", strWindowFeatures);
                        return;
                    }
            </script>
        <div style="margin-top:5px;"> 
            <span class="notice">you can click button to get access token from gocoin.com</span>
            <button id="btn_get_token" title="Get API Token" class="scalable " onclick="get_api_token(); return false;" style="">
                <span><span><span>Get API Token</span></span></span>
            </button>
        </div>
			</div>
			
			<div class="margin-form">
				<input type="submit" name="SubmitBasicSettings" class="button" value="{l s='Save settings' mod='gocoin'}" />
			</div>
			<span class="small"><sup style="color: red;">*</sup> {l s='Required fields' mod='gocoin'}</span>
		</fieldset>
	</form>
	
	
</div>
{if $paypal_usa_merchant_country_is_mx}
	<script type="text/javascript">
		{literal}
		$(document).ready(function() {
			$('#content table.table tbody tr th span').html('paypalmx');
		});
		{/literal}
	</script>
{/if}