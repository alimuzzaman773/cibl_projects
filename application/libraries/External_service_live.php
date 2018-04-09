<?php

class External_service
{
	public function externalService($billerCode, $data)
	{
		switch($billerCode)
		{
			case "BC0021":
			case "BC0022":

				$topUpWebService = 'http://192.168.5.77/EasyAirtime/webservices/AirtimeManager.asmx?WSDL';
				$xml = "<?xml version='1.0' encoding='UTF-8'?>
	                    <RECHARGE_ENV xmlns='http://192.168.5.77/EasyAirtime/webservices/AirtimeManager/RechargeAirtime'>
	                    <RECHARGE_DETAILS>
	                            <USERID>EBLSKY</USERID>
	                            <PASSWORD>123</PASSWORD>
	                            <BILLID>".$data['payBillLogId']."</BILLID>
	                            <MESSAGE>Test Message</MESSAGE>
	                            <BILLDATE>".date("d/m/Y h:i:s A")."</BILLDATE>
	                            <BILLNO>".$data['narration']."</BILLNO>
	                            <BILLAMT>".$data['amount']."</BILLAMT>
	                            <INSTID>".$billerCode."</INSTID>
	                    </RECHARGE_DETAILS>
	                    </RECHARGE_ENV>";
	            $param = array('RechargeParam' => $xml);
	            $client = new SoapClient($topUpWebService, array("trace" => 1, "exception" => 0));
				$result = $client->RechargeAirtime($param)->RechargeAirtimeResult;
			return $result;
			break;
		}
	}	
}

?>