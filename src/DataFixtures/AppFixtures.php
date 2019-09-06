<?php

namespace App\DataFixtures;

use App\DataFixtures\FakerProvider\DataProvider;
use App\Entity\Mentor;
use App\Entity\Tag;
use App\Entity\User;
use App\Enum\LevelEnum;
use App\Enum\RoleEnum;
use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $generator;
    private $encoder;
    private $manager;
    private $slugger;

    private $listUsers;
    private $listMentors;
    private $listTags;
    private $listQuizzes;

    public function __construct(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Slugger $slugger)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->generator = Faker\Factory::create('fr_FR');
        $this->generator->addProvider(new DataProvider($this->generator));
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $this->listUsers = $this->createUsers();
        dump('===============================');
        $this->listMentors = $this->createMentors();
        dump('===============================');
        $this->manageMentors();
        dump('===============================');
        $this->listTags = $this->createAllTags();
        dump('===============================');
        $this->listQuizzes = $this->createQuizzes();
        dump('===============================');
    }

    public function createUsers()
    {
        $listUsers = array();

        // Création de 20 étudiants
        for ( $i=0 ; $i<5 ; $i++ ) {

            $randDate = $this->generator->dateTimeBetween('-5 years', 'now');

            $user = new User();
            $user->setEmail('user' . $i . '@formation.fr')
                ->setFirstName($this->generator->firstName)
                ->setLastName($this->generator->lastName)
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setIsActive($this->generator->boolean)
                ->setPhoneNumber('0623009985')
                ->setCreatedAt($randDate)
                ->setUpdatedAt($this->generator->dateTimeBetween($randDate, 'now'))
                ->setSlug($this->slugger->slugify($user->getFirstName() . ' ' . $user->getLastName()));

            $this->manager->persist($user);
            $this->manager->flush();

            $listUsers[] = $user;

            // Affichage sur le terminal
            dump('Creation de l\'étudiant n°' . ($i+1) . ' sous le nom de ' . $user->getFirstName() . ' ' . $user->getLastName() . '.');
        }

        return $listUsers;
    }

    public function createMentors()
    {
        $listMentors = array();

        // Choisir 5 Users aléatoirement pour les passer Mentor
        for ( $i=0 ; $i<1 ; $i++ ) {
            $rand = array_rand($this->listUsers);
            $randUser = $this->listUsers[$rand];

            $randUser->setRoles(RoleEnum::ROLE_TEACHER[0])
                ->setIsActive(true);

            $this->manager->persist($randUser);
            $this->manager->flush();

            $listMentors[] = $randUser;

            // Affichage sur le termninal
            dump($randUser->getFirstName() . ' ' . $randUser->getLastName() . ' est maintenant active et un mentor.');

            // Suppression du Mentor dans la liste des Users
            array_splice($this->listUsers, $rand, 1);
        }

        return $listMentors;
    }

    public function manageMentors()
    {
        // Attribution des mentors pour chaque étudiants
        for ( $i=0 ; $i<count($this->listUsers) ; $i++ ) {

            // Random sur la liste des mentors pour les attribuer par le suite
            $rand = array_rand($this->listMentors);
            $randMentor = $this->listMentors[$rand];

            $mentor = new Mentor();
            $mentor->setStudent($this->listUsers[$i])
                ->setMentor($randMentor);

            $this->manager->persist($mentor);
            $this->manager->flush();

            dump($mentor->getStudent()->getFirstName() . ' ' . $mentor->getStudent()->getLastName() . ' a pour mentor : ' . $mentor->getMentor()->getFirstName() . ' ' . $mentor->getMentor()->getLastName());

        }
    }

    public function createAllTags()
    {
        $listTags = array();

        // Création de la liste des Tags
        for ( $i=0 ; $i<9 ; $i++) {

            $tag = new Tag();
            $tag->setName($this->generator->unique()->tagName())
                ->setTextColor($this->generator->hexColor)
                ->setBackgroundColor($this->generator->hexColor);

            $this->manager->persist($tag);
            $this->manager->flush();

            $listTags [] = $tag;

            dump('Création du tag : ' . $tag->getName());
        }

        return $listTags;
    }

    public function createQuizzes()
    {
        $listQuizzes = array();

        // Création des questionnaires => 20
        for ( $i=0 ; $i>20 ; $i++ ) {
            // random sur la difficultée
            $level = LevelEnum::get_class_constants();
            dump($level);
        }
    }

}
