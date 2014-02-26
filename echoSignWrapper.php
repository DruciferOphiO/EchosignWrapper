<?php 
    class echoSignWrapper
    {
        private $wsdlUrl;
        private $apiKey;
        private $userEmail;
        private $password;
        private $userKey;
        private $client;
        private $userInfoArray;
        private $recipientArray = array();
        private $documentArray = array();
        private $mergeInfoArray = array();
        public $requireSenderSignature = SignatureFlow::NONE;
        
        public function instantiateClient($key, $password)
        {
            $this->usdlUrl = 'https://secure.echosign.com/services/EchoSignDocumentService16?wsdl';
            $this->apiKey = $key;
            $this->password = $password;
            $this->client = new SoapClient($url, array(ÔtraceÕ => TRUE));
            return $this->client;
        }
        
        public function setUserFromEmail($email)
        {
            try
            {
                $getUserParameters = array('apiKey'=>$this->apiKey);
                $userInfo = $$this->client->getUsersInAccount($getUserParameters);
                
                $accountResults = $userInfo->getUsersInAccountResult->userListForAccount->UserInfo;
        
                foreach($accountResults as $aUser)
                {
                    if($aUser->email == $this->userEmail)
                    {
                        $this->userKey = $aUser->userKey;
                    }
                    
                }
                $this->userInfoArray = array ('userKey'=>$this->userKey, 'userEmail'=>$email, 'userPassword'=>$this->password);
                return 'Success';
            }
            catch (SoapFault $e)
            {
                return $e;
            }
        }
        
        public function setRecipientsFromArray($recipientArray)
        {
            $this->recipientArray = array();
            foreach($recipientArray as $email)
            {
                $recipientInfo = array('email'=>$email, 'role'=>'SIGNER');
                array_push($this->recipientArray, $recipientInfo);
            }
        }
        
        public function setFileFromUrlArray($fileUrlArray)
        {
            $this->documentArray = array();
            foreach($fileUrlArray as $fileUrl)
            {
                $urlArray = explode('/', $fileUrl);
                $lastIndex = count($urlArray)-1;
                $theDocument = array('fileName' => $urlArray[$lastIndex], 'url'=>$fileUrl);
                array_push($this->documentArray, $theDocument);
            }
        }
        
        public function setPrefillsFromAssocArray($data)
        {
            $allMergeData = array();
            $this->mergeInfoArray = array();
            foreach ($data as $key=>$value)
            {
                $mergeData = array('fieldName'=>$key, 'defaultValue'=>$value);
                array_push($allMergeData, $mergeData);
            }
            $this->mergeInfoArray['mergeFields'] = $allMergeData;
        }
        
        public function sendDocumentWithTitle($title)
        {
            $documentCreationInfoArray = array();
            
            if(empty($this->documentArray) == FALSE)
            {
                $documentCreationInfoArray = array('recipients'=>$this->recipientArray,
                                              'name'=>$title,
                                              'fileInfos' => $this->documentArray,
                                              'signatureType'=>'ESIGN',
                                              'signatureFlow'=>$this->requireSenderSignature,
                                              'mergeFieldInfo'=>$this->documentArray);
            }
            else
            {
                $documentCreationInfoArray = array('recipients'=>$this->recipientArray,
                                              'name'=>$title,
                                              'fileInfos' => $fileInfosArray,
                                              'signatureType'=>'ESIGN',
                                              'signatureFlow'=>$this->requireSenderSignature);
            }
            
            $sendDocParams = array('apiKey'=>$this->apiKey, 'senderInfo'=>$this->userInfoArray, 'documentCreationInfo'=>$documentCreationInfoArray);
            
            return $this->client->sendDocument($sendDocParams);
        }
        
        public function sendDocumentWithTitleAndMessage($title, $message)
        {
            $documentCreationInfoArray = array();
            
            if(empty($this->documentArray) == FALSE)
            {
                $documentCreationInfoArray = array('recipients'=>$this->recipientArray,
                                              'name'=>$title,
                                              'message'=>$message,
                                              'fileInfos' => $this->documentArray,
                                              'signatureType'=>'ESIGN',
                                              'signatureFlow'=>$this->requireSenderSignature,
                                              'mergeFieldInfo'=>$this->documentArray);
            }
            else
            {
                $documentCreationInfoArray = array('recipients'=>$this->recipientArray,
                                              'name'=>$title,
                                              'message'=>$message,
                                              'fileInfos' => $fileInfosArray,
                                              'signatureType'=>'ESIGN',
                                              'signatureFlow'=>$this->requireSenderSignature);
            }
            
            $sendDocParams = array('apiKey'=>$this->apiKey, 'senderInfo'=>$this->userInfoArray, 'documentCreationInfo'=>$documentCreationInfoArray);
            
            return $this->client->sendDocument($sendDocParams);
        }
        
    }
    
    class SignatureFlow
    {
        const NONE = 'SENDER_SIGNATURE_NOT_REQUIRED';
        const FIRST = 'SENDER_SIGNS_FIRST';
        const LAST = 'SENDER_SIGNS_LAST';
    }
    
?>