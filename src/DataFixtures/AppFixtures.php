<?php

namespace App\DataFixtures;

use App\DataFixtures\FakerProvider\DataProvider;
use App\Entity\Mentor;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\Tag;
use App\Entity\User;
use App\Enum\LevelEnum;
use App\Enum\RoleEnum;
use App\Repository\QuestionRepository;
use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    //Paramatres pour les tests de la base de données:
    const NB_USERS = 20; // Nombre de création d'étudiants
    const NB_MENTORS = 5; // Nombre de mentors
    const NB_TAGS = 5; // Nombre de tags
    const NB_QUIZZES = 20; // Nombre de Questionnaires
    const NB_QUESTIONS_MIN = 5; // Nombre de questions minimum
    const NB_QUESTIONS_MAX = 10; // Nombre de questions maximum
    const NB_RESULTS = 100; // Nombre de réponses

    private $generator;
    private $encoder;
    private $manager;
    private $slugger;

    private $listUsers;
    private $listMentors;
    private $listTags;
    private $listQuizzes;
    private $listQuestions;

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
        $this->listQuestions = $this->createQuestions();
        dump('===============================');
        $this->createResultsQuizzes();

    }

    public function createUsers()
    {
        $listUsers = array();

        // Création des étudiants
        for ( $i=0 ; $i<self::NB_USERS ; $i++ ) {

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

        // Choisir Users aléatoirement pour les passer Mentor
        for ( $i=0 ; $i<self::NB_MENTORS ; $i++ ) {
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
        for ( $i=0 ; $i<self::NB_TAGS ; $i++) {

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

        // Création des questionnaires => 5
        for ( $i=0 ; $i<self::NB_QUIZZES ; $i++ ) {
            // random sur l'auteur
            $rand = array_rand($this->listMentors);
            $randAuthor = $this->listMentors[$rand];

            $date = $this->generator->dateTimeBetween('-1 year', 'now');

            $quiz = new Quiz();
            $quiz->setTitle($this->generator->sentence(6))
                ->setDescription($this->generator->text(100))
                ->setCreatedAt($date)
                ->setUpdatedAt($date)
                ->setAuthor($randAuthor);

            $this->manager->persist($quiz);
            $this->manager->flush();

            dump('Création de la question n°' . $i);
            $listQuizzes[] = $quiz;
        }

        // Ajout de Tags pour chaque questions
        foreach ($listQuizzes as $quiz) {
            shuffle($this->listTags);
            $rand = rand(1, 2);
            for ( $i=0 ; $i < $rand ; $i++ ) {
                $quiz->addTag($this->listTags[$i]);
            }
        }

        return $listQuizzes;
    }

    public function createQuestions()
    {
        $listQuestions = array();

        // Récupération des clés des levels
        $level = null;
        try {
            $level = LevelEnum::getConstants();
        } catch (\ReflectionException $e) {
        }
        $keyLevel = array();
        foreach ($level as $key => $value) {
            $keyLevel[] = $key;
        }

        for ( $i=0 ; $i<count($this->listQuizzes) ; $i++ ) {
            $quiz = $this->listQuizzes[$i];
            dump('');
            dump($quiz->getTitle());

            // Nombre aléatoir pour choisir le nombre de question entre 3 et 5
            $randQuestions = rand(self::NB_QUESTIONS_MIN, self::NB_QUESTIONS_MAX);
            dump($randQuestions);

            for ( $y=0 ; $y<$randQuestions ; $y++ ) {
                // random sur la difficultée
                $rand = array_rand($keyLevel);
                $randLevel = $level[$keyLevel[$rand]];

                $question = new Question();
                $question->setQuiz($quiz)
                    ->setQuestion($this->generator->quizName())
                    ->setProp1('Réponse 1')
                    ->setProp2('Réponse 2')
                    ->setProp3('Réponse 3')
                    ->setProp4('Réponse 4')
                    ->setLevel($randLevel[0]);

                $this->manager->persist($question);
                $this->manager->flush();

                dump($question->getQuestion());

                $listQuestions[] = $question;
            }
        }

        return $listQuestions;
    }

    public function createResultsQuizzes()
    {
        // On va choisir au hasard un utlisateur et un questionnaire pour lui faire remplir le questionnaires avec pour cahque réponses, un choix aléatoire
        for ( $i=0 ; $i<self::NB_RESULTS ; $i++ ) {

            // Choisir un étudiant au hasard
            $student = $this->listUsers[array_rand($this->listUsers)];

            // Choisir un questionnaire au hasard
            $quiz = $this->listQuizzes[array_rand($this->listQuizzes)];
            // Récupération de toutes les questions du Quiz sélectionné
            $listQuestions = $this->manager->getRepository(Question::class)->findByQuiz($quiz);

            // Création des réponses pour chaques questions
            $responses = array();
            for ( $y=0 ; $y<count($listQuestions) ; $y++ ) {
                $question = $listQuestions[$y];

                $rand = rand(1, 4);
                $prop = 'getProp'.$rand;
                $responseUser = $question->$prop();

                $isGood = false;
                if ( $rand === 1) {
                    $isGood = true;
                }

                $responses[] = [$question, $responseUser, $isGood];
            }

            // compter le nombre de bonne réponses:
            $goodResponses = 0;
            for ( $x=0 ; $x<count($responses) ; $x++ ) {
                if ($responses[$x][2]) {
                    $goodResponses++;
                }
            }

            //Calcul du pourcentage de bonne réponse et convertion en entier:
            $pourcent = ($goodResponses * 100) / count($listQuestions);
            $pourcent = intval($pourcent);

            $result = new Result();
            $result->setStudent($student)
                ->setQuiz($quiz)
                ->setDateAt($this->generator->dateTimeBetween('-1 month', 'now'))
                ->setResponses($responses)
                ->setScore($pourcent);

            $this->manager->persist($result);
            $this->manager->flush();

            dump($result->getStudent()->getFirstName() . ' a répondu ' . $result->getScore() . '% de bonnes réponses !');
        }
    }
}
