<?php

namespace Academic\Bundle\BugTrackingBundle\Placeholder;

use Symfony\Component\HttpFoundation\Request;

class PlaceholderFilter
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Checks if current route is user profile
     * @return bool
     */
    public function isUserViewRoute()
    {
        return $this->request->get('_route') === 'oro_user_view';
    }
}
