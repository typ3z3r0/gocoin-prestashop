<?php


class GocoinPaymentModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
  
	/**
	 * @see FrontController::initContent()
	 */
  
	public function initContent()
	{
    
		$this->display_column_left = false;
		parent::initContent();
    $gocoin = new Gocoin();
    
		$cart = $this->context->cart;
		if (!$this->module->checkCurrency($cart))
			Tools::redirect('index.php?controller=order');

    
    $merchant_id        =   Configuration::get('GOCOIN_MERCHANT_ID');
    $gocoin_access_key  =   Configuration::get('GOCOIN_ACCESS_KEY');
    $gocoin_token       =   Configuration::get('GOCOIN_TOKEN');
    $gocoin_url         =   Configuration::get('GOCOIN_URL');
    
    if(!$merchant_id || !$gocoin_access_key || !$gocoin_token){
        Tools::redirect('index.php?controller=order');
    }
    
    $currency = new Currency((int)$this->context->cart->id_currency);
    $cart   =   $this->context->cart;
    $billing_customer =  $this->context->customer;
        
    $billing_address = new Address((int)$this->context->cart->id_address_invoice);
		$billing_address->country = new Country((int)$billing_address->id_country);
		$billing_address->state = new State((int)$billing_address->id_state);
    
        $data=array();
        $data['currency_code']=urlencode($currency->iso_code); 
        $data['name']       =   $billing_customer->firstname." ".$billing_customer->lastname;
        $data['address1']   =   $billing_address->address1;
        $data['address2']   =   $billing_address->address2;
        $data['city']       =   $billing_address->city;
        $data['state']      =   $billing_address->state->name;
        $data['zip']        =   $billing_address->postcode;
        $data['country']    =   $billing_address->country->iso_code;
        if(isset($billing_address->phone_mobile) && !empty($billing_address->phone_mobile))
        {
            $data['day_phone_b']=   $billing_address->phone_mobile;
        }
        elseif(isset($billing_address->phone) && !empty($billing_address->phone))
        {
            $data['day_phone_b']=   $billing_address->phone;
        }    
			
       $paytype=isset($_POST['paytype'])&&!empty($_POST['paytype'])?$_POST['paytype']:'';
       switch ($paytype) {
           case 'Bitcoin':
                 $price_currency='BTC';
                 break;
           
           case 'Litcoin':
                 $price_currency='LTC';
                 break;  
        }
       
       if($paytype==''){
           Tools::redirect('index.php?controller=order');
       }
       $total=(float)$this->context->cart->getOrderTotal(true);
       
   
       
       $arr =  array(
                'client_id' => $merchant_id,
                'client_secret' =>  $gocoin_access_key,
                'scope' => "user_read_write+merchant_read_write+invoice_read_write",);

      include _PS_CLASS_DIR_.'/gocoinlib/src/client.php';    
      $client = new Client($arr);
      $client->setToken($gocoin_token);
      if (!$client) {
            $result = 'error';
            $messages 	= $client->getError();
      }  
      
      
      $url=array();
      $url['cancel_url'] = $this->context->link->getPageLink('order.php','');
      $url['callback_url'] = $this->context->link->getModuleLink('gocoin', 'validation', array('pps' => 1), false);
			$url['redirect_url'] =				((int)version_compare(_PS_VERSION_, '1.4', '>')) ?
					(Configuration::get('PS_SSL_ENABLED') ? Tools::getShopDomainSsl(true) : Tools::getShopDomain(true)).
					__PS_BASE_URI__.'order-confirmation.php?id_cart='.(int)$this->context->cart->id.'&id_module='.(int)$this->module->id.'&key='.$this->context->customer->secure_key : 
					$this->context->link->getPageLink('order-confirmation.php', null, null, array('id_cart' => (int)$this->context->cart->id, 'key' => $this->context->customer->secure_key, 'id_module' => $this->module->id));
  		var_dump($url);
      $my_array = array();
      $my_array = array(
                    'price_currency'       => $price_currency,   
                    'base_price'           =>  (float)$this->context->cart->getOrderTotal(true),
                    'base_price_currency'  =>  $data['currency_code'] ,
                    'notification_level'    => 'all' ,
                    'callback_url'         =>  $url['callback_url'],
                    'redirect_url'         =>  $url['redirect_url'],
                    'customer_name'        =>  $data['name'],
                    'customer_address_1'   =>  $data['address1'],
                    'customer_address_2'   =>  $data['address2'],
                    'customer_city'        =>  $data['city'],
                    'customer_region'      =>  $data['state'],
                    'customer_postal_code' =>  $data['zip'],
                    'customer_country'     =>  $data['country'],
                    'customer_phone'       =>  $data['day_phone_b'],
                    'customer_email'       =>  $data['email'], 
                    'user_defined_1'       =>  (int)$currency->id,
                    'user_defined_2'       =>  $billing_customer->secure_key,
               );
      $my_array['order_id']  =   $cart->id;
           
      var_dump($my_array);
      $data_string = json_encode($my_array);
      $user = $client->api->user->self();
      if (!$user) {
                $result = 'error';
                $messages 	= $client->getError();
      }
      
      $invoice_params = array(
            'id' => $user->merchant_id,
            'data' => $data_string
        );
     
      if (!$invoice_params) {
                $result = 'error';
                $messages 	= $client->getError();
      }
      $invoice = $client->api->invoices->create($invoice_params);
      if(isset($invoice->errors)) {
                $result = 'error';
                $messages 	= 'GoCoin does not permit' ;
      }
      elseif(isset($invoice->error)) {
                $result = 'error';
                $messages 	=  $invoice->error;
      } 
      elseif(isset($invoice->merchant_id) && $invoice->merchant_id!='' && isset($invoice->id) && $invoice->id!=''){
         $url = $gocoin_url.$invoice->merchant_id."/invoices/".$invoice->id;
         $result 	= 'success';
         $messages 	= 'success';
         $redirect	= $url;
        
      }

		$this->context->smarty->assign(array(
      '_result'             => $result  ,
      '_messages'           => $messages  ,
      '_redirect'           => $redirect  ,
			'nbProducts'          => $cart->nbProducts(),
			'cust_currency'       => $cart->id_currency,
			'currencies'          => $this->module->getCurrency((int)$cart->id_currency),
			'total'               => $cart->getOrderTotal(true, Cart::BOTH),
			'this_path'           => $this->module->getPathUri(),
			'this_path_bw'        => $this->module->getPathUri(),
			'this_path_ssl'       => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
		));

		$this->setTemplate('payment_execution.tpl');
	}
}
