<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <soap:Body>
    <EntityInquiry xmlns="http://CTL.COM.Services.Prime.Issuer.WebServices/PrimeIssuerServices">
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
        <Entity>Account</Entity>
        <Reference>65</Reference>
        <Numbers>
          <string><?= $account_no; ?></string>
        </Numbers>
        <IncludeReplacedCards>false</IncludeReplacedCards>
        <ExtensionFieldsCategoryFilter>
          <string />
        </ExtensionFieldsCategoryFilter>
        <RecalculateExtensions Scope="All">
          <FieldNumber>0</FieldNumber>
        </RecalculateExtensions>
        <IncludeRouting>None</IncludeRouting>
        <IncludeAddresses>All</IncludeAddresses>
        <IncludeRiskDomains>false</IncludeRiskDomains>
        <IncludeFees>false</IncludeFees>
      </xmlRequest>
    </EntityInquiry>
  </soap:Body>
</soap:Envelope>