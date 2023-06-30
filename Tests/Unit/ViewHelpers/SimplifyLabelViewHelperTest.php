<?php

namespace Sup7even\Mailchimp\Tests\Unit\ViewHelpers;

use Sup7even\Mailchimp\ViewHelpers\SimplifyLabelViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class SimplifyLabelViewHelperTest extends UnitTestCase
{
    /**
     * @param string $input
     * @param string $expected
     * @dataProvider checkViewHelperOutputDataProvider
     * @test
     */
    public function checkViewHelperOutput($input, $expected): void
    {
        $viewHelper = new SimplifyLabelViewHelper();

        self::assertEquals($expected, $viewHelper->render($input));
    }

    /**
     * @return array
     */
    public function checkViewHelperOutputDataProvider()
    {
        return [
            'simply text' => [
                'A normal Label', 'AnormalLabel',
            ],
            'umlauts' => [
                'Ein Jäger überfällt ein ', 'EinJaegerueberfaelltein',
            ],
        ];
    }
}
