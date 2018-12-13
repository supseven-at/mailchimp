<?php

namespace Sup7even\Mailchimp\Tests\Unit\ViewHelpers;

use Sup7even\Mailchimp\ViewHelpers\SimplifyLabelViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;

class SimplifyLabelViewHelperTest extends BaseTestCase
{

    /**
     * @param string $input
     * @param string $expected
     * @dataProvider checkViewHelperOutputDataProvider
     * @test
     */
    public function checkViewHelperOutput($input, $expected)
    {
        $viewHelper = new SimplifyLabelViewHelper();
        $this->markTestSkipped('not functional');
        $this->assertEquals($expected, $viewHelper->render($input));
    }

    /**
     * @return array
     */
    public function checkViewHelperOutputDataProvider()
    {
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
