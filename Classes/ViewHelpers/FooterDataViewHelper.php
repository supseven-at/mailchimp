<?php

namespace Sup7\Mailchimp\ViewHelpers;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class FooterDataViewHelper
 */
class FooterDataViewHelper extends AbstractViewHelper
{

    /**
     * Renders footer data
     */
    public function render()
    {
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addFooterData($this->renderChildren());
    }
}