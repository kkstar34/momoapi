<?php
/**
 * User: Kouyate Karim
 * Date: 15/12/2020
 * Time: 09:04
 */

namespace Kouyatekarim\Momoapi\Responses;


class TransactionStatus extends Response
{
    /**
     * @var string $amount
     */
    protected $amount;

    /**
     * @var string $currency
     */
    protected $currency;

    /**
     * @var string $financialTransactionId
     */
    protected $financialTransactionId;

    /**
     * @var string $externalId
     */
    protected $externalId;

    /**
     * @var array $payer
     */
    protected $payer = [];

    /**
     * @var string $payerMessage
     */
    protected $payerMessage;

    /**
     * @var string $payeeNote
     */
    protected $payeeNote;

    /**
     * @var string $status
     */
    protected $status;

    /**
     * @var array $reason
     */
    protected $reason = [];

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getFinancialTransactionId()
    {
        return $this->financialTransactionId;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return array
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * @return string
     */
    public function getPayerMessage()
    {
        return $this->payerMessage;
    }

    /**
     * @return string
     */
    public function getPayeeNote()
    {
        return $this->payeeNote;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getReason()
    {
        return $this->reason;
    }
}