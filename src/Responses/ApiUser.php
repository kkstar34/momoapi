<?php
/**
 * User: Kouyate Karim
 * Date: 15/12/2020
 * Time: 09:04
 */
namespace Kouyatekarim\Momoapi\Responses;


class ApiUser extends Response
{
    /**
     * @var string $providerCallbackHost
     */
    protected $providerCallbackHost;

    /**
     * @var string $targetEnvironment
     */
    protected $targetEnvironment;

    /**
     * @return string
     */
    public function getProviderCallbackHost()
    {
        return $this->providerCallbackHost;
    }

    /**
     * @return string
     */
    public function getTargetEnvironment()
    {
        return $this->targetEnvironment;
    }
}