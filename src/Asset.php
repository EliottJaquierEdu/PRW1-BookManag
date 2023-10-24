<?php

namespace App;

abstract class Asset
{
    protected $expirationDate = null;
    protected $currentBorrower = null;

    public function __construct(private $nowCallback){}

    /**
     * Get the actual borrower or null if the asset is not borrowed
     */
    public function getCurrentBorrower(): ?Borrower
    {
        return $this->currentBorrower;
    }

    /**
     * Get the number of days the asset can be borrowed
     */
    protected abstract function getBorrowingDaysDuration(): int;

    /**
     * Check if the expiration date is passed
     */
    public function isExpired(): bool
    {
        return $this->expirationDate < call_user_func($this->nowCallback);
    }

    /**
     * Set borrowing information
     * @throws AssetException
     */
    public function onBorrowBy(Borrower $borrower): void
    {
        if($borrower == null) throw new AssetException("The borrower cannot be null.");
        $this->currentBorrower = $borrower;
        $this->expirationDate = call_user_func($this->nowCallback)->modify('+'.$this->getBorrowingDaysDuration().' day');
    }
}