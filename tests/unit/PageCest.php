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

    public function CreateValidPage(UnitTester $I)
    {
        $pageData = $I->getConfig('validPage');
        $page = new Page();
        $page->setScenario('create');
        $page->setAttributes($pageData);
        $I->assertTrue($page->save());
    }

    public function CreateInvalidPage(UnitTester $I)
    {
        $pageData = $I->getConfig('invalidPage');
        $page = new Page();
        $page->setScenario('create');
        $page->setAttributes($pageData);
        $I->assertFalse($page->save());
    }
}
