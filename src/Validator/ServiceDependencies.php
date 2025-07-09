<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ServiceDependencies extends Constraint
{
    public string $messageDomaine = 'Le domaine sélectionné ne correspond pas au service.';
    public string $messageLieu = 'Le lieu sélectionné ne correspond pas au service.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
