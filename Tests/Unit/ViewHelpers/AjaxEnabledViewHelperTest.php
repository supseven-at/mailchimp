<?php

namespace Sup7even\Mailchimp\Tests\Unit\ViewHelpers;

use Sup7even\Mailchimp\ViewHelpers\AjaxEnabledViewHelper;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class AjaxEnabledViewHelperTest extends UnitTestCase
{

    /**
     * @test
     * @dataProvider ajaxEnabledWorksDataProvider
     */
    public function ajaxEnabledWorks($setting, $extIsLoaded, $expeted)
    {
        $viewHelper = $this->getAccessibleMock(AjaxEnabledViewHelper::class, ['renderChildren'], [], '', false);
        $viewHelper->method('renderChildren')->willReturn($code);

        $pageRender = $this->prophesize(PageRenderer::class);
        $pageRender->addFooterData($code)->shouldBeCalled();

        $viewHelper->_set('pageRenderer', $pageRender->reveal());

        $viewHelper->_call('render');
    }

    protected function ajaxEnabledWorksDataProvider()
    {
        return [
            'nothing enabled' => [
                ['0', false, false]
            ]
        ];
    }
}
