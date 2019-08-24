<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $generator;
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->generator = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@formation.fr')
            ->setFirstName($this->generator->firstName)
            ->setLastName($this->generator->lastName)
            ->setPassword($this->encoder->encodePassword($user, 'password'));
        
        $manager->persist($user);

        $manager->flush();
    }
}
