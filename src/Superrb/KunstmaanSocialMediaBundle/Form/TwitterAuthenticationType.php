<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Superrb\KunstmaanSocialMediaBundle\Entity\Setting;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class TwitterAuthenticationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array(
                Setting::class,
                'determineTwitterValidationGroups',
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
            'label' => 'kuma_social.forms.twitter.active',
            'choices' => Setting::$twitterSettings,
            'choices_as_values' => true,
            'constraints' => array(
                new Choice(array(
                    'choices' => Setting::$twitterSettings,
                    'groups' => array('api', 'active', 'hashtag', 'disabled'),
                )),
            ),
        ));

        $builder->add('consumer_key', TextType::class, array(
            'label' => 'kuma_social.forms.twitter.consumer_key',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.twitter.consumer_key',
                    'groups' => array('api'),
                )),
            ),
        ));

        $builder->add('consumer_secret', TextType::class, array(
            'label' => 'kuma_social.forms.twitter.consumer_secret',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.twitter.consumer_secret',
                    'groups' => array('api'),
                )),
            ),
        ));

        $builder->add('hashtag', TextType::class, array(
            'label' => 'kuma_social.forms.twitter.hashtag',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.twitter.hashtag',
                    'groups' => array('hashtag'),
                )),
            ),
        ));

        $builder->add('profile_url', UrlType::class, array(
            'label' => 'kuma_social.forms.twitter.profile_url',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.twitter.profile_url',
                    'groups' => array('active'),
                )),
                new Url(array(
                    'message' => 'kuma_social.forms.twitter.profile_url_invalid',
                    'groups' => array('active'),
                )),
            ),
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'TwitterAuthenticationType';
    }
}