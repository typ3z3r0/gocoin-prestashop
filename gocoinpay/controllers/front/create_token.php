<?php
 include_once _PS_CLASS_DIR_.'gocoinlib/src/GoCoin.php';  
class GocoinpayCreate_tokenModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
  
	/**
	 * @see FrontController::initContent()
	 */
  
	public function initContent()
  {
       if(isset($_REQUEST['code'])){
        $code = $_REQUEST['code'];
       }
       else{
        $code = ''    ;
       }
        $client_id = Configuration::get('GOCOIN_MERCHANT_ID');;
        $client_secret = Configuration::get('GOCOIN_ACCESS_KEY');

        try {
            $token = GoCoin::requestAccessToken($client_id, $client_secret, $code, null);
            echo "<b>Copy this Access Token into your GoCoin Module: </b><br>" . $client->getToken();
        } catch (Exception $e) {
            echo "Problem in getting Token: " . $e->getMessage();
        }
        
  }
}
