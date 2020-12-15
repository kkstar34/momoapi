<?php
/**
  * User: Kouyate Karim
 * Date: 15/12/2020
 * Time: 09:04
 */

namespace Kouyatekarim\Momoapi\Responses;


class ApiKey extends Response
{
    /**
     * @var string $apiKey
     */
    protected $apiKey;

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}