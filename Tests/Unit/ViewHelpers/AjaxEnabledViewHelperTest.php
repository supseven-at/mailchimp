<?php

namespace Sup7even\Mailchimp\Tests\Unit\ViewHelpers;

use Sup7even\Mailchimp\ViewHelpers\AjaxEnabledViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;

class AjaxEnabledViewHelperTest extends BaseTestCase
{

    /**
     * @test
     * @dataProvider ajaxEnabledWorksDataProvider
     */
    public function ajaxEnabledWorks()
    {
        $this->markTestSkipped('not functional');
        $viewHelper = $this->getAccessibleMock(AjaxEnabledViewHelper::class, ['renderChildren'], [], '', false);
        $viewHelper->method('renderChildren')->willReturn($code);

        $pageRender = $this->prophesize(PageRenderer::class);
        $pageRender->addFooterData($code)->shouldBeCalled();

        $viewHelper->_set('pageRenderer', $pageRender->reveal());

        $viewHelper->_call('render');
    }

    public function ajaxEnabledWorksDataProvider()
    {
        return [
            'nothing enabled' => [
                ['0', false, false]
            ]
        ];
    }
}
