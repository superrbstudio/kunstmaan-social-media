# UPGRADE FROM 0.2.3 to 0.2.4

## Tumblr and Vimeo integration

* Support for Tumblr and Vimeo feeds.
* Minor bug fixes with content have been addressed.

### Upgrade Instructions:

You can use Doctrine Migrations or a schema update, it is your choice

```bash
app/console doctrine:migrations:diff
app/console doctrine:migrations:migrate
```
or
```bash
app/console doctrine:schema:update --force
```

### Setting Up Access To Tumblr Posts

  * Navigate to the Social Media module admin page.
  * Click on the Tumblr Settings in the top right corner.
  * Enter your Tumblr App Consumer Key.
  * Choose whether to pull tweets from a given user or search for tweets using a hashtag.
  * Enter the Tumblr URL or hashtag that you want to search for. - The Tumblr URL should contain 'tumblr.com' unless it is a custom URL
  * Click 'Log in to Tumblr'
  * You should now see that you are logged into Tumblr.

### Generating a Vimeo Access Token

   * Navigate to the Social Media module admin page.
   * Click on the Vimeo Settings in the top right corner.
   * Enter your Vimeo App Consumer ID and Consumer Secret.
   * Choose whether to pull videos from a given user or search for videos using a hashtag.
   * Enter the user ID or hashtag that you want to search for.
   * Click 'Log in to Vimeo'
   * You should now see that you are logged into Vimeo.



## Active switch on all medias

* Disable individual feeds without losing details by switching the active state on each media type.

### Upgrade Instructions:

Nothing custom required