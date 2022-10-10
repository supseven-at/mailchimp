<?php

namespace Sup7even\Mailchimp\ViewHelpers;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Class AjaxEnabledViewHelper
 */
class AjaxEnabledViewHelper extends AbstractConditionViewHelper
{
    /**
     */
    public function initializeArguments()
    {
        $this->registerArgument('isEnabled', 'bool', 'Is enabled');
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

    /**
     * @return mixed
     */
    public function render()
    {
        if (static::evaluateCondition($this->arguments)) {
            return $this->renderThenChild();
        }
        return $this->renderElseChild();
    }
}
