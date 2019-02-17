<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Body>
        <StatementInquiry xmlns="http://CTL.COM.Services.Prime.Issuer.WebServices/PrimeIssuerServices">
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
                <Paging>
                    <Size>0</Size>
                    <Key />
                    <OrderBy />
                    <Direction>Ascending</Direction>
                </Paging>
                <Period>FromTo</Period>
                <DateFrom><?= $from_date; ?></DateFrom>
                <DateTo><?= $to_date; ?></DateTo>
                <Content>Card</Content>
                <Reference>C</Reference>
                <Number><?= $card_no; ?></Number>
                <HierarchicalTrxns>false</HierarchicalTrxns>
                <CustomerNumber />
                <LevelDepth>0</LevelDepth>
                <TransactionMemos>None</TransactionMemos>
                <IncludeTransactionExtensions>false</IncludeTransactionExtensions>
                <FilterByGLCategory>
                    <string />
                </FilterByGLCategory>
                <ISOCodeType>Default</ISOCodeType>
            </xmlRequest>
        </StatementInquiry>
    </soap:Body>
</soap:Envelope>