<?php

namespace Sup7even\Mailchimp\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class SimplifyLabelViewHelper extends AbstractViewHelper
{

    /**
     * @param string $label
     * @param bool $toLowerCase
     * @return string
     */
    public function render($label = '', $toLowerCase = false)
    {
        $label = $label ? $label : $this->renderChildren();

        $label = str_replace(['Ö', 'Ü', 'Ä', 'ö', 'ü', 'ä', 'ß'], ['Oe', 'Ue', 'Ae', 'oe', 'ue', 'ae', 'ss'], $label);
        $filter = preg_replace('/[^a-zA-Z0-9]/', '', $label);
        if ($toLowerCase) {
            $filter = strtolower($filter);
        }

        return $filter;
    }
}
