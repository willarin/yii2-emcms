<?php

use almeyda\emcms\models\Listing;

class ListingCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    /**
     * Test that checks creation of correct listing data
     * @param UnitTester $I
     */
    public function CreateValidListing(UnitTester $I)
    {
        $listingData = $I->getConfig('validListing');
        $listing = new Listing();
        $listing->setScenario('create');
        $listing->setAttributes($listingData);
        $I->assertTrue($listing->save());
    }

    /**
     * Test that checks not creation of incorrect listing data
     * @param UnitTester $I
     */
    public function CreateInvalidListing(UnitTester $I)
    {
        $listingData = $I->getConfig('invalidListing');
        $listing = new Listing();
        $listing->setScenario('create');
        $listing->setAttributes($listingData);
        $I->assertFalse($listing->save());
    }

    /**
     * Test that checks retrieving of ids of pages of the listing
     * @param UnitTester $I
     */
    public function GetPageIds(UnitTester $I)
    {
        $pageConfig = $I->getConfig('pageToDelete');
        $listing = new Listing();
        $result = $listing->getPageIds($pageConfig['listingId']);
        $I->assertTrue(is_array($result));
    }

    /**
     * Test that checks deleting Listing, all Pages and ListingPage records associated with Pages
     * @param UnitTester $I
     */
    public function DeleteListing(UnitTester $I)
    {
        $pageConfig = $I->getConfig('pageToDelete');
        $I->seeRecord('almeyda\emcms\models\ListingPage', ['pageId' => $pageConfig['id']]);
        $listing = new Listing();
        $listing->id = $pageConfig['listingId'];
        $listing->refresh();
        $listing->delete();
        $I->dontSeeRecord('almeyda\emcms\models\ListingPage', ['pageId' => $pageConfig['id']]);
    }
}
