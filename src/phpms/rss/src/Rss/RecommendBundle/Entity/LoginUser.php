<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * LoginUser
 * @ORM\Entity(repositoryClass="Rss\RecommendBundle\Repository\LoginUserRepository")
 * @ORM\Table(name="login_user", uniqueConstraints={@ORM\UniqueConstraint(name="user_name", columns={"user_name"})})
 */
class LoginUser implements AdvancedUserInterface
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="user_name", type="string", length=32, nullable=false)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(name="mail_address", type="string", length=255, nullable=true)
     */
    private $mailAddress;

    /**
     * @var string
     * @ORM\Column(name="twitter_account", type="string", length=255, nullable=true)
     */
    private $twitterAccount;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;
  
    private $accountNonExpired = true;
    private $credentialsNonExpired = true;
    private $accountNonLocked = true;
    private $enabled = true;
    private $roles = array('ROLE_USER');
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return LoginUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set mailAddress
     *
     * @param string $mailAddress
     * @return LoginUser
     */
    public function setMailAddress($mailAddress)
    {
        $this->mailAddress = $mailAddress;

        return $this;
    }

    /**
     * Get mailAddress
     *
     * @return string 
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * Set twitterAccount
     *
     * @param string $twitterAccount
     * @return LoginUser
     */
    public function setTwitterAccount($twitterAccount)
    {
        $this->twitterAccount = $twitterAccount;

        return $this;
    }

    /**
     * Get twitterAccount
     *
     * @return string 
     */
    public function getTwitterAccount()
    {
        return $this->twitterAccount;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return LoginUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * Saltを返すメソッド
     * Saltは固定の文字列を返す
     *
     * @return string
     */
    public function getSalt()
    {
        return 'loginSecuritySalt';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }
    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
    
    /**
     * {@inheritDoc}
     */
    public function equals(UserInterface $user)
    {
        if (!$user instanceof LoginUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getUsername() !== $user->getUsername()) {
            return false;
        }

        if ($this->accountNonExpired !== $user->isAccountNonExpired()) {
            return false;
        }

        if ($this->accountNonLocked !== $user->isAccountNonLocked()) {
            return false;
        }

        if ($this->credentialsNonExpired !== $user->isCredentialsNonExpired()) {
            return false;
        }

        if ($this->enabled !== $user->isEnabled()) {
            return false;
        }

        return true;
    }
}
