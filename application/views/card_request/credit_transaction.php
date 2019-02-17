<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Body>
        <PostTransaction xmlns="http://CTL.COM.Services.Prime.Issuer.WebServices/PrimeIssuerServices">
            <xmlRequest>
                <Header>
                    <MessageID><?= $message_id; ?></MessageID>
                    <CorrelationID />
                    <SystemID />
                    <RequestorID />
                    <Ticket><?= $ticket_id; ?></Ticket>
                    <CallerRef />
                    <Origin />
                    <Culture />
                </Header>
                <Reference>C</Reference>
                <Number><?= $card_no; ?></Number>
                <PostTo>Card</PostTo>
                <OriginalTrxnSerno>0</OriginalTrxnSerno>
                <OriginalTrxnPartitionKey>0</OriginalTrxnPartitionKey>
                <TrxnDate />
                <ValueDate />
                <Currency>050</Currency>
                <Amount><?= $amount; ?></Amount>
                <Type>PAYMT</Type>
                <Reason />
                <Text />
                <MerchantType />
                <AcquirerCountryCode />
                <PosEntryMode />
                <PosConditionMode />
                <AcquirerID />
                <RetrievalReferenceNo />
                <AuthorisationID />
                <ResponseCode />
                <PosID />
                <MerchantID />
                <MerchantName />
                <MerchantCity />
                <MerchantCountry />
                <GetOnlineAuthorization>false</GetOnlineAuthorization>
            </xmlRequest>
        </PostTransaction>
    </soap:Body>
</soap:Envelope>