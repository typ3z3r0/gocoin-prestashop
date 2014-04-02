{*
*}
 
	<form method='POST' action="{$link->getModuleLink('gocoinpay', 'payment')|escape:'html'}" title="{l s='Pay by gocoin' mod='gocoin'}">
      <p class="payment_module">
            <img src="{$this_path_bw}logo.png" alt="{l s='Pay by gocoin' mod='gocoin'}" width="86" height="49"/>&nbsp;
            <select name="paytype">
                <option value="BTC">Bitcoin</option>
                <option value="XDG">Dogecoin</option>
                <option value="LTC">LiteCoin</option>
            </select>
            <input type='submit' name='cmd' value="Go">
     </p>
  </form>
 
    