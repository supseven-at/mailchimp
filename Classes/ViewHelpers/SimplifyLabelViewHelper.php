<?php

namespace Sup7even\Mailchimp\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class SimplifyLabelViewHelper extends AbstractViewHelper
{

    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('label', 'string', 'label', false, '');
        $this->registerArgument('toLowerCase', 'bool', 'should it be lowered', false, false);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $label = $arguments['label'] ?: $renderChildrenClosure();

        $label = str_replace(['Ö', 'Ü', 'Ä', 'ö', 'ü', 'ä', 'ß'], ['Oe', 'Ue', 'Ae', 'oe', 'ue', 'ae', 'ss'], $label);
        $filter = preg_replace('/[^a-zA-Z0-9]/', '', $label);
        if ($arguments['toLowerCase']) {
            $filter = mb_strtolower($filter);
        }
        return $filter;
    }
}
