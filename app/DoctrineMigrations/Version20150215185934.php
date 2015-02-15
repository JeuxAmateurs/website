<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManager;
use JA\AppBundle\Entity\Skill;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Set the skills for the application
 */
class Version20150215185934 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function getSkills()
    {
        return array(
            '2d-art' => '2D Art',
            '3d-art' => '3D Art',
            'animation' => 'Animation',
            'sound-design' => 'Sound Design',
            'game-design' => 'Game Design',
            'front-end-development' => 'Front-End Development',
            'back-end-development' => 'Back-End Development',
            'programming' => 'Programming',
            'story-and-narrative' => 'Story and Narrative',
            'web-design' => 'Web Design',
            'ui-ux-design' => 'UI/UX Design',
            'mobile-development' => 'Mobile Development',
            'console-development' => 'Console Development',
            'slacker' => 'Slacker',
        );
    }

    public function up(Schema $schema)
    {
        $skillsAssoc = $this->getSkills();

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        foreach($skillsAssoc as $key => $value)
        {
            $em->persist(new Skill($key, $value));
        }

        $em->flush();
    }

    public function down(Schema $schema)
    {
        // @todo: delete entities with name_canonical = content above
    }
}
