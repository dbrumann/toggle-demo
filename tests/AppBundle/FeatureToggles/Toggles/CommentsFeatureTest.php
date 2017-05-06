<?php

namespace Tests\AppBundle\FeatureToggle\Toggles;

use AppBundle\FeatureToggle\Toggles\CommentsFeature;

class CommentsFeatureTest extends \PHPUnit_Framework_TestCase
{
    public function testFeatureIsDisabled()
    {
        $toggle = new CommentsFeature(false);

        $this->assertFalse($toggle->isActive());
    }

    public function testFeatureIsEnabled()
    {
        $toggle = new CommentsFeature(true);

        $this->assertTrue($toggle->isActive());
    }
}
