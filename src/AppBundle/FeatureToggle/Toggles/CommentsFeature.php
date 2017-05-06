<?php

namespace AppBundle\FeatureToggle\Toggles;

use AppBundle\FeatureToggle\ToggleInterface;

class CommentsFeature implements ToggleInterface
{
    private $isEnabled;

    public function __construct(bool $isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    public function isActive(): bool
    {
        return $this->isEnabled;
    }
}
