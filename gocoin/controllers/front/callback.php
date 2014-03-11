<?php

class GocoinCallbackModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
  
	/**
	 * @see FrontController::initContent()
	 */
  
	public function initContent()
  {
      $merchant_id        =   Configuration::get('GOCOIN_MERCHANT_ID');
    	$gocoin_access_key  =   Configuration::get('GOCOIN_ACCESS_KEY');
   
    	$arr =  array(
                'client_id' => $merchant_id,
                'client_secret' =>  $gocoin_access_key,
                'scope' => "user_read_write+merchant_read_write+invoice_read_write",);

      include _PS_CLASS_DIR_.'/gocoinlib/src/client.php';    
      $client = new Client($arr);
      $b_auth = $client->authorize_api();
      $result = array();
      if ($b_auth) {
          $result['success'] = true;
          $result['data'] = $client->getToken();
          echo "Copy this Access Token into your GoCoin Module: ".$client->getToken();
      } else {
          $result['success'] = false;
          echo "Error in getting Token: ". $client->getError();
      } 
      die();
  }
}
