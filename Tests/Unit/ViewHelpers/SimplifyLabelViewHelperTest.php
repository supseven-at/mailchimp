<?php

namespace Sup7\Mailchimp\Tests\Unit\ViewHelpers;

use Sup7\Mailchimp\ViewHelpers\SimplifyLabelViewHelper;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class SimplifyLabelViewHelperTest extends UnitTestCase
{

    /**
     * @param string $input
     * @param string $expected
     * @dataProvider checkViewHelperOutputDataProvider
     * @test
     */
    public function checkViewHelperOutput($input, $expected) {
        $viewHelper = new SimplifyLabelViewHelper();

        $this->assertEquals($expected, $viewHelper->render($input));
    }

    /**
     * @return array
     */
    public function checkViewHelperOutputDataProvider() {
        return [
            'simply text' => [
                'A normal Label', 'AnormalLabel'
            ],
            'umlauts' => [
                'Ein Jäger überfällt ein ', 'EinJaegerueberfaelltein'
            ],
        ];
    }

}