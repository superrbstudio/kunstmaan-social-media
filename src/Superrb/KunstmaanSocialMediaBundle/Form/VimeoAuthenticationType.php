<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VimeoAuthenticationType extends AbstractType
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
        ));

        $builder->add('consumer_key', TextType::class, array(
            'label' => 'Vimeo App Client Identifier',
        ));

        $builder->add('consumer_secret', TextType::class, array(
            'label' => 'Vimeo App Consumer Secret',
        ));

        $choices = array(
            'Username' => 'Username',
            'Hashtag' => 'Hashtag',
        );

        $builder->add('user_or_hashtag', ChoiceType::class, array(
            'choices' => $choices,
        ));

        $builder->add('user_id', TextType::class, array(
            'required' => false,
            'label' => 'User ID (Only needed for username option, found in account settings)',
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
        return 'vimeo_authentication';
    }
}
