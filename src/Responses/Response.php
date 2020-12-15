<?php
/**
 * User: Kouyate Karim
 * Date: 15/12/2020
 * Time: 09:04
 */

namespace Kouyatekarim\Momoapi\Responses;


use Kouyatekarim\Momoapi\Traits\Configurations;

abstract class Response
{
    use Configurations;

    public function __construct($options)
    {
        $this->setOptions($options);
    }
}