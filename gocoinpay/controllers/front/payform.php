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
      $_url     = isset($_POST['gurl'])  && !empty($_POST['gurl'])?$_POST['gurl']:'';
      if(!empty($_url)){
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
    