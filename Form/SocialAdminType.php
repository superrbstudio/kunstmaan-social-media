<?php

namespace Superrb\SocialMediaFeedBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

/**
 * The type for Social
 */
class SocialAdminType extends AbstractType
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
        $builder->add('socialId');
        $builder->add('username');
        $builder->add('slug');
        $builder->add('type');
        $builder->add('datePosted');
        $builder->add('link');
        $builder->add('latitude');
        $builder->add('longitude');
        $builder->add('approved');
        $builder->add('blogTitle');
        $builder->add('blogContent');
        $builder->add('blogImageUrl');
        $builder->add('instagramImageUrl');
        $builder->add('instagramCaption');
        $builder->add('twitterContent');
        $builder->add('twitterLocation');
        $builder->add('vimeoTitle');
        $builder->add('vimeoDescription');
        $builder->add('vimeoThumbnailImageUrl');
        $builder->add('tumblrMediaType');
        $builder->add('tumblrTitle');
        $builder->add('tumblrBodyText');
        $builder->add('tumblrImageUrl');
        $builder->add('tumblrCaption');
        $builder->add('tumblrVideoUrl');
        $builder->add('tumblrVideoThumbnailImageUrl');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'social_form';
    }
}
