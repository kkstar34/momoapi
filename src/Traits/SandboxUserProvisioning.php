<?php
/**
* User: Kouyate Karim
 * Date: 15/12/2020
 * Time: 09:04
 */

namespace Kouyatekarim\Momoapi\Traits;
use Kouyatekarim\Momoapi\Responses\ApiKey;
use Kouyatekarim\Momoapi\Responses\ApiUser;
require_once $path = base_path('vendor/pear/http_request2/HTTP/Request2.php');


/**
 * Trait SandboxUserProvisioning
 * @package Kouyatekarim\Momoapi\Traits
 */
trait SandboxUserProvisioning
{
    /**
     * Create an api user
     *
     * @return string
     * @throws \Exception
     */
    public function createApiUser() {
        $request = new \Http_Request2(self::$baseUrl . self::API_USER_URI);
        $url = $request->getUrl();
        
        $headers = array(
            // Request headers
            'X-Reference-Id' => $this->xReferenceId,
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
        );
        
        $request->setHeader($headers);
        
        $parameters = array(
            // Request parameters
        );
        
        $url->setQueryVariables($parameters);
        
        $request->setMethod(\HTTP_Request2::METHOD_POST);
        
        // Request body
        
        $body = array(
            'providerCallbackHost' => $this->callbackHost,
            );

            $body = json_encode($body);
            $request->setBody($body);
        
        try
        {
            $response = $request->send();
     
         
            echo $response->getBody();
        }
        catch (HttpException $ex)
        {
            echo $ex;
        }
    }

    /**
     * Validate api user
     *
     * @return ApiUser
     * @throws \Exception
     */
    public function getApiUser() {

            $request = new \Http_Request2(self::$baseUrl . self::API_USER_URI . "/" . $this->xReferenceId);
            $url = $request->getUrl();

            $headers = array(
                // Request headers
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            );

            $request->setHeader($headers);

            $parameters = array(
                // Request parameters
            );

            $url->setQueryVariables($parameters);

            $request->setMethod(\HTTP_Request2::METHOD_GET);

            // Request body
           // $request->setBody("{body}");

            try
            {
                $response = $request->send();
                return ApiUser::create(json_decode($response->getBody()));
            }
            catch (HttpException $ex)
            {
                echo $ex;
            }

    }

    /**
     * Create api key
     *
     * @return ApiKey
     * @throws \Exception
     */
    public function createApiKey() {
        $request = new \Http_Request2(self::$baseUrl . self::API_USER_URI . "/" . $this->xReferenceId. "/apikey");
            $url = $request->getUrl();

            $headers = array(
                // Request headers
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            );

            $request->setHeader($headers);

            $parameters = array(
                // Request parameters
            );

            $url->setQueryVariables($parameters);

            $request->setMethod(\HTTP_Request2::METHOD_POST);

            // Request body
            //$request->setBody("{body}");

            try
            {
                $response = $request->send();
                
                return ApiKey::create(json_decode($response->getBody()));
            }
            catch (HttpException $ex)
            {
                echo $ex;
            }

    }
}