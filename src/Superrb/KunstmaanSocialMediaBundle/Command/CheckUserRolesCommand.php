<?php

namespace Superrb\KunstmaanSocialMediaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Kunstmaan\AdminBundle\Entity\Role;

class CheckUserRolesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kuma:socialMedia:checkRoles')
            ->setDescription('Checks whether the required Roles exist');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');

        // check admin role
        $socialAdminRole = $doctrine->getRepository(Role::class)->findOneBy(array('role' => 'ROLE_SOCIAL_ADMIN'));

        if(!$socialAdminRole)
        {
            $socialAdminRole = new Role('ROLE_SOCIAL_ADMIN');
            $doctrine->getManager()->persist($socialAdminRole);
        }

        $socialUserRole = $doctrine->getRepository(Role::class)->findOneBy(array('role' => 'ROLE_SOCIAL_USER'));

        if(!$socialUserRole)
        {
            $socialUserRole = new Role('ROLE_SOCIAL_USER');
            $doctrine->getManager()->persist($socialUserRole);
        }

        $doctrine->getManager()->flush();
        $output->writeln('Roles checked and updated');
    }
}