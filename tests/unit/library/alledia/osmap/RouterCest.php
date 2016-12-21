<?php
namespace library\alledia\osmap;

use \UnitTester;
use AspectMock\Test;
use Codeception\Util\Stub;

class RouterCest
{
    protected $container;

    public function _before(UnitTester $I)
    {

    }

    public function _after(UnitTester $I)
    {
        Test::clean();
    }

    public function tryConvertRelativeUriToFullUrlWithSlashOnBaseAndUri(UnitTester $I)
    {
        $base     = 'http://example.com/test/';
        $input    = '/images/test/myimage1.png';
        $expected = 'http://example.com/test/images/test/myimage1.png';

        Test::double('\Alledia\OSMap\Router', ['getFrontendBase' => $base]);
        $router = new \Alledia\OSMap\Router;

        $I->assertEquals($expected, $router->convertRelativeUriToFullUri($input));
    }

    public function tryConvertRelativeUriToFullUrlWithSlashOnBaseButNoneOnUri(UnitTester $I)
    {
        $base     = 'http://example.com/test/';
        $input    = 'images/test/myimage1.png';
        $expected = 'http://example.com/test/images/test/myimage1.png';

        Test::double('\Alledia\OSMap\Router', ['getFrontendBase' => $base]);
        $router = new \Alledia\OSMap\Router;

        $I->assertEquals($expected, $router->convertRelativeUriToFullUri($input));
    }

    public function tryConvertRelativeUriToFullUrlWithSlashOnUriButNoneOnBase(UnitTester $I)
    {
        $base     = 'http://example.com/test';
        $input    = '/images/test/myimage1.png';
        $expected = 'http://example.com/test/images/test/myimage1.png';

        Test::double('\Alledia\OSMap\Router', ['getFrontendBase' => $base]);
        $router = new \Alledia\OSMap\Router;

        $I->assertEquals($expected, $router->convertRelativeUriToFullUri($input));
    }

    public function tryConvertRelativeUriToFullUrlWithoutSlashOnBaseAndUri(UnitTester $I)
    {
        $base     = 'http://example.com/test';
        $input    = 'images/test/myimage1.png';
        $expected = 'http://example.com/test/images/test/myimage1.png';

        Test::double('\Alledia\OSMap\Router', ['getFrontendBase' => $base]);
        $router = new \Alledia\OSMap\Router;

        $I->assertEquals($expected, $router->convertRelativeUriToFullUri($input));
    }
}
