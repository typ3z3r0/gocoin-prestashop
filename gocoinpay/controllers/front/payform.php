<?php
class GocoinpayPayformModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
  
	/**
	 * @see FrontController::initContent()
	 */
  
	public function initContent()
	{
      $this->gocoin = new Gocoinpay();
      $_array   = isset($_POST['jData']) && !empty($_POST['jData'])?unserialize($_POST['jData']):'';
      $_url     = isset($_POST['gurl'])  && !empty($_POST['gurl'])?$_POST['gurl']:'';
      if(!empty($_url)){
          $this->gocoin->addTransaction($type = 'payment', $_array);    
        Tools::redirect($_url);
         exit;
      }
     else 
     {
           Tools::redirect('index.php?controller=order');
         exit;
      }
	}
  
}
    