<?php
namespace Data\Entity;

use DateTime;
use InvalidArgumentException;
use Data\CollectionInterface;
use Data\ArrayCollection;

class User implements UserInterface
{
    const PASSWORD_ALGORITHM = PASSWORD_BCRYPT;

    protected $id;

    protected $email;

    protected $firstName;

    protected $lastName;

    protected $password = null;

    protected $country;

    protected $locale;

    protected $verified = null;

    /**
     * CollectionInterface of SocialAccountInterface
     *
     * @var CollectionInterface<SocialAccountInterface>
     */
    protected $socialAccounts;

    protected $created;

    public function __construct()
    {
        $this->created = new DateTime();

        if (!isset($this->socialAccounts)) {
            $this->socialAccounts = new ArrayCollection();
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        if (trim($email) === '') {
            throw new InvalidArgumentException("Email cannot be empty");
        }
        $this->email = $email;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        if (trim($firstName) === '') {
            throw new InvalidArgumentException("First name cannot be empty");
        }
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        if (trim($lastName) === '') {
            throw new InvalidArgumentException("Last name cannot be empty");
        }
        $this->lastName = $lastName;
    }

    public function getName()
    {
        $firstName = $this->getFirstName();
        $lastName = $this->getLastName();

        $name = $firstName;
        if (!empty($firstName) && !empty($lastName)) {
            $name .= " ";
        }
        $name .= $lastName;
        return $name;
    }

    final public function isPasswordSet()
    {
        return !is_null($this->password);
    }

    final public function setPassword($password = null)
    {
        if (!is_null($password)) {
            $password = base64_encode(hash('sha256', $password, true));
            $this->password = password_hash($password, static::PASSWORD_ALGORITHM);
        } else {
            $this->password = $password;
        }
    }

    final public function checkPassword($password)
    {
        if (!$this->isPasswordSet()) {
            return false;
        }
        $password = base64_encode(hash('sha256', $password, true));
        return password_verify($password, $this->password);
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        if (trim($country) === '') {
            throw new InvalidArgumentException("Country cannot be empty");
        } elseif (strlen($country) !== 2) {
            throw new InvalidArgumentException("Invalid country code specified: {$country}");
        }
        $this->country = $country;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        if (trim($locale) === '') {
            throw new InvalidArgumentException("Locale cannot be empty");
        } elseif (strlen($locale) !== 5) {
            throw new InvalidArgumentException("Invalid locale specified: {$locale}");
        }
        $this->locale = $locale;
    }

    public function setVerified(DateTime $verified)
    {
        $this->verified = $verified;
    }

    public function isVerified()
    {
        return isset($this->verified);
    }

    public function addSocialAccount(SocialAccountInterface $socialAccount)
    {
        $this->socialAccounts->add($socialAccount);
    }

    public function getCreated()
    {
        return $this->created;
    }
}