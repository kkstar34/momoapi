<?php
/**
 * User: Kouyate Karim
 * Date: 15/12/2020
 * Time: 09:04
 *
 */

namespace Kouyatekarim\Momoapi\Products;


// use Kouyatekarim\Momoapi\Responses\Balance;
use Kouyatekarim\Momoapi\Responses\Token;
use Kouyatekarim\Momoapi\Responses\TransactionStatus;
use Kouyatekarim\Momoapi\Traits\Configurations;
use Kouyatekarim\Momoapi\Traits\SandboxUserProvisioning;
require_once $path = base_path('vendor/pear/http_request2/HTTP/Request2.php');



/**
 * Class Product
 * @package Kouyatekarim\MomoapiProducts
 */
abstract class Product
{
    use Configurations, SandboxUserProvisioning;

 


    const TOKEN_URI = "/token/";

    const BALANCE_URI = "/v1_0/account/balance";

    const ACCOUNT_HOLDER_URI = "/v1_0/accountholder";

    const API_USER_URI = "/v1_0/apiuser";



    /**
     * @var string $callbackHost
     */
    protected $callbackHost = "http://localhost:8000";

    protected static $baseUrl= "https://ericssonbasicapi1.azure-api.net";

    /**
     * @var string $callbackUrl
     */
    protected $callbackUrl = "http://localhost:8000/callback";

    /**
     * @var string $environment
     */
    protected $environment = "sandbox";

    /**
     * @var string  $accountHolderIdType
     */
    protected $accountHolderIdType = "msisdn";

    /**
     * @var string $subscriptionKey
     */
    protected $subscriptionKey;

    /**
     * @var string $xReferenceId
     */
    protected $xReferenceId;

    /**
     * @var string $apiKey
     */
    protected $apiKey;

    /**
     * @var string $accessToken
     */
    protected $accessToken;

    /**
     * Product constructor.
     * @param $options
     */
    public function __construct($options) {
        if(!isset($options['subscriptionKey']))
            throw new \InvalidArgumentException("subscriptionKey should be specified");

        if(!isset($options['xReferenceId']))
            throw new \InvalidArgumentException("xReferenceId should be specified");

        $this->setOptions($options);

        self::$baseUrl = $this->environment != "sandbox" ? "https://ericssonbasicapi1.azure-api.net" :  'https://sandbox.momodeveloper.mtn.com';
    }

    /**
     * create new http request
     *
     * @return Request
     */
    protected function newRequest() {
        return new \Http_Request2($this->getProductBaseUrl());
    }

    abstract protected function getProductBaseUrl();

