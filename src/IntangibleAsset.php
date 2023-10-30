<?php

namespace App;

class IntangibleAsset extends Asset
{
    public function tryToBorrowBy(Borrower $borrower): void
    {
        if ($this->currentBorrower != null && !$this->isExpired()) {
            throw new AssetException(
                "You cannot borrow this intengible item, it is already borrowed by an other person."
            );
        }
        parent::tryToBorrowBy($borrower);
        (new IntangibleAssetLink($this, $borrower))->sendEmail();
    }

    protected function getBorrowingDaysDuration(): int
    {
        return 2 * 7;
    }
}