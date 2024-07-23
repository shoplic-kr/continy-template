<?php

namespace ShoplicKr\WpTemplate\Tests;

use WP_UnitTestCase;
use ShoplicKr\WpTemplate\Template;

class TestTemplate extends WP_UnitTestCase
{
    public function testTemplate()
    {
        $output = Template::template(
            [__DIR__ . '/test-plugin/templates'],
            'template-foo',
            [
                'foo' => 'Foo',
                'bar' => 'Bar',
            ],
            true
        );

        $output = preg_replace('/>\s+</', '><', $output);
        $output = preg_replace("/\s{2,}|\n/", ' ', $output);
        $output = trim($output);

        $expected = '<div>Header - Foo, Bar <p>Template - Foo, Bar</p></div>';

        $this->assertEquals($expected, $output);
    }
}
