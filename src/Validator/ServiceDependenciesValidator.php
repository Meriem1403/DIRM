<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ServiceDependenciesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof User || !$value->getService()) {
            return;
        }

        $service = $value->getService();

        // Validation du domaine
        if ($value->getDomaine() && !$service->getDomaines()->contains($value->getDomaine())) {
            $this->context->buildViolation($constraint->messageDomaine)
                ->atPath('domaine')
                ->addViolation();
        }

        // Validation du lieu
        if ($value->getLieu() && !$service->getLieux()->contains($value->getLieu())) {
            $this->context->buildViolation($constraint->messageLieu)
                ->atPath('lieu')
                ->addViolation();
        }
    }
}