    public function getUuid(){
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
               // 32 bits for "time_low"
               mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

               // 16 bits for "time_mid"
               mt_rand( 0, 0xffff ),

               // 16 bits for "time_hi_and_version",
               // four most significant bits holds version number 4
               mt_rand( 0, 0x0fff ) | 0x4000,

               // 16 bits, 8 bits for "clk_seq_hi_res",
               // 8 bits for "clk_seq_low",
               // two most significant bits holds zero and one for variant DCE1.1
               mt_rand( 0, 0x3fff ) | 0x8000,

               // 48 bits for "node"
               mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
           );
    }

    /**
     * Get token
     *
     * @return Token
     * @throws \Exception
     */
    public function getToken() {
        if(!$this->apiKey)
            throw new \InvalidArgumentException("apiKey should be specified");


            $request = new \Http_Request2($this->getProductBaseUrl(). self::TOKEN_URI);
            $url = $request->getUrl();

            $headers = array(
                // Request headers
                'Authorization' => 'Basic '.base64_encode($this->xReferenceId . ':' . $this->apiKey),
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            );

            $request->setHeader($headers);

         

            //$url->setQueryVariables($parameters);

            $request->setMethod(\HTTP_Request2::METHOD_POST);
            

            try
            {
                $response = $request->send();

    
         
                $access_token = json_decode($response->getBody());

               
               
                return Token::create($access_token); 
                
                
             
              
            }
            catch (HttpException $ex)
            {
                echo $ex;
            }
       /*  try {
            $response = $this->newClient()->post($this->getProductBaseUrl() . self::TOKEN_URI, [
                'headers' => [
                    'Authorization' => 'Basic '.base64_encode($this->xReferenceId . ':' . $this->apiKey)
                ],
                'json' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);

            return Token::create(json_decode($response->getBody()->getContents(), true));
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        } */
    }

    /**
     * Get account balance
     *
     * @return Balance
     * @throws \Exception
     */
    public function getAccountBalance() {
        if(!$this->accessToken)
            throw new \InvalidArgumentException("accessToken should be specified");

        try {
            $response = $this->newClient()->get($this->getProductBaseUrl() . self::BALANCE_URI, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'X-Target-Environment' => $this->environment,
                ]
            ]);

            return Balance::create(json_decode($response->getBody()->getContents(), true));
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * Get account holder information
     *
     * @param $accountHolderId
     * @return array
     * @throws \Exception
     */
    public function getAccountHolderInfo($accountHolderId) {
        if(!$this->accessToken)
            throw new \InvalidArgumentException("accessToken should be specified");

        try {
            $url = $this->getProductBaseUrl() . self::ACCOUNT_HOLDER_URI . "/" . $this->accountHolderIdType . "/" . $accountHolderId . "/active";
            $response = $this->newClient()->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'X-Target-Environment' => $this->environment,
                ],
            ]);

            return ['statusCode' => $response->getStatusCode()];
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    protected abstract function transactionUrl();

    /**
     * Start a payment transaction
     *
     * @param $externalId
     * @param $partyId
     * @param $amount
     * @param $currency
     * @param string $payerMessage
     * @param string $payeeNote
     * @return array
     * @throws \Exception
     */
    protected function transact($externalId, $partyId, $amount, $currency, $payerMessage = '', $payeeNote = '') {
        if(!$this->accessToken)
            throw new \InvalidArgumentException("accessToken should be specified");



        $request = new \Http_Request2($this->transactionUrl());
        $url = $request->getUrl();
   
       
        
       
      
        $headers = array(
            // Request headers
            'Authorization' => 'Bearer '. $this->accessToken->getAccessToken(),
            'X-Reference-Id' => $this->getUuid(),
            'Content-Type' => 'application/json;charset=utf-8',
            'X-Target-Environment' => $this->environment,
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
          
            
        );

    
          
        $request->setHeader($headers);


        $parameters = array();

       $url->setQueryVariables($parameters);

            $body = array(
                'amount' => $amount,
                'currency' => $currency,
                'externalId' => $externalId,
                'payer' =>array(
                    'partyIdType'=>'MSISDN',
                'partyId'=> '225'.$partyId,
                ) ,
                'payerMessage'=> $payerMessage,
                'payeeNote'=>$payeeNote
                );

        $body = json_encode($body);

        $request->setMethod(\HTTP_Request2::METHOD_POST);
        // Request body
        $request->setBody($body);
      
        
        try
        {

            $response = $request->send();
            
            
          

            var_dump($response);
            
         
          
        }
        catch (HttpException $ex)
        {
            echo $ex;
        }



       
    }

    /**
     * Get transaction status
     *
     * @param $paymentRef
     * @return TransactionStatus
     * @throws \Exception
     */
    public function getTransactionStatus($paymentRef) {

        if(!$this->accessToken)

        throw new \InvalidArgumentException("accessToken should be specified");


        $request = new \Http_Request2($this->transactionUrl(). "/" . $paymentRef);
        $url = $request->getUrl();
        
        $headers = array(
            // Request headers
            'Authorization' => 'Bearer '. $this->accessToken->getAccessToken(),
            'X-Target-Environment' => $this->environment,
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
        );
        
        $request->setHeader($headers);
        
        $parameters = array(
            // Request parameters
        );
        
        $url->setQueryVariables($parameters);
        
        $request->setMethod(\HTTP_Request2::METHOD_GET);
        
        // Request body
        //$request->setBody("{body}");
        
        try
        {
            $response = $request->send();
            return TransactionStatus::create(json_decode($response->getBody()));
        }
        catch (HttpException $ex)
        {
            echo $ex;
        }
        
    }
}