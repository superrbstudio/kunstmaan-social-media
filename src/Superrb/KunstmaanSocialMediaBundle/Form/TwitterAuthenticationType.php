<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class TwitterAuthenticationType extends AbstractType
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
            'label' => 'Twitter App Consumer Key',
        ));

        $builder->add('consumer_secret', 'text', array(
            'label' => 'Twitter App Consumer Secret',
        ));

        $choices = array(
            'Username' => 'Username',
            'Hashtag' => 'Hashtag',
        );

        $builder->add('user_or_hashtag', 'choice', array(
             'choices' => $choices,
        ));

        $builder->add('username', 'text', array(
            'required' => false,
            'label' => 'Username',
        ));

        $builder->add('hashtag', 'text', array(
            'required' => false,
            'label' => 'Feed Hashtag (without #)',
        ));
    }

    public function getName()
    {
        return 'twitter_authentication';
    }
}