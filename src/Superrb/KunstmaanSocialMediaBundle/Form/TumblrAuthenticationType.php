<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class TumblrAuthenticationType extends AbstractType
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
            'label' => 'Tumblr App Consumer Key',
        ));

        $choices = array(
            'Username' => 'Username',
            'Hashtag' => 'Hashtag',
        );

        $builder->add('user_or_hashtag', 'choice', array(
            'choices' => $choices,
        ));

        $builder->add('tumblr_url', 'text', array(
            'required' => false,
            'label' => 'Tumblr URL (found in blog settings)',
        ));

        $builder->add('hashtag', 'text', array(
            'required' => false,
            'label' => 'Feed Hashtag (without #)',
        ));
    }

    public function getName()
    {
        return 'tumblr_authentication';
    }
}