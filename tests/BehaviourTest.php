<?php
declare(strict_types=1);

use App\AssetException;
use App\Borrower;
use App\BorrowerException;
use App\IntangibleAsset;
use App\NotFoundException;
use App\PhysicalAsset;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;


class BehaviourTest extends TestCase
{
    //TODO : Split this test in multiple test
    public function testFullSuite()
    {
        global $now;
        $now = new DateTimeImmutable();

        $nowCallback = function () {
            global $now;
            return $now;
        };

        $user1 = new Borrower("Eliott", "eliott.jaquier@gmail.com");
        $user2 = new Borrower("Jean", "jean@gmail.com");

        $physicalAsset1 = new PhysicalAsset($nowCallback);
        $physicalAsset2 = new PhysicalAsset($nowCallback);
        $physicalAsset3 = new PhysicalAsset($nowCallback);

        $intangibleAsset1 = new IntangibleAsset($nowCallback);
        $intangibleAsset2 = new IntangibleAsset($nowCallback);
        $intangibleAsset3 = new IntangibleAsset($nowCallback);

        $user1->borrow($physicalAsset1);

        //Asset not expired
        assertFalse($physicalAsset1->isExpired());
        //User have one unexpired item
        assertEquals(1, count($user1->getNotExpiredBorrowedItems()));
        //The asset is the one borrowed
        assertEquals($physicalAsset1, $user1->getNotExpiredBorrowedItems()[0]);

        //User cannot borrow already borrowed asset by other
        try {
            $user2->borrow($physicalAsset1);
            $this->fail("Exception not thrown");
        } catch (AssetException $e) {
        }

        //User cannot borrow already borrowed asset by him
        try {
            $user1->borrow($physicalAsset1);
            $this->fail("Exception not thrown");
        } catch (BorrowerException $e) {
        }

        $user1->borrow($intangibleAsset1);

        //User have two unexpired items
        assertEquals(2, count($user1->getNotExpiredBorrowedItems()));
        //User received one link
        assertEquals(1, count($user1->getReceivedLinks()));
        //The asset is not expired
        assertFalse($intangibleAsset1->isExpired());

        $link1 = $user1->getReceivedLinks()[0];
        //Consult without 404
        $link1->consult();

        //User cannot borrow more than 5 items
        try {
            $user1->borrow($physicalAsset2);
            $user1->borrow($physicalAsset3);
            $user1->borrow($intangibleAsset2);
            $user1->borrow($intangibleAsset3);
            $this->fail("Exception not thrown");
        } catch (BorrowerException $e) {
        }

        //Still 5 unexpired items
        assertEquals(5, count($user1->getNotExpiredBorrowedItems()));
        $user1->sendBack($physicalAsset2);

        //User can borrow again
        $user1->borrow($physicalAsset2);
        $user1->sendBack($physicalAsset2);
        //Other user can borrow
        $user2->borrow($physicalAsset2);

        //Borrower have 4 unexpired items
        assertEquals(4, count($user1->getNotExpiredBorrowedItems()));

        //Move 2 weeks later
        $now = $now->modify('+2 week 1 day');

        //Physical is not expired
        assertFalse($physicalAsset1->isExpired());
        //IntangibleAsset1 is expired
        assertTrue($intangibleAsset1->isExpired());
        //Link return 404
        try {
            $link1->consult();
            $this->fail("Exception not thrown");
        } catch (NotFoundException $e) {
        }

        //Borrower have 2 unexpired items (only physical remain)
        assertEquals(2, count($user1->getNotExpiredBorrowedItems()));

        //Other user can borrow intangibleAsset1 cause it's auto expired
        $user2->borrow($intangibleAsset1);

        //Asset not expired
        assertFalse($intangibleAsset1->isExpired());

        //First link continue returning 404
        try {
            $link1->consult();
            $this->fail("Exception not thrown");
        } catch (NotFoundException $e) {
        }

        //User have a new link
        assertEquals(1, count($user2->getReceivedLinks()));
        $link1FromUser2 = $user2->getReceivedLinks()[0];
        //Consult without 404
        $link1FromUser2->consult();

        //Move 2 weeks later
        $now = $now->modify('+2 week');

        //Physical is expired
        assertTrue($physicalAsset1->isExpired());

        //Borrower have 1 unexpired items
        assertEquals(1, count($user1->getNotExpiredBorrowedItems()));
    }
}
