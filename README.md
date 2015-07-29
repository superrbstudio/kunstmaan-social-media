# KunstmaanSocialMediaBundle

The KunstmaanSocialMediaBundle makes working with social media feeds and the KunstmaanBundles CMS easier.

Currently only supports Instagram, will support Twitter, Tumblr, Facebook and YouTube.

## Installation

### Step 1: Install the Bundle

```bash
composer require superrb/kunstmaan-social-media
```

### Step 2: Enable the Bundle

Enable the bundle in your `app/AppKernel.php` for your project

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Superrb\KunstmaanSocialMediaBundle\SuperrbKunstmaanSocialMediaBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Add the routes

Add the following to your `app/config/routes.yml`

```yml
SuperrbKunstmaanSocialMediaBundle:
    resource: "@SuperrbKunstmaanSocialMediaBundle/Resources/config/routing.yml"
    prefix:   /
    
superrbkunstmaansocialmediabundle_social_admin_list:
    resource: @SuperrbKunstmaanSocialMediaBundle/Controller/SocialAdminListController.php
    type:     annotation
    prefix:   /{_locale}/admin/social/
```

Remember to remove the `/{_locale}/` from the admin list route if you are using single language.

### Step 4: Generate Database Tables

You can use Doctrine Migrations or a schema update, it is your choice

```bash
app/console doctrine:migrations:diff
app/console doctrine:migrations:migrate
```
or
```bash
app/console doctrine:schema:update --force
```

### Step 5: Add required parameters and other config

Add the following parameters to `app/config/parameters.yml`

Don't forget to add them to your `app/config/parameters.yml.dist` if you use it.

```yml
parameters:
    superrb_kunstmaan_social_media.instagram_client_id:     CLIENT_ID
    superrb_kunstmaan_social_media.instagram_client_secret: CLIENT_SECRET
    superrb_kunstmaan_social_media.instagram_callback:      http://domain.com
    superrb_kunstmaan_social_media.instagram_access_token:  INSTAGRAM-XXXX
    superrb_kunstmaan_social_media.instagram_user_id:       123456
    superrb_kunstmaan_social_media.instagram_username:      instagramuser
    superrb_kunstmaan_social_media.instagram_hashtag:       'hashtag'
```

You can generate your Instagram Client details at http://developers.instagram.com

Set the hashtag to null to pull the users own photos rather than posts for a hashtag.

Turn on the timestampable Doctrine extension in `app/config/config.yml`

```yml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
```

## Usage

### Generating an Instagram Access Token

First make sure you have set up your Instagram client and have added the client ID and the client secret to your project parameters. Your redirect URL for your client will be http://yoursite.com/sksmb/instagram/generate-token

 * Navigate to http://yoursite.com/sksmb/instagram/generate-token
 * Click on the 'Login to Instagram' Link
 * Log into Instagram with the account that you want to generate the access token for
 * Authenticate the Instagram Client to allow it access to your account.
 * You will be redirected back to your site and your access token should be displayed on the page. 
 * Copy this token to your project parameters.
 
### Updating Your Social Feed

This allows you to update your social feed and pull in the latest posts ready for moderation from the project Admin List.

```bash
app/console kuma:socialMedia:update
```

### Outputting Feed Items on the front end

You can output a list of your authorised feed items on the front end simply be rendering a controller action. This could also be added to a page part template to allow more control.

```twig
{{ render_esi(controller('SuperrbKunstmaanSocialMediaBundle:SocialMedia:feed', { 'limit' : 12, 'template' : 'SuperrbKunstmaanSocialMediaBundle:SocialMedia:feed.html.twig' } )) }}
```

## Issues and Troubleshooting

All issues: tech@superrb.com
