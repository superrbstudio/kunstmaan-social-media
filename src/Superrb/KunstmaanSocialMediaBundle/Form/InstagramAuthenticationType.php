<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Superrb\KunstmaanSocialMediaBundle\Entity\Setting;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class InstagramAuthenticationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array(
                Setting::class,
                'determineInstagramValidationGroups',
            ),
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('active', ChoiceType::class, array(
            'label' => 'kuma_social.forms.instagram.active',
            'choices' => Setting::$instagramSettings,
            'choices_as_values' => true,
            'constraints' => array(
                new Choice(array(
                    'choices' => Setting::$instagramSettings,
                    'groups' => array('api', 'active', 'hashtag', 'disabled'),
                )),
            ),
        ));

        $builder->add('client_id', TextType::class, array(
            'label' => 'kuma_social.forms.instagram.app_client_id',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.instagram.client_id',
                    'groups' => array('api'),
                )),
            ),
        ));

        $builder->add('client_secret', TextType::class, array(
            'label' => 'kuma_social.forms.instagram.app_client_secret',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.instagram.client_secret',
                    'groups' => array('api'),
                )),
            ),
        ));

        $builder->add('hashtag', TextType::class, array(
            'label' => 'kuma_social.forms.instagram.hashtag',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.instagram.hashtag',
                    'groups' => array('hashtag'),
                )),
            ),
        ));

        $builder->add('profile_url', UrlType::class, array(
            'label' => 'kuma_social.forms.instagram.profile_url',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.instagram.profile_url',
                    'groups' => array('active'),
                )),
                new Url(array(
                    'message' => 'kuma_social.forms.instagram.profile_url_invalid',
                    'groups' => array('active'),
                )),
            ),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'InstagramAuthenticationType';
    }
}