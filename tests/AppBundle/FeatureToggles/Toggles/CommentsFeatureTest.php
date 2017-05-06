<?php

namespace Tests\AppBundle\FeatureToggle\Toggles;

use AppBundle\FeatureToggle\Toggles\CommentsFeature;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CommentsFeatureTest extends \PHPUnit_Framework_TestCase
{
    public function testFeatureIsDisabled()
    {
        $authChecker = $this->getMock(AuthorizationCheckerInterface::class);
        $toggle = new CommentsFeature($authChecker, false, false);

        $this->assertFalse($toggle->isActive());
    }

    public function testFeatureIsEnabled()
    {
        $authChecker = $this->getMock(AuthorizationCheckerInterface::class);
        $toggle = new CommentsFeature($authChecker, true, false);

        $this->assertTrue($toggle->isActive());
    }

    public function testFeatureIsEnabledAndRoleAdminIsGranted()
    {
        $authChecker = $this->getMock(AuthorizationCheckerInterface::class);
        $toggle = new CommentsFeature($authChecker, true, true);

        $authChecker
            ->expects($this->once())
            ->method('isGranted')
            ->with('ROLE_ADMIN')
            ->willReturn(true);

        $this->assertTrue($toggle->isActive());
    }

    public function testFeatureIsEnabledAndRoleAdminIsNotGranted()
    {
        $authChecker = $this->getMock(AuthorizationCheckerInterface::class);
        $toggle = new CommentsFeature($authChecker, true, true);

        $authChecker
            ->expects($this->once())
            ->method('isGranted')
            ->with('ROLE_ADMIN')
            ->willReturn(false);

        $this->assertFalse($toggle->isActive());
    }
}
