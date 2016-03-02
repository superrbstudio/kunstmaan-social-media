<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TwitterAuthenticationType extends AbstractType
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
            'label' => 'Twitter App Consumer Key',
        ));

        $builder->add('consumer_secret', TextType::class, array(
            'label' => 'Twitter App Consumer Secret',
        ));

        $choices = array(
            'Username' => 'Username',
            'Hashtag' => 'Hashtag',
        );

        $builder->add('user_or_hashtag', ChoiceType::class, array(
            'choices' => $choices,
            'choices_as_values' => true,
        ));

        $builder->add('username', TextType::class, array(
            'required' => false,
            'label' => 'Username',
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
        return 'twitter_authentication';
    }
}