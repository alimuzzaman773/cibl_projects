<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
            
            $json = '{"jsmods":{"require":[["LeftNavScrollable"]]},"bootloadable":{"TimeSliceInteractionsLiteTypedLogger":{"resources":["+49dT","XzLIn","ZN6iu"],"needsAsync":1,"module":1},"WebSpeedInteractionsTypedLogger":{"resources":["+49dT","XzLIn","4F+rW"],"needsAsync":1,"module":1},"ErrorSignal":{"resources":["+49dT","XzLIn","RqHnY","pyoVY"],"needsAsync":1,"module":1},"UITinyViewportAction":{"resources":["+49dT","dCoZQ","XzLIn","H0OoB"],"needsAsync":1,"module":1},"Banzai":{"resources":["+49dT","XzLIn"],"needsAsync":1,"module":1},"BanzaiODS":{"resources":["+49dT","XzLIn"],"needsAsync":1,"module":1},"ResourceTimingBootloaderHelper":{"resources":["+49dT","RqHnY"],"needsAsync":1,"module":1},"TimeSliceHelper":{"resources":["+49dT","z2vIx","XzLIn"],"needsAsync":1,"module":1},"BanzaiStream":{"resources":["ZU1ro","+49dT","XzLIn"],"needsAsync":1,"module":1},"LeftNavScrollable":{"resources":["CvksN","+49dT","dCoZQ","XzLIn","DIf6n","H0OoB"],"needsAsync":1,"module":1},"SnappyCompressUtil":{"resources":["+49dT"],"needsAsync":1,"module":1}},"resource_map":{"+49dT":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3i76C4/yw/l/en_US/cCUbAKA3bs1.js","crossOrigin":1},"XzLIn":{"type":"css","src":"https://static.xx.fbcdn.net/rsrc.php/v3/yi/l/0,cross/iRe-htlK4kA.css","permanent":1,"crossOrigin":1},"ZN6iu":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3/yB/r/8b2MDwICR8Z.js","crossOrigin":1},"4F+rW":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3/yg/r/g0ZRJiRCTmN.js","crossOrigin":1},"RqHnY":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3i3pY4/yi/l/en_US/Q5wY7rYDRLY.js","crossOrigin":1},"pyoVY":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3/yS/r/wTM5pPVQSKw.js","crossOrigin":1},"dCoZQ":{"type":"css","src":"https://static.xx.fbcdn.net/rsrc.php/v3/yq/l/0,cross/fCuBynhlW8w.css","permanent":1,"crossOrigin":1},"H0OoB":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3iwg34/yK/l/en_US/kt8hYCjhAOI.js","crossOrigin":1},"z2vIx":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3/yG/r/diUCTEnNvkF.js","crossOrigin":1},"ZU1ro":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3/yP/r/elg8eNarjVy.js","crossOrigin":1},"CvksN":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3/ym/r/IwrxXh6XKbR.js","crossOrigin":1},"DIf6n":{"type":"js","src":"https://static.xx.fbcdn.net/rsrc.php/v3/yx/r/DejSBbsc5tx.js","crossOrigin":1}},"gkxData":{"AT4kYIk7PhRqUACJJM8qs58t-WNCoM2ZYe35b1xv03xf3OtmC7RfXVIT9hWB6yTOgfA":{"result":true,"hash":"AT4Y-ZeTLWAxcUnX"},"AT6Afdq0Tt2jEesGOMGnSRKoZIl2eQfQBS7ISXiYFG3RHN4ykkPiZeyWuKALtD0ObEVGeeZuAFKdYpfxlBzUUPkd":{"result":false,"hash":"AT5vVo3XeKaPyCg1"},"AT7IsskI4XB9V3_ZpKFnRxAvs6BVPIgSDbDcq24b8ToUAOY2pCaSzuagN7f_cNx9vGp7vgNftn1_SRfogFUNGS0K":{"result":true,"hash":"AT6zOfQTcmuT_gYT"},"AT68bJwSI-83elN-7JSMMH9zt32KbiF6pW-XMlf6NViAJ3CbAk_16Vq8cK1tl1029_ApvFwINR8hmoci3nMKFTDhDCBp1wrvYQbOKq0pCjZpqA":{"result":false,"hash":"AT5j0BW0MSZlwFQv"},"AT6DanO60hgFT7juQEF_b5acv5amdrLzodvaFbz5tWF8DGQCmmf0_a7wsRZnn4yNp9kI3S6KXc87dzKSPpUSy11k":{"result":false,"hash":"AT5nDPF8cd2qNjX5"}},"allResources":["CvksN","+49dT","dCoZQ","XzLIn","DIf6n","H0OoB"],"displayResources":["dCoZQ","XzLIn"]}';
            
            //$this->load->model('login_model');
            //$this->load->model('common_model');
            //$this->load->helper('url');
            $this->load->library("crypt");
            $this->load->helper("string");
            //var_dump(openssl_cipher_iv_length('BF-CBC'));
            $iv = random_string("alnum",8); //openssl_random_pseudo_bytes(openssl_cipher_iv_length('BF-CBC'));
            
            $i = 10;
            $array = array();
            while($i > 0){
                $array[$i] = array(
                    random_string("alnum",8) => random_string("alnum",16)
                );
                
                $i--;
            }
            echo "<pre>";
            var_dump($array);
            
            echo "<br >";
            echo "<br >";
            $data = json_encode($array);
            
            echo $iv."<br />";
            $encryptedData = $this->crypt->encrypt_openssl("EblCibl123456789", $data, $iv);
            echo $encryptedData."<br />";
            $decryptedData = $this->crypt->decrypt_openssl("EblCibl123456789", $encryptedData, $iv);
            echo $decryptedData;
            echo "<br >";
            echo "<br >";
            var_dump(json_decode($decryptedData),true);
            
	//	$this->load->view('welcome_message');
	}
}
