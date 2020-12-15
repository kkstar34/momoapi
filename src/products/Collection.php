<?php
/**
 * 
 * User: Kouyate Karim
 * Date: 15/12/2020
 * Time: 09:04
 */
namespace Kouyatekarim\Momoapi\Products;
use Kouyatekarim\Momoapi\Responses\TransactionStatus;

/**
 * Class Collection
 * 
 */
class Collection extends Product
{
    const REQUEST_TO_PAY_URI = "/v1_0/requesttopay";

    const PRE_APPROVAL_URI = "/v1_0/preapproval";

    /**
     * @var bool $preApproval
     */
    protected $preApproval = false;

    protected function getProductBaseUrl() {
        return self::$baseUrl . "/collection";
    }

    protected function transactionUrl() {
        return $this->getProductBaseUrl() . self::REQUEST_TO_PAY_URI;
    }

    /**
     * Start request to pay transaction
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
    public function requestToPay($externalId, $partyId, $amount, $currency, $payerMessage = '', $payeeNote = '') {
        return $this->transact($externalId, $partyId, $amount, $currency, $payerMessage, $payeeNote);
    }

    /**
     * Get request to pay transaction status
     *
     * @param $financialTransactionId
     * @return TransactionStatus
     * @throws \Exception
     */
    public function getRequestToPayStatus($financialTransactionId) {
        return $this->getTransactionStatus($financialTransactionId);
    }
}