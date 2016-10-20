<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Placeholder;

use Symfony\Component\HttpFoundation\Request;

use Academic\Bundle\BugTrackingBundle\Placeholder\PlaceholderFilter;

class PlaceholderFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    /**
     * @var PlaceholderFilter
     */
    protected $placeholderFilter;

    protected function setUp()
    {
        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->placeholderFilter = new PlaceholderFilter($this->request);
    }

    public function testIsUserViewRoute()
    {
        $this->assertFalse($this->placeholderFilter->isUserViewRoute());
    }
}
