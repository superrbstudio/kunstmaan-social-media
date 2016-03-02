<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TumblrAuthenticationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $activeChoices = array(
            'Active' => 'active',
            'Disabled' => 'disabled',
        );

        $builder->add('active', ChoiceType::class, array(
            'choices' => $activeChoices,
            'choices_as_values' => true,
        ));

        $builder->add('consumer_key', TextType::class, array(
            'label' => 'Tumblr App Consumer Key',
        ));

        $choices = array(
            'Username' => 'Username',
            'Hashtag' => 'Hashtag',
        );

        $builder->add('user_or_hashtag', ChoiceType::class, array(
            'choices' => $choices,
            'choices_as_values' => true,
        ));

        $builder->add('tumblr_url', TextType::class, array(
            'required' => false,
            'label' => 'Tumblr URL (found in blog settings)',
        ));

        $builder->add('hashtag', TextType::class, array(
            'required' => false,
            'label' => 'Feed Hashtag (without #)',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tumblr_authentication';
    }
}