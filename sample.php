<?php
    include("echosignWrapper");
    
    $wrapper = new echoSignWrapper();
    // choose between NONE, FIRST, or LAST
    $wrapper->requireSenderSignature = SignatureFlow::NONE;
    
    // api key and password ("Get an API key and the developer kit from the ÒEchoSign APIÓ tab on your Account page")
    $wrapper->instantiateClient ('key', 'password');
    
    // select the account the document should send from by the e-mail address
    $wrapper->setUserFromEmail('echoSignAccountEmail@mail.com');
    
    // list all recipients in an array
    $recipients = array('recipent1@email.com', 'recipent2@otherMail.com');
    $wrapper->setRecipientsFromArray($recipients);
    
    // list the url to all of the documents. These documents should be stored on a server (NOT saved in echoSign)
    $documents = array('http://www.example.com/folder/file1.pdf', 'http://www.example.com/folder2/file2.pdf');
    $wrapper->setFileFromUrlArray($documents);
    
    // {{aFieldName_es_:prefill}} {{aFieldName2_es_:prefill}}
    $prefills = array ("aFieldName" => "value1", "aFieldName2" => "value2");
    $wrapper->setPrefillsFromAssocArray($prefills);
    
    // this returns the result of the SOAP call 
    $result = $wrapper->sendDocumentWithTitleAndMessage('Document Title', 'This document is VERY important! You have to sign it or else you\'ll get in trouble.');
?>