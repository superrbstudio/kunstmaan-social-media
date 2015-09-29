<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class TumblrAuthenticationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('consumer_key', 'text', array(
            'label' => 'Tumblr App Consumer Key',
        ));

        $builder->add('consumer_secret', 'text', array(
            'label' => 'Tumblr App Consumer Secret',
        ));
    }

    public function getName()
    {
        return 'tumblr_authentication';
    }
}