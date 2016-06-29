<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * The type for Social
 */
class SocialAdminType extends SocialType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('type');
        $builder->remove('username');
        $builder->remove('link');
        $builder->remove('datePosted');
        $builder->remove('customImage');
    }

    /**
     * @return string
     */
    public function getBlockPrefix(){
        return 'SocialAdminType';
    }
}
