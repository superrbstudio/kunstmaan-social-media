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
        if(!$this->settings instanceof \stdClass)
        {
            $this->settings = new \stdClass();
        }
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
        if(isset($this->getSettings()[$key]))
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
                        $response = $client->get('users/self/feed', array('query' => array('access_token' => $this->getSetting('access_token'))));

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
        }

        return $success;
    }
}
