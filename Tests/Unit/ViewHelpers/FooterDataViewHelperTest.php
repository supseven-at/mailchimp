<?php

namespace Sup7even\Mailchimp\Tests\Unit\ViewHelpers;

use Sup7even\Mailchimp\ViewHelpers\FooterDataViewHelper;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class FooterDataViewHelperTest extends UnitTestCase
{

    /**
     * @test
     */
    public function footerDataIsAdded()
    {
        $code = '<script>alert(1)</script>';
        $viewHelper = $this->getAccessibleMock(FooterDataViewHelper::class, ['renderChildren'], [], '', false);
        $viewHelper->method('renderChildren')->willReturn($code);

        $pageRender = $this->prophesize(PageRenderer::class);
        $pageRender->addFooterData($code)->shouldBeCalled();

        $viewHelper->_set('pageRenderer', $pageRender->reveal());

        $viewHelper->_call('render');
    }
}
