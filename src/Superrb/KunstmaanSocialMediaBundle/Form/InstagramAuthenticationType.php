<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InstagramAuthenticationType extends AbstractType
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

        $builder->add('client_id', TextType::class, array(
            'label' => 'Instagram App Client ID',
        ));

        $builder->add('client_secret', TextType::class, array(
            'label' => 'Instagram App Client Secret',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'instagram_authentication';
    }
}