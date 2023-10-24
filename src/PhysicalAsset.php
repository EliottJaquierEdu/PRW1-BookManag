<?php

namespace App;

class PhysicalAsset extends Asset
{
    public function onBorrowBy(Borrower $borrower): void
    {
        if($this->currentBorrower !== null) throw new AssetException("You cannot borrow this physical item, it is already borrowed by an other person.");
        parent::onBorrowBy($borrower);
    }

    public function onSendBackBy(Borrower $borrower): void
    {
        if($borrower == null) throw new AssetException("The borrower cannot be null.");
        if($this->currentBorrower !== $borrower) throw new AssetException("You cannot send back this item, you don't have it.");
        $this->currentBorrower = null;
    }

    protected function getBorrowingDaysDuration(): int
    {
        return 4*7;
    }
}