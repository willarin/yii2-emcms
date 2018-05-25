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
}
