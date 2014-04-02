<?php

 include_once _PS_CLASS_DIR_.'gocoinlib/src/GoCoin.php';  

class GocoinpayPaymentModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
  
	/**
	 * @see FrontController::initContent()
	 */
  
	public function initContent()
	{
           
      
		$this->display_column_left = false;
		parent::initContent();
    $gocoin = new Gocoinpay();
     
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
        $data['email']      =  $billing_customer->email;
           
        if(isset($billing_address->phone_mobile) && !empty($billing_address->phone_mobile))
        {
            $data['day_phone_b']=   $billing_address->phone_mobile;
        }
        elseif(isset($billing_address->phone) && !empty($billing_address->phone))
        {
            $data['day_phone_b']=   $billing_address->phone;
        }    
			
       $paytype=isset($_POST['paytype'])&&!empty($_POST['paytype'])?$_POST['paytype']:'';
       $price_currency = $paytype;
       if($paytype==''){
           Tools::redirect('index.php?controller=order');
       }
       
       $total=(float)$this->context->cart->getOrderTotal(true);
       
   
       
       $arr =  array(
                'client_id' => $merchant_id,
                'client_secret' =>  $gocoin_access_key,
                'scope' => "user_read_write+merchant_read_write+invoice_read_write",);

       
      
      
      $url=array();
      $url['cancel_url'] = $this->context->link->getPageLink('order.php','');
      $url['callback_url'] = $this->context->link->getModuleLink('gocoinpay', 'validation', array('pps' => 1), false);
			$url['redirect_url'] =				((int)version_compare(_PS_VERSION_, '1.4', '>')) ?
					(Configuration::get('PS_SSL_ENABLED') ? Tools::getShopDomainSsl(true) : Tools::getShopDomain(true)).
					__PS_BASE_URI__.'order-confirmation.php?id_cart='.(int)$this->context->cart->id.'&id_module='.(int)$this->module->id.'&key='.$this->context->customer->secure_key : 
					$this->context->link->getPageLink('order-confirmation.php', null, null, array('id_cart' => (int)$this->context->cart->id, 'key' => $this->context->customer->secure_key, 'id_module' => $this->module->id));
  		
      $options = array();
      $options = array(
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
      $options['order_id']  =   $cart->id;
       
      //$data_string = json_encode($my_array);
      
        try {
            
                $user = GoCoin::getUser($gocoin_token);
                
                if ($user) {
                    $merchant_id = $user->merchant_id;
           
                    if (!empty($merchant_id)) {
                        $invoice = GoCoin::createInvoice($gocoin_token, $merchant_id, $options);
                        
                        if (isset($invoice->errors)) {
                            $result = 'error';
                            $messages = 'GoCoin does not permit';
                        } elseif (isset($invoice->error)) {
                            $result = 'error';
                            $messages = $invoice->error;
                        } elseif (isset($invoice->merchant_id) && $invoice->merchant_id != '' && isset($invoice->id) && $invoice->id != '') {
                            $url = $gocoin_url . $invoice->merchant_id . "/invoices/" . $invoice->id;
                            $result = 'success';
                            $messages = 'success';
                            $redirect = $url;
                        }
                    }
                } else {
                    $result = 'error';
                    $messages = 'GoCoin Invalid Settings';
                }
            } catch (Exception $e) {
                $result = 'error';
               $messages = $invoice->error;
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
    