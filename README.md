# KunstmaanSocialMediaBundle

The KunstmaanSocialMediaBundle makes working with social media feeds and the KunstmaanBundles CMS easier.

Currently supports Instagram, Twitter and Tumblr , will support Facebook, Vimeo and YouTube.

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

### Step 5: Add required config

Turn on the timestampable Doctrine extension in `app/config/config.yml`

```yml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
```

## Usage

### Generating an Instagram Access Token

 * Navigate to the Social Media module admin page.
 * Click on the Instagram Settings in the top right corner.
 * Enter your Instagram App client ID and client secret.
 * Enter a hashtag to use to pull media for if required. Leave blank to pull your own feed.
 * Click 'Log in to Instagram'
 * Authorise the App with Instagram.
 * You will be returned to the Settings page and it should say that you are now logged in to Instagram.
 
### Generating a Twitter Access Token

 * Navigate to the Social Media module admin page.
 * Click on the Twitter Settings in the top right corner.
 * Enter your Twitter App Consumer ID and Consumer Secret.
 * Choose whether to pull tweets from a given user or search for tweets using a hashtag.
 * Enter the username or hashtag that you want to search for.
 * Click 'Log in to Twitter'
 * You should now see that you are logged into Twitter.

### Setting Up Access To Tumblr Posts

  * Navigate to the Social Media module admin page.
  * Click on the Tumblr Settings in the top right corner.
  * Enter your Tumblr App Consumer Key.
  * Choose whether to pull tweets from a given user or search for tweets using a hashtag.
  * Enter the Tumblr URL or hashtag that you want to search for. - The Tumblr URL should contain 'tumblr.com' unless it is a custom URL
  * Click 'Log in to Tumblr'
  * You should now see that you are logged into Tumblr.
 
### Updating Your Social Feed from the CLI

This allows you to update your social feed and pull in the latest posts ready for moderation from the project Admin List. You could set up a cron to run this.

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
