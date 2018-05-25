<?php

use almeyda\emcms\models\Page;

class PageCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    /**
     * Test that checks creation of page with valid input data
     * @param UnitTester $I
     */
    public function CreateValidPage(UnitTester $I)
    {
        $pageData = $I->getConfig('validPage');
        $page = new Page();
        $page->setScenario('create');
        $page->setAttributes($pageData);
        $I->assertTrue($page->save());
    }

    /**
     * Test that checks not creation of page with not valid input data
     * @param UnitTester $I
     */
    public function CreateInvalidPage(UnitTester $I)
    {
        $pageData = $I->getConfig('invalidPage');
        $page = new Page();
        $page->setScenario('create');
        $page->setAttributes($pageData);
        $I->assertFalse($page->save());
    }

    /**
     * Test that checks deleting Page and associated ListingPage record
     * @param UnitTester $I
     */
    public function DeletePage(UnitTester $I)
    {
        $pageConfig = $I->getConfig('pageToDelete');
        $I->seeRecord('almeyda\emcms\models\Page', ['id' => $pageConfig['id']]);
        $I->seeRecord('almeyda\emcms\models\ListingPage', ['pageId' => $pageConfig['id']]);
        $page = new Page();
        $page->id = $pageConfig['id'];
        $page->refresh();
        \Yii::$app->request->setQueryParams(['listingId' => $pageConfig['listingId']]);
        $page->delete();
        $I->dontSeeRecord('almeyda\emcms\models\Page', ['id' => $pageConfig['id']]);
        $I->dontSeeRecord('almeyda\emcms\models\ListingPage', ['pageId' => $pageConfig['id']]);
    }
}
