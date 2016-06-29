<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Superrb\KunstmaanSocialMediaBundle\Entity\Social;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Kunstmaan\MediaBundle\Validator\Constraints as Assert;
use Kunstmaan\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class SocialType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'class' => Social::class,
            'validation_groups' => array(
                Social::class,
                'determineCustomPostValidationGroups',
            ),
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('type', ChoiceType::class, array(
            'choices' => Social::$availableTypes,
            'label' => 'kuma_social.forms.social.type',
            'choices_as_values' => true,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.social.type',
                    'groups' => array('default'),
                )),
            ),
        ));

        $builder->add('username', TextType::class, array(
            'label' => 'kuma_social.forms.social.username',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.social.username',
                    'groups' => array('default'),
                )),
            ),
        ));

        $builder->add('datePosted', DateTimeType::class, array(
            'label' => 'kuma_social.forms.social.date_posted',
            'required' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd/MM/yyyy',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.social.date_posted',
                    'groups' => array('default'),
                )),
                new DateTime(array(
                    'message' => 'kuma_social.forms.social.date_posted_invalid',
                    'groups' => array('default'),
                )),
            ),
        ));

        $builder->add('link', UrlType::class, array(
            'label' => 'kuma_social.forms.social.link',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.social.link',
                    'groups' => array('default'),
                )),
                new Url(array(
                    'message' => 'kuma_social.forms.social.link_invalid',
                    'groups' => array('default'),
                )),
            ),
        ));

        $builder->add('customImage', MediaType::class, array(
            'label' => 'kuma_social.forms.social.custom_image',
            'pattern' => 'KunstmaanMediaBundle_chooser',
            'mediatype' => 'image',
            'constraints' => array(new Assert\Media(array(
                'minHeight' => '750',
                'minWidth' => '750',
                'groups' => array('default'),
            ))),
            'required' => true,
        ));

        $builder->add('instagramCaption', TextType::class, array(
            'label' => 'kuma_social.forms.social.instagram_caption',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'kuma_social.forms.social.instagram_caption',
                    'groups' => array('instagram'),
                )),
            ),
        ));

        $builder->add('approved');
    }

    public function getBlockPrefix(){
        return 'SocialType';
    }
}