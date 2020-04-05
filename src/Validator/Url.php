<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Url extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Aucune url disponible dans la balise embed renseignée';

    public function validatedBy()
    {
        return parent::validatedBy();
    }
}
