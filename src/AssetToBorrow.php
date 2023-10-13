<?php

namespace App;

use Exception;

abstract class AssetToBorrow
{
    private $currentBorrower = null;

    public function __construct(private $timeProvider)
    {
    }

    /**
     * @throws AssetToBorrowException
     */
    public function setCurrentBorrower($user): void
    {
        if ($this->currentBorrower != null) {
            throw new AssetToBorrowException("You must have no current user borrowing this asset to be able to change its emprunt owner.");
        }
        $this->currentBorrower = $user;
    }

    /**
     * @return void
     */
    public function removeCurrentBorrower(): void
    {
        $this->currentBorrower = null;
    }
}

