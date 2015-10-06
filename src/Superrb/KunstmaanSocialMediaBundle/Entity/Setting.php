<?php

namespace Superrb\KunstmaanSocialMediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use GuzzleHttp\Client;

/**
 * Setting
 *
 * @ORM\Table(name="sksmb_setting", indexes={@ORM\Index(name="social_type_idx", columns={"social_type"})})
 * @ORM\Entity(repositoryClass="Superrb\KunstmaanSocialMediaBundle\Repository\SettingRepository")
 * @UniqueEntity("socialType")
 */
class Setting extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="social_type", type="string", length=255, unique=true)
     */
    private $socialType;

    /**
     * @var string
     *
     * @ORM\Column(name="settings", type="json_array")
     */
    private $settings;

    public function __construct()
    {
    }

    /**
     * Set setting
     *
     * @param string $key
     * @param string $value
     *
     * @return Setting
     */
    public function setSetting($key, $value)
    {
        $settings = $this->getSettings();
        $settings[$key] = $value;
        $this->setSettings($settings);

        return $this;
    }

    /**
     * Get setting
     *
     * @return string
     */
    public function getSetting($key)
    {
        if($this->getSettings() and isset($this->getSettings()[$key]))
        {
            return $this->getSettings()[$key];
        }

        return null;
    }

    /**
     * Set settings
     *
     * @param \stdClass $settings
     *
     * @return Setting
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settings
     *
     * @return \stdClass
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set socialType
     *
     * @param string $socialType
     *
     * @return Setting
     */
    public function setSocialType($socialType)
    {
        $this->socialType = $socialType;

        return $this;
    }

    /**
     * Get socialType
     *
     * @return string
     */
    public function getSocialType()
    {
        return $this->socialType;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        $success = false;

        switch($this->getSocialType())
        {
            case 'instagram':
                if($this->getSetting('access_token'))
                {
                    try
                    {
                        $client = new Client(array('base_uri' => "https://api.instagram.com/v1/"));
                        $response = $client->get('users/self/feed', array(
                            'query' => array(
                                'access_token' => $this->getSetting('access_token')
                            )
                        ));

                        if($response->getStatusCode() == 200)
                        {
                            $success = true;
                        }
                    }
                    catch (\Exception $e)
                    {
                        $success = false;
                    }
                }

            case 'tumblr':
                if($this->getSetting('consumer_key') and $this->getSetting('user_or_hashtag') and ($this->getSetting('tumblr_url') or $this->getSetting('hashtag')))
                {
                    try
                    {
                        $tag = '';
                        $url = '';
                        if($this->getSetting('user_or_hashtag'))
                        {
                            switch ($this->getSetting('user_or_hashtag'))
                            {
                                case 'Username':
                                    $url = 'blog/' . $this->getSetting('tumblr_url') . '/posts';
                                    break;
                                case 'Hashtag':
                                    $url = 'tagged';
                                    $tag = $this->getSetting('hashtag');
                                    break;
                            }
                        }

                        $client = new Client(array('base_uri' => 'https://api.tumblr.com'));

                        if($url)
                        {
                            $response =  $client->get('/v2/' . $url, [
                                'query' => [
                                    'api_key' => $this->getSetting('consumer_key'),
                                    'tag' => $tag,
                                    'notes_info' => true
                                ]
                            ]);

                            if($response->getStatusCode() == 200)
                            {
                                $success = true;
                            }
                        }
                    }
                    catch (\Exception $e)
                    {
                        $success = false;
                    }
                }

            case 'twitter':
                if($this->getSetting('access_token') and $this->getSetting('user_or_hashtag') and ($this->getSetting('username') or $this->getSetting('hashtag')))
                {
                    try
                    {
                        $client = new Client(array('base_uri' => 'https://api.twitter.com'));

                        if($this->getSetting('user_or_hashtag') == 'Username' and $this->getSetting('username'))
                        {
                            $response = $client->get('/1.1/statuses/user_timeline.json', array(
                                'headers' => array(
                                    'Authorization' => 'Bearer ' . $this->getSetting('access_token'),
                                ),
                                'query' => array(
                                    'count' => 50,
                                    'screen_name' => $this->getSetting('username'),
                                )
                            ));

                            if($response->getStatusCode() == 200)
                            {
                                $success = true;
                            }
                        }

                        if($this->getSetting('user_or_hashtag') == 'Hashtag' and $this->getSetting('hashtag'))
                        {
                            $response = $client->get('/1.1/search/tweets.json', array(
                                'headers' => array(
                                    'Authorization' => 'Bearer ' . $this->getSetting('access_token'),
                                ),
                                'query' => array(
                                    'count' => 50,
                                    'q' => '#' . $this->getSetting('hashtag'),
                                )
                            ));

                            if($response->getStatusCode() == 200)
                            {
                                $success = true;
                            }
                        }
                    }
                    catch (\Exception $e)
                    {
                        $success = false;
                    }
                }
            case 'vimeo':
                if($this->getSetting('access_token') and $this->getSetting('user_or_hashtag') and ($this->getSetting('user_id') or $this->getSetting('hashtag')))
                {
                    try
                    {
                        $client = new Client(array('base_uri' => 'https://api.vimeo.com'));

                        if($this->getSetting('user_or_hashtag') == 'Username' and $this->getSetting('user_id'))
                        {

                            $response = $client->get('/users/' . $this->getSetting('user_id') . '/videos', array(
                                'headers' => array(
                                    'Authorization' => 'bearer ' . $this->getSetting('access_token'),
                                )
                            ));

                            if($response->getStatusCode() == 200)
                            {
                                $success = true;
                            }
                        }

                        if($this->getSetting('user_or_hashtag') == 'Hashtag' and $this->getSetting('hashtag'))
                        {
                            $response = $client->get('/tags/' . $this->getSetting('hashtag') . '/videos', array(
                                'headers' => array(
                                    'Authorization' => 'bearer ' . $this->getSetting('access_token'),
                                )
                            ));

                            if($response->getStatusCode() == 200)
                            {
                                $success = true;
                            }
                        }
                    }
                    catch (\Exception $e)
                    {
                        $success = false;
                    }
                }

        }

        return $success;
    }
}
