<?php

namespace App\Security\Voter;

use App\Entity\Quiz;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class EditQuiz implements VoterInterface
{
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        // Condition pour que les votes puissent avoir lieux :

        // Si mon vote reçoit autre chose qu'une Lesson, alors il n'est pas interessé
        if (!$subject instanceof Quiz) {
            return self::ACCESS_ABSTAIN;
        }

        // L'attribut
        if (!in_array('EDIT', $attributes)) {
            return self::ACCESS_ABSTAIN;
        }

        // Le vote peux avoir lieux
        $user = $token->getUser();

        // Pas d'utiliseateur = pas le droit
        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        // Si le connecté n'est pas l'auteur alors on interdit l'access
        if ($user !== $subject->getAuthor()) {
            return self::ACCESS_DENIED;
        }

        // Tout est OK, j'autorise l'access
        return self::ACCESS_GRANTED;
    }
}
