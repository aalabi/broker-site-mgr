<?php

/**
 * InterfaceWallet
 *
 * An interface for work with with wallet
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => March 2023
 * @link        alabiansolutions.com
 */

interface InterfaceWallet
{
    /** @var string values for orders*/
    public const ORDER_VALUE = ["ascending", "descending"];

    /**
     * for posting of debit transaction into a wallet
     *
     * @param float $amount the amount to be debit for the transaction
     * @param string $narration the description of transaction
     * @param DateTime|null $transactionDate the date of the transaction was posted
     * @return void
     */
    public function debit(float $amount, string $narration, DateTime|null $transactionDate = null);

    /**
     * for posting of credit transaction into a wallet
     *
     * @param float $amount the amount to be credit for the transaction
     * @param string $narration the description of transaction
     * @param DateTime|null $transactionDate the date of the transaction was posted
     * @return void
     */
    public function credit(float $amount, string $narration, DateTime|null$transactionDate = null);

    /**
     * for getting the balance of a wallet
     *
     * @param DateTime|null $endDate the date at which the balance should be taken
     * @return float|null the balance of the account at the indicated date
     */
    public function balance(DateTime|null $endDate = null):float|null;

    /**
     * for getting the transaction(s) of a wallet
     *
     * @param string $order the ordering of the statement either ascending or descending
     * @param DateTime|null $startDate the start date for the statement
     * @param DateTime|null $endDate the end date for the statement
     * @return array
     */
    public function statement(string $order = InterfaceWallet::ORDER_VALUE[1], DateTime|null $startDate = null, DateTime|null $endDate = null):array;
}
