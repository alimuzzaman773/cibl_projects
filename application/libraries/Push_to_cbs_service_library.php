<?php

class Push_to_cbs_service_library {

    public function pushToCbsService($req_type_code, $data) {


        $url = "";
        $xml = "";
        $apprequest = "";
        $url = "http://192.168.3.159/webserver/coreEngin.php";


        switch ($req_type_code) {


            case "01":    // ACCOUNT DETAIL

                $apprequest = "vw_account_detail";

                $xml = "<QUERY_ACC_DETAIL>						
						<CUSTOMERID>" . $data['customerId'] . "</CUSTOMERID>
						<CUSTACC>" . $data['accountNumber'] . "</CUSTACC>
						</QUERY_ACC_DETAIL>";

                $result = "<QRYRESULT>
			 			<ISSUCCESS>Y</ISSUCCESS>

			 			<EBLCUSTOMER ACCOUNTNO='11111'>
						<CUSTACCTNO>121212</CUSTACCTNO>
						<ACCTSTATUS>active</ACCTSTATUS>
						<CCYDESC>BDT</CCYDESC>
						<BALANCE>100000</BALANCE>
						<AVAILABLEBALANCE>100000</AVAILABLEBALANCE>
						<CUSTOMERNAME>cus_name</CUSTOMERNAME>
						<AMOUNTONHOLD>1000</AMOUNTONHOLD>
						<ODLIMIT>99</ODLIMIT>
						</EBLCUSTOMER>

						</QRYRESULT>";

                $result1 = "<QRYRESULT>
							<ISSUCCESS>N</ISSUCCESS>
							<REASON>E001</REASON>		
							<WARNING>some error message</WARNING>
							</QRYRESULT>";


                return $result;
                break;





            case "02":  // ACCOUNT SUMMARY


                $apprequest = "vw_account_summary";

                $xml = "<QUERY_ACC_SUMMARY>						
						<CUSTOMERID>" . $data['customerId'] . "</CUSTOMERID>
						<CUSTTOMERACCNO>" . $data['accountNumber'] . "</CUSTTOMERACCNO>
						</QUERY_ACC_SUMMARY>";


                $result1 = "<QRYRESULT>
							<ISSUCCESS>Y</ISSUCCESS>

							<CUSTSUMMARY ACCOUNT='11111'>
							<IDACCOUNT>1010101</IDACCOUNT>
							<CODACCTCURR>BDT</CODACCTCURR>
							<NUMBALANCE>99999</NUMBALANCE>
							<ACCTDESC>22222</ACCTDESC>
							<PRDNAME>Sanjit</PRDNAME>
							</CUSTSUMMARY>


							<CUSTSUMMARY ACCOUNT='11111'>
							<IDACCOUNT>202020</IDACCOUNT>
							<CODACCTCURR>BDT</CODACCTCURR>
							<NUMBALANCE>99999</NUMBALANCE>
							<ACCTDESC>22222</ACCTDESC>
							<PRDNAME>Sanjit</PRDNAME>
							</CUSTSUMMARY>

							</QRYRESULT>";


                $result = "<QRYRESULT>
							  <ISSUCCESS>Y</ISSUCCESS>

							  <CUSTPERSONAL>							  
								<DOB>01-JUL-75</DOB>
								<SEX>M</SEX>
								<FATHER_NAME>PRAN HARI DUTTA</FATHER_NAME>
								<MOTHER_NAME>SHUMOTI DUTTA</MOTHER_NAME>
								<MOB1>01783400500</MOB1>
								<MOB2>Not Available</MOB2>
								<EMAIL>DUTTAKS@EBL-BD.COM</EMAIL>
								<CURRADDR>G.P.JA-61 2ND FLOOR WIRELESS GATE MOHAKHALI C/A DHAKA-1212 TH- GULSHAN</CURRADDR>
								<PERMADDR>VILL- THAKURTALA PO-GORAKGHATA PS-MOHESHKHALI DIST-  COXSBAZAR CTG, BD</PERMADDR>

							  </CUSTPERSONAL>

							  <CUSTACCTSUMMARY>

								<CUSTSUMMARY ACCOUNT='1011020219645'>
									<IDACCOUNT>1011020219645</IDACCOUNT>
									<ACCTDESC>SANJIT KUMAR DUTTA</ACCTDESC>
									<ACCTTYPE>C</ACCTTYPE>
									<PRDNAME>High Performance Account (HPA)</PRDNAME>
									<CODACCTCURR>BDT</CODACCTCURR>
									<NUMBALANCE>0000.00</NUMBALANCE>
								</CUSTSUMMARY>

								<CUSTSUMMARY ACCOUNT='1011100196979'>
									<IDACCOUNT>1011100196979</IDACCOUNT>
									<ACCTDESC>SANJIT KUMAR DUTTA</ACCTDESC>
									<ACCTTYPE>C</ACCTTYPE>
									<PRDNAME>EBL Employee Salary Account</PRDNAME>
									<CODACCTCURR>BDT</CODACCTCURR>
									<NUMBALANCE>0000.00</NUMBALANCE>
								</CUSTSUMMARY>

								<CUSTSUMMARY ACCOUNT='9990703000001570'>
									<IDACCOUNT>9990703000001570</IDACCOUNT>
									<ACCTDESC>SANJIT KUMAR DUTTA</ACCTDESC>
									<ACCTTYPE>L</ACCTTYPE>
									<PRDNAME>Staff Loan - Car</PRDNAME>
									<CODACCTCURR>BDT</CODACCTCURR>
									<NUMBALANCE>0000.00</NUMBALANCE>
								</CUSTSUMMARY>


								<CUSTSUMMARY ACCOUNT='32534645567678'>
									<IDACCOUNT>32534645567678</IDACCOUNT>
									<ACCTDESC>SANJIT KUMAR DUTTA</ACCTDESC>
									<ACCTTYPE>T</ACCTTYPE>
									<PRDNAME>Term Deposite</PRDNAME>
									<CODACCTCURR>BDT</CODACCTCURR>
									<NUMBALANCE>0000.00</NUMBALANCE>
								</CUSTSUMMARY>

							  </CUSTACCTSUMMARY>
							</QRYRESULT>";



                return $result;
                break;





            case "03":  //  TRANSACTION HISTORY

                $apprequest = "vw_trn_history";

                $xml = "<QUERY_ACC_TRN>						
						<CUSTOMERID>" . $data['customerId'] . "</CUSTOMERID>
						<CUSTACC>" . $data['accountNumber'] . "</CUSTACC>
						</QUERY_ACC_TRN>";


                $result = "<QRYRESULT>

							<ISSUCCESS>Y</ISSUCCESS>

							<TRNHISTORY SL_NO='1'>
							<NBRACCOUNT>11111</NBRACCOUNT>
							<TXNDATE>02-MAR-14</TXNDATE>
							<DATVALUEDATE>28-FEB-14</DATVALUEDATE>
							<TXTREFERENCENO>**********</TXTREFERENCENO>
							<DESCRIPTION>*********</DESCRIPTION>
							<TXNAMOUNT>**********</TXNAMOUNT>
							<CODDRCR>D</CODDRCR>
							<BALANCE>**********</BALANCE>
							</TRNHISTORY>

							<TRNHISTORY SL_NO='2'>
							<NBRACCOUNT>11111</NBRACCOUNT>
							<TXNDATE>**********</TXNDATE>
							<DATVALUEDATE>*********</DATVALUEDATE>
							<TXTREFERENCENO>**********</TXTREFERENCENO>
							<DESCRIPTION>***********</DESCRIPTION>
							<TXNAMOUNT>*********</TXNAMOUNT>
							<CODDRCR>C</CODDRCR>
							<BALANCE>**************</BALANCE>
							</TRNHISTORY></QRYRESULT>";


                return $result;
                break;





            case "04":  // TERM DEPOSIT INFO

                $apprequest = "vw_td_info";

                $xml = "<QUERY_TD>						
						<IDCORPORATE>" . $data['customerId'] . "</IDCORPORATE>
						<TDACCNO>" . $data['accountNumber'] . "</TDACCNO>
						</QUERY_TD>";


                $result = "<QRYRESULT><ISSUCCESS>Y</ISSUCCESS>

							<CUSTOMERTD ACCOUNT='11111'>
							<TDACCTNO>*****</TDACCTNO>
							<CODPRODUCT>***</CODPRODUCT>
							<DATMATURITY>30-JUN-15</DATMATURITY>
							<CCY>BDT</CCY>
							<TD_AMOUNT>*****</TD_AMOUNT>
							<RATE>9</RATE>
							<DESCRIPTION>********</DESCRIPTION>
							</CUSTOMERTD></QRYRESULT>";

                return $result;
                break;




            case "05":  // CURRENT LOAN DETAIL

                $apprequest = "vw_cl_account";

                $xml = "<QUERY_CL>						
						<CUSTOMERID>" . $data['customerId'] . "</CUSTOMERID>
						<LOANACCNO>" . $data['accountNumber'] . "</LOANACCNO>
						</QUERY_CL>";

                $result = "<QRYRESULT><ISSUCCESS>Y</ISSUCCESS><CUSTOMERLOAN ACCOUNT='11111'>

								<ACCOUNT_NUMBER>*************</ACCOUNT_NUMBER>
								<CURRENCY>BDT</CURRENCY>
								<PRODUCT_NAME>*************</PRODUCT_NAME>
								<OPENING_DATE>dd-mmm-yy</OPENING_DATE>
								<MATURITY_DATE>dd-mmm-yy</MATURITY_DATE>
								<NEXT_INST_DATE>dd-mmm-yy</NEXT_INST_DATE>
								<NEXT_INST_AMT>*******</NEXT_INST_AMT>
								<LN_OUTSTANDING>*******</LN_OUTSTANDING>
								<AMT_DISBURSED>*******</AMT_DISBURSED>
							    </CUSTOMERLOAN></QRYRESULT>";

                return $result;
                break;






            case "06":  // OWN ACCOUNT TRANSFER


                $apprequest = "req_own_account_fund_transfer";

                // customerId = cfId
                $xml = "<REQ_FUND_TRANSFER>
						<CUSTOMERID>" . $data['customerId'] . "</CUSTOMERID>
						<FROM_ACCOUNT>" . $data['fromAccount'] . "</FROM_ACCOUNT>
						<TO_ACCOUNT>" . $data['toAccount'] . "</TO_ACCOUNT>
						<AMOUNT>" . $data['amount'] . "</AMOUNT>
						<DATE>" . $data['date'] . "</DATE>
						<NARRATION>" . $data['narration'] . "</NARRATION>
						</REQ_FUND_TRANSFER>"; // date format yyyy-mm-dd


                $result = "<QRYRESULT>
							<ISSUCCESS>Y</ISSUCCESS>
							<TRN_REF>2498297295729857927</TRN_REF>
							<WARNING>IF ANY WARNING</WARNING>
							</QRYRESULT>";


                return $result;
                break;






            case "07":  // EBL ACCOUNT TRANSFER

                $apprequest = "req_ebl_account_transfer";

                $xml = "<REQ_FUND_TRANSFER>
						<USER_ID>" . $data['userId'] . "</USER_ID>
						<CUSTOMERID>" . $data['customerId'] . "</CUSTOMERID>
						<FROM_ACCOUNT>" . $data['fromAccount'] . "</FROM_ACCOUNT>
						<TO_ACCOUNT>" . $data['toAccount'] . "</TO_ACCOUNT>
						<AMOUNT>" . $data['amount'] . "</AMOUNT>
						<DATE>" . $data['date'] . "</DATE>
						<OTP_CODE>" . $data['otpCode'] . "</OTP_CODE>
						<NARRATION>" . $data['narration'] . "</NARRATION>
						</REQ_FUND_TRANSFER>";  // date format yyyy-mm-dd

                $result = "<QRYRESULT>
							<ISSUCCESS>Y</ISSUCCESS>
							<TRN_REF>8759823759273957</TRN_REF>
							<WARNING>IF THERE ANY WARNING</WARNING>
							</QRYRESULT>";


                return $result;
                break;







            case "08":  // OTHER BANK TRANSFER

                $apprequest = "req_other_bank_transfer";

                $xml = "<REQ_FUND_TRANSFER>
						<USER_ID>" . $data['userId'] . "</USER_ID>
						<CUSTOMERID>" . $data['customerId'] . "</CUSTOMERID>
						<FROM_ACCOUNT>" . $data['fromAccount'] . "</FROM_ACCOUNT>
						<RCVR_NAME>" . $data['rcvrName'] . "</RCVR_NAME>
						<TO_ACCOUNT>" . $data['toAccount'] . "</TO_ACCOUNT>
						<RCVR_BNK_NAME>" . $data['rcvrBankName'] . "</RCVR_BNK_NAME>
						<RCVR_DIST_NAME>" . $data['rcvrDistName'] . "</RCVR_DIST_NAME>
						<RCVR_BRN_NAME>" . $data['rcvrBrnName'] . "</RCVR_BRN_NAME>
						<RCVR_RT_NUMBER>" . $data['rcvrRtNumber'] . "</RCVR_RT_NUMBER>
						<AMOUNT>" . $data['amount'] . "</AMOUNT>
						<DATE>" . $data['date'] . "</DATE>
						<OTP_CODE>" . $data['otpCode'] . "</OTP_CODE>
						<NARRATION>" . $data['narration'] . "</NARRATION>
						</REQ_FUND_TRANSFER>";  // date format yyyy-mm-dd


                $result = "<QRYRESULT>
							<ISSUCCESS>Y</ISSUCCESS>
							<TRN_REF>87507985484078</TRN_REF>
							<WARNING>IF THERE ANY WARNING</WARNING>
							</QRYRESULT>";


                return $result;
                break;



            case "09":  // BILLS PAY

                $apprequest = "req_bills_pay";

                $xml = "<REQ_FUND_TRANSFER>
						<CUSTOMERID>" . $data['customerId'] . "</CUSTOMERID>
						<FROM_ACCOUNT>" . $data['fromAccount'] . "</FROM_ACCOUNT>
						<BILLER_NAME>" . $data['billerName'] . "</BILLER_NAME>
						<AMOUNT>" . $data['amount'] . "</AMOUNT>
						<DATE>" . $data['date'] . "</DATE>
						<NARRATION>" . $data['narration'] . "</NARRATION>
						</REQ_FUND_TRANSFER>";

                $result = "<QRYRESULT>
							<ISSUCCESS>Y</ISSUCCESS>
							<TRN_REF>12345678</TRN_REF>
							<WARNING>IF THERE ANY WARNING</WARNING>
							</QRYRESULT>";


                return $result;
                break;






            case "10": // CARDS SERVICE

                $apprequest = "vw_card_information";
                $loginName = "EBLSKYADM";
                $passWord = "ESBADM123";

                $xml = "<REQ_CARD_INFO>
							<LOGIN>" . $loginName . "</LOGIN>
							<PASSWORD>" . $passWord . "</PASSWORD>
							<METHODNAME>" . $data['methodName'] . "</METHODNAME>
							<CLIENTID>" . $data['clientId'] . "</CLIENTID>
							<CARD_NO>" . $data['cardNumber'] . "</CARD_NO>
							<CARD_CURRENCY>" . $data['cardCurrency'] . "</CARD_CURRENCY>
							</REQ_CARD_INFO>";





                switch ($data['methodName']) {

                    case "CARD_STATUS":

                        $result = "<QRYRESULT>
											<ISSUCCESS>Y</ISSUCCESS>
											<EBLCRD_OUTPUT>
											<ITEM>
												<STATUS_CODE>N</STATUS_CODE>
												<STATUS_DESCRIPTION>Normal</STATUS_DESCRIPTION>
											</ITEM>
											</EBLCRD_OUTPUT>
										</QRYRESULT>";


                        return $result;
                        break;



                    case "CARD_ACCOUNT_DETAILS":

                        $result = "<QRYRESULT>
										<ISSUCCESS>Y</ISSUCCESS>
										<EBLCRD_OUTPUT>
										<ITEM>
											<CLIENTNUMBER>259354</CLIENTNUMBER>
											<CARDNUMBER>4679225077973019</CARDNUMBER>
											<CARDNAME>SANJIT DUTTA</CARDNAME>
											<CARDSTATUS>Normal</CARDSTATUS>
											<ACCOUNTNUMBER>607000</ACCOUNTNUMBER>
											<CURRENCYCODE>BDT</CURRENCYCODE>
											<CREDITLIMIT>399000.0</CREDITLIMIT>
											<CASHLIMIT>399000.0</CASHLIMIT>
											<AVAILABLECREDITLIMIT>228065.98</AVAILABLECREDITLIMIT>
											<AVAILABLECASHLIMIT>228065.98</AVAILABLECASHLIMIT>
											<OPENTOBUY>228065.98</OPENTOBUY>
											<OUTSTANDINGBALANCE>170934.02</OUTSTANDINGBALANCE>
											<PENDINGTRANSACTIONTOTAL>0.0</PENDINGTRANSACTIONTOTAL>
											<MINIMUMAMOUNTDUE>8546.7</MINIMUMAMOUNTDUE>
											<DUEDATE>09/08/2015</DUEDATE>
											<PAYMENTSSINCELASTSTATEMENT>0.0</PAYMENTSSINCELASTSTATEMENT>
											<LASTSTATEMENTDATE>25/07/2015</LASTSTATEMENTDATE>
										</ITEM>
										<ITEM>
											<CLIENTNUMBER>259354</CLIENTNUMBER>
											<CARDNUMBER>4679225077973019</CARDNUMBER>
											<CARDNAME>SANJIT DUTTA</CARDNAME>
											<CARDSTATUS>Normal</CARDSTATUS>
											<ACCOUNTNUMBER>607001</ACCOUNTNUMBER>
											<CURRENCYCODE>USD</CURRENCYCODE>
											<CREDITLIMIT>10.0</CREDITLIMIT>
											<CASHLIMIT>10.0</CASHLIMIT>
											<AVAILABLECREDITLIMIT>10.0</AVAILABLECREDITLIMIT>
											<AVAILABLECASHLIMIT>10.0</AVAILABLECASHLIMIT>
											<OPENTOBUY>10.0</OPENTOBUY>
											<OUTSTANDINGBALANCE>0.0</OUTSTANDINGBALANCE>
											<PENDINGTRANSACTIONTOTAL>0.0</PENDINGTRANSACTIONTOTAL>
											<MINIMUMAMOUNTDUE>0.0</MINIMUMAMOUNTDUE>
											<DUEDATE>09/08/2015</DUEDATE>
											<PAYMENTSSINCELASTSTATEMENT>0.0</PAYMENTSSINCELASTSTATEMENT>
											<LASTSTATEMENTDATE>25/07/2015</LASTSTATEMENTDATE>
										</ITEM>
										</EBLCRD_OUTPUT>
									</QRYRESULT>";
                        return $result;
                        break;





                    case "CARD_CUSTOMER_DETAILS":

                        $result = "<QRYRESULT>
										<ISSUCCESS>Y</ISSUCCESS>
										<EBLCRD_OUTPUT>
										<ITEM>
											<ADDRESSCODE>L1</ADDRESSCODE>
											<CAREOF>null</CAREOF>
											<ADDRESS_1>WALI VILLA,G.P.JA-61(3RD FL),WIRELESS GATE,MOHAKHALI,DHAKA</ADDRESS_1>
											<ADDRESS_2>null</ADDRESS_2>
											<ADDRESS_3>null</ADDRESS_3>
											<ADDRESS_4>null</ADDRESS_4>
											<ZIPCODE>1212</ZIPCODE>
											<MOBILEPHONE>01199800313</MOBILEPHONE>
											<EMAIL>duttaks@ebl-bd.com,sanjitcoxs@gmail.com</EMAIL>
										</ITEM>
										<ITEM>
											<ADDRESSCODE>L6</ADDRESSCODE>
											<CAREOF>null</CAREOF>
											<ADDRESS_1>EASTERN BANK LTD,IT DIVISION,10,DILKUSHA C/A</ADDRESS_1>
											<ADDRESS_2>null</ADDRESS_2>
											<ADDRESS_3>null</ADDRESS_3>
											<ADDRESS_4>null</ADDRESS_4>
											<ZIPCODE>1000</ZIPCODE>
											<MOBILEPHONE>01783400500</MOBILEPHONE>
											<EMAIL>duttaks@ebl-bd.com,sanjitcoxs@gmail.com</EMAIL>
										</ITEM>
										<ITEM>
											<ADDRESSCODE>L8</ADDRESSCODE>
											<CAREOF>null</CAREOF>
											<ADDRESS_1>EASTERN BANK LTD,IT DIVISION,10,DILKUSHA C/A,DHAKA</ADDRESS_1>
											<ADDRESS_2>null</ADDRESS_2>
											<ADDRESS_3>null</ADDRESS_3>
											<ADDRESS_4>null</ADDRESS_4>
											<ZIPCODE>0000</ZIPCODE>
											<MOBILEPHONE>9556360-111</MOBILEPHONE>
											<EMAIL>duttaks@ebl-bd.com</EMAIL>
										</ITEM>
										</EBLCRD_OUTPUT>
									</QRYRESULT>";



                        $result1 = "<QRYRESULT>
										<ISSUCCESS>N</ISSUCCESS>
										<REASON>E001</REASON>
										<WARNING>error in customer details</WARNING>		
										</QRYRESULT>";



                        return $result;
                        break;




                    case "CARD_LIMITS":

                        $result = "<QRYRESULT>
										<ISSUCCESS>Y</ISSUCCESS>
										<EBLCRD_OUTPUT>
										<ITEM>
											<SHADOW_ACCOUNT_CURRENCY>050</SHADOW_ACCOUNT_CURRENCY>
											<CREDIT_LIMIT>399000.0</CREDIT_LIMIT>
											<CASH_LIMIT>399000.0</CASH_LIMIT>
											<LOAN_LIMIT>0.0</LOAN_LIMIT>
											<USD2_ACCOUNT_LIMIT>0.0</USD2_ACCOUNT_LIMIT>
											<SAARC_LIMIT>0.0</SAARC_LIMIT>
											<NOSAARC_LIMIT>0.0</NOSAARC_LIMIT>
											<OQ_SAARC_LIMIT>0.0</OQ_SAARC_LIMIT>
											<OQ_NOSAARC_LIMIT>0.0</OQ_NOSAARC_LIMIT>
										</ITEM>
										<ITEM>
											<SHADOW_ACCOUNT_CURRENCY>840</SHADOW_ACCOUNT_CURRENCY>
											<CREDIT_LIMIT>10.0</CREDIT_LIMIT>
											<CASH_LIMIT>10.0</CASH_LIMIT>
											<LOAN_LIMIT>0.0</LOAN_LIMIT>
											<USD2_ACCOUNT_LIMIT>0.0</USD2_ACCOUNT_LIMIT>
											<SAARC_LIMIT>0.0</SAARC_LIMIT>
											<NOSAARC_LIMIT>0.0</NOSAARC_LIMIT>
											<OQ_SAARC_LIMIT>0.0</OQ_SAARC_LIMIT>
											<OQ_NOSAARC_LIMIT>0.0</OQ_NOSAARC_LIMIT>
										</ITEM>
										</EBLCRD_OUTPUT>
									</QRYRESULT>";
                        return $result;
                        break;





                    case "CARD_BALANCE":

                        $result = "<QRYRESULT>
										<ISSUCCESS>Y</ISSUCCESS>
										<EBLCRD_OUTPUT>
										<ITEM>
											<SHADOW_ACCOUNT_CURRENCY>050</SHADOW_ACCOUNT_CURRENCY>
											<CURRENT_BALANCE>0.0</CURRENT_BALANCE>
											<CLOSING_BALANCE>170934.02</CLOSING_BALANCE>
											<BALANCE>170934.02</BALANCE>
											<LIVE_DEPOSIT>0.0</LIVE_DEPOSIT>
											<OUTSTANDING>170934.02</OUTSTANDING>
											<CURRENT_PAYMENT>0.0</CURRENT_PAYMENT>
										</ITEM>
										<ITEM>
											<SHADOW_ACCOUNT_CURRENCY>840</SHADOW_ACCOUNT_CURRENCY>
											<CURRENT_BALANCE>0.0</CURRENT_BALANCE>
											<CLOSING_BALANCE>0.0</CLOSING_BALANCE>
											<BALANCE>0.0</BALANCE>
											<LIVE_DEPOSIT>0.0</LIVE_DEPOSIT>
											<OUTSTANDING>0.0</OUTSTANDING>
											<CURRENT_PAYMENT>0.0</CURRENT_PAYMENT>
										</ITEM>
										</EBLCRD_OUTPUT>
									</QRYRESULT>";
                        return $result;
                        break;




                    case "CARD_ACCOUNT_STATEMENT":

                        $result = "<QRYRESULT>
										<ISSUCCESS>Y</ISSUCCESS>
										<EBLCRD_OUTPUT>
										<ITEM>
											<MICROFILM_REF_NUMBER>71111115206490149131953</MICROFILM_REF_NUMBER>
											<MICROFILM_REF_SEQ>0.0</MICROFILM_REF_SEQ>
											<STATEMENT_DATE>25/07/2015</STATEMENT_DATE>
											<DUE_DATE>09/08/2015</DUE_DATE>
											<VALUE_DATE>09/08/2015</VALUE_DATE>
											<POSTING_DATE>25/07/2015</POSTING_DATE>
											<TRANSACTION_DESCRIPTION>Risk Assurance Fee</TRANSACTION_DESCRIPTION>
											<CARD_NUMBER>4679225077973019</CARD_NUMBER>
											<TRANSACTION_CODE>3G</TRANSACTION_CODE>
											<TRANSACTION_SIGN>D</TRANSACTION_SIGN>
											<BILLING_CURRENCY>BDT</BILLING_CURRENCY>
											<BILLING_AMOUNT>595.87</BILLING_AMOUNT>
											<LAST_INTEREST_AMOUNT>0.0</LAST_INTEREST_AMOUNT>
											<LAST_CHARGE_VALUE_DAYS>0.0</LAST_CHARGE_VALUE_DAYS>
										</ITEM>
										<ITEM>
											<MICROFILM_REF_NUMBER>71111115206490149131953</MICROFILM_REF_NUMBER>
											<MICROFILM_REF_SEQ>1.0</MICROFILM_REF_SEQ>
											<STATEMENT_DATE>25/07/2015</STATEMENT_DATE>
											<DUE_DATE>09/08/2015</DUE_DATE>
											<VALUE_DATE>09/08/2015</VALUE_DATE>
											<POSTING_DATE>25/07/2015</POSTING_DATE>
											<TRANSACTION_DESCRIPTION>VAT</TRANSACTION_DESCRIPTION>
											<CARD_NUMBER>4679225077973019</CARD_NUMBER>
											<TRANSACTION_CODE>69</TRANSACTION_CODE>
											<TRANSACTION_SIGN>D</TRANSACTION_SIGN>
											<BILLING_CURRENCY>BDT</BILLING_CURRENCY>
											<BILLING_AMOUNT>89.38</BILLING_AMOUNT>
											<LAST_INTEREST_AMOUNT>0.0</LAST_INTEREST_AMOUNT>
											<LAST_CHARGE_VALUE_DAYS>0.0</LAST_CHARGE_VALUE_DAYS>
										</ITEM>
										<ITEM>
											<MICROFILM_REF_NUMBER>71111115206490049121948</MICROFILM_REF_NUMBER>
											<MICROFILM_REF_SEQ>0.0</MICROFILM_REF_SEQ>
											<STATEMENT_DATE>25/07/2015</STATEMENT_DATE>
											<DUE_DATE>09/08/2015</DUE_DATE>
											<VALUE_DATE>25/07/2015</VALUE_DATE>
											<POSTING_DATE>25/07/2015</POSTING_DATE>
											<TRANSACTION_DESCRIPTION>Debit interest</TRANSACTION_DESCRIPTION>
											<CARD_NUMBER>4679225077973019</CARD_NUMBER>
											<TRANSACTION_CODE>63</TRANSACTION_CODE>
											<TRANSACTION_SIGN>D</TRANSACTION_SIGN>
											<BILLING_CURRENCY>BDT</BILLING_CURRENCY>
											<BILLING_AMOUNT>4183.01</BILLING_AMOUNT>
											<LAST_INTEREST_AMOUNT>0.0</LAST_INTEREST_AMOUNT>
											<LAST_CHARGE_VALUE_DAYS>0.0</LAST_CHARGE_VALUE_DAYS>
										</ITEM>
										</EBLCRD_OUTPUT>
									</QRYRESULT>";
                        return $result;
                        break;








                    case "CARD_INFORMATION_DETAILS":

                        $result = "<QRYRESULT>
										<ISSUCCESS>Y</ISSUCCESS>
										<EBLCRD_OUTPUT>
										<ITEM>
											<CLIENTNUMBER>259354</CLIENTNUMBER>
											<CARDNUMBER>4679225077973019</CARDNUMBER>
											<DEBITORCREDITCARD>Credit</DEBITORCREDITCARD>
											<CARDTYPE>VISA PLATINUM PERSONAL</CARDTYPE>
											<CARDPRODUCT>VISA PLATINUM</CARDPRODUCT>
											<CARDSTATUS>Normal</CARDSTATUS>
											<ISSUPPLEMENTARY>Y</ISSUPPLEMENTARY>
											<PRIMARYCARDNUMBER>null</PRIMARYCARDNUMBER>
											<EMBOSSEDNAME>SANJIT DUTTA</EMBOSSEDNAME>
											<EXPIRYDATE>1017</EXPIRYDATE>
											<CLIENTBILLINGADDRESS>EASTERN BANK LTD,IT DIVISION,10,DILKUSHA C/A    00001 01783400500</CLIENTBILLINGADDRESS>
											<DOB>01-JUL-75</DOB>
											<MOTHERSNAME>SHUMOTI DUTTA</MOTHERSNAME>
										</ITEM>
										</EBLCRD_OUTPUT>
									</QRYRESULT>";
                        return $result;
                        break;
                }
                break;
        }
    }

    private function pushtoservice($url, $appreq, $xml) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $appreq . '=' . $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $server_output = curl_exec($ch);
        curl_close($ch);


        //$server_output = $xml;

        return $server_output;
    }

}
