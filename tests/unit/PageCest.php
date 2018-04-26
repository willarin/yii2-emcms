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

    // tests
    public function CreateValidPage(UnitTester $I)
    {
        $pageData = $I->getConfig('validPage');
        $page = new Page();
        $page->setAttributes($pageData);
        $page->setScenario('create');
        $I->assertTrue($page->save());
    }
}
