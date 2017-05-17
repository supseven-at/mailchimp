<?php

namespace Sup7even\Mailchimp\ViewHelpers;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Class AjaxEnabledViewHelper
 */
class AjaxEnabledViewHelper extends AbstractConditionViewHelper
{

    /**
     * Initialize additional argument
     */
    public function initializeArguments()
    {
        $this->registerArgument('isEnabled', 'bool', 'Is enabled', false);
        parent::initializeArguments();
    }

    /**
     * @param array|null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        return (int)$arguments['isEnabled'] === 1 && ExtensionManagementUtility::isLoaded('typoscript_rendering');
    }
}
