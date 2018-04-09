<?php

class push_to_cbs_service_library
{
	public function pushToCbsService($req_type_code, $data)
	{
		$xml = "";
		$apprequest = "";
		$url = "http://192.168.3.159/webserver/coreEngin.php";


		switch($req_type_code)
		{
			case "01":    // Account Details

				$apprequest = "vw_account_detail";
				$xml = "<QUERY_ACC_DETAIL>						
						<CUSTOMERID>".$data['customerId']."</CUSTOMERID>
						<CUSTACC>".$data['accountNumber']."</CUSTACC>
						</QUERY_ACC_DETAIL>";

				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;


			case "02":   // ACCOUNT SUMMARY

				$apprequest = "vw_account_summary";
				$xml = "<QUERY_ACC_SUMMARY>						
						<CUSTOMERID>".$data['customerId']."</CUSTOMERID>
						<CUSTOMERACCNO>".$data['accountNumber']."</CUSTOMERACCNO>
						</QUERY_ACC_SUMMARY>";
				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;



			case "03":   // TRN HISTORY 

				$apprequest = "vw_trn_history";
				$xml = "<QUERY_ACC_TRN>						
						<CUSTOMERID>".$data['customerId']."</CUSTOMERID>
						<CUSTACC>".$data['accountNumber']."</CUSTACC>
						</QUERY_ACC_TRN>";
				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;



			case "04":  // TD INFO 

				$apprequest = "vw_td_info";
				$xml = "<QUERY_TD>						
						<IDCORPORATE>".$data['customerId']."</IDCORPORATE>
						<TDACCNO>".$data['accountNumber']."</TDACCNO>
						</QUERY_TD>";
				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;

	
				
			case "05":  // LOAN INFO 

				$apprequest = "vw_cl_account";
				$xml = "<QUERY_CL>						
						<CUSTOMERID>".$data['customerId']."</CUSTOMERID>
						<LOANACCNO>".$data['accountNumber']."</LOANACCNO>
						</QUERY_CL>";
				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;	
			break;



			case "06":  // OWN ACCOUNT TRANSFER

				$apprequest = "req_own_account_fund_transfer";				
				$xml = "<REQ_FUND_TRANSFER>
						<CUSTOMERID>".$data['customerId']."</CUSTOMERID>
						<FROM_ACCOUNT>".$data['fromAccount']."</FROM_ACCOUNT>
						<TO_ACCOUNT>".$data['toAccount']."</TO_ACCOUNT>
						<AMOUNT>".$data['amount']."</AMOUNT>
						<DATE>".$data['date']."</DATE>
						<CROSS_REF_NO>".$data['crossRefNo']."</CROSS_REF_NO>
						<NARRATION>".$data['narration']."</NARRATION>
						</REQ_FUND_TRANSFER>"; // date format yyyy-mm-dd

				 $result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;




			case "07":  // EBL ACCOUNT TRANSFER

				$apprequest = "req_ebl_account_transfer";
				$xml = "<REQ_FUND_TRANSFER>
						<USER_ID>".$data['userId']."</USER_ID>
						<CUSTOMERID>".$data['customerId']."</CUSTOMERID>
						<FROM_ACCOUNT>".$data['fromAccount']."</FROM_ACCOUNT>
						<TO_ACCOUNT>".$data['toAccount']."</TO_ACCOUNT>
						<AMOUNT>".$data['amount']."</AMOUNT>
						<DATE>".$data['date']."</DATE>
						<OTP_CODE>".$data['otpCode']."</OTP_CODE>
						<CROSS_REF_NO>".$data['crossRefNo']."</CROSS_REF_NO>
						<NARRATION>".$data['narration']."</NARRATION>
						</REQ_FUND_TRANSFER>";  // date format yyyy-mm-dd

				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;



			case "08":  // OTHER BANK TRANSFER

				$apprequest = "req_other_bank_transfer";
				$xml = "<REQ_FUND_TRANSFER>
						<USER_ID>".$data['userId']."</USER_ID>
						<CUSTOMERID>".$data['customerId']."</CUSTOMERID>
						<FROM_ACCOUNT>".$data['fromAccount']."</FROM_ACCOUNT>
						<RCVR_NAME>".$data['rcvrName']."</RCVR_NAME>
						<TO_ACCOUNT>".$data['toAccount']."</TO_ACCOUNT>
						<RCVR_BNK_NAME>".$data['rcvrBankName']."</RCVR_BNK_NAME>
						<RCVR_DIST_NAME>".$data['rcvrDistName']."</RCVR_DIST_NAME>
						<RCVR_BRN_NAME>".$data['rcvrBrnName']."</RCVR_BRN_NAME>
						<RCVR_RT_NUMBER>".$data['rcvrRtNumber']."</RCVR_RT_NUMBER>
						<AMOUNT>".$data['amount']."</AMOUNT>
						<DATE>".$data['date']."</DATE>
						<OTP_CODE>".$data['otpCode']."</OTP_CODE>
						<CROSS_REF_NO>".$data['crossRefNo']."</CROSS_REF_NO>
						<NARRATION>".$data['narration']."</NARRATION>
						</REQ_FUND_TRANSFER>";  // date format yyyy-mm-dd

				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;



			case "09": // BILLS PAY

				$apprequest = "req_bills_pay";						
				$xml = "<REQ_FUND_TRANSFER>
						<CUSTOMERID>".$data['customerId']."</CUSTOMERID>
						<FROM_ACCOUNT>".$data['fromAccount']."</FROM_ACCOUNT>
						<BILLER_NAME>".$data['billerName']."</BILLER_NAME>
						<AMOUNT>".$data['amount']."</AMOUNT>
						<DATE>".$data['date']."</DATE>
						<NARRATION>".$data['narration']."</NARRATION>
						</REQ_FUND_TRANSFER>";

				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;





			case "10": // CARD DOCS

				$apprequest = "vw_card_information";
				$loginName = "EBLSKYADM";
				$passWord = "ESBADM123";

				$xml = "<REQ_CARD_INFO>
						<LOGIN>". $loginName ."</LOGIN>
						<PASSWORD>". $passWord ."</PASSWORD>
						<METHODNAME>". $data['methodName'] ."</METHODNAME>
						<CLIENTID>". $data['clientId'] ."</CLIENTID>
						<CARD_NO>". $data['cardNumber'] ."</CARD_NO>
						<CARD_CURRENCY>". $data['cardCurrency'] ."</CARD_CURRENCY>
						</REQ_CARD_INFO>";
				$result = $this->pushtoservice($url, $apprequest, $xml);
				return $result;
			break;
		}
	}


	private function pushtoservice($url, $appreq, $xml)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $appreq.'='.$xml);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$server_output =  curl_exec($ch);
		curl_close($ch);
		$response = str_replace(array("&"), 'AND', $server_output);
		return $response;


	}
}
