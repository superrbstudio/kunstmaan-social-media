<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class VimeoAuthenticationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $activeChoices = array(
            'active' => 'Active',
            'disabled' => 'Disabled',
        );

        $builder->add('active', 'choice', array(
            'choices' => $activeChoices,
        ));

        $builder->add('consumer_key', 'text', array(
            'label' => 'Vimeo App Client Identifier',
        ));

        $builder->add('consumer_secret', 'text', array(
            'label' => 'Vimeo App Consumer Secret',
        ));

        $choices = array(
            'Username' => 'Username',
            'Hashtag' => 'Hashtag',
        );

        $builder->add('user_or_hashtag', 'choice', array(
            'choices' => $choices,
        ));

        $builder->add('user_id', 'text', array(
            'required' => false,
            'label' => 'User ID (Only needed for username option, found in account settings)',
        ));

        $builder->add('hashtag', 'text', array(
            'required' => false,
            'label' => 'Feed Hashtag (without #)',
        ));
    }

    public function getName()
    {
        return 'vimeo_authentication';
    }
}