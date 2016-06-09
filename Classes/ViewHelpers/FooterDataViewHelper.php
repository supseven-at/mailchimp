<?php

namespace Sup7even\Mailchimp\ViewHelpers;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class FooterDataViewHelper
 */
class FooterDataViewHelper extends AbstractViewHelper
{

    /** @var PageRenderer */
    protected $pageRenderer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
    }

    /**
     * Renders footer data
     */
    public function render()
    {
        $this->pageRenderer->addFooterData($this->renderChildren());
    }
}
