<?php

namespace Superrb\KunstmaanSocialMediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class InstagramAuthenticationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('client_id', 'text', array(
            'label' => 'Instagram App Client ID',
        ));

        $builder->add('client_secret', 'text', array(
            'label' => 'Instagram App Client Secret',
        ));

        $builder->add('hashtag', 'text', array(
            'required' => false,
            'label' => 'Feed Hashtag (without #, leave blank to use your feed)',
        ));
    }

    public function getName()
    {
        return 'instagram_authentication';
    }
}