<?php

namespace AppBundle\FeatureToggle;

interface ToggleInterface
{
    public function isActive(): bool;
}
