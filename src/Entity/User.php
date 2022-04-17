<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="user_ibfk_1", columns={"membership"})}, indexes={@ORM\Index(name="gym", columns={"gym"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, nullable=false)
     */
    private $role;

    /**
     * @var string
     * 
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+"
     * )
     *
     *
     * @ORM\Column(name="first_name", type="string", length=200, nullable=false)
     * @Assert\NotBlank(message="Last name must not be null!")
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+"
     * )
     *
     * @ORM\Column(name="last_name", type="string", length=200, nullable=false)
     * @Assert\NotBlank(message="Last name must not be null!")
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @ORM\Column(name="email", type="string", length=200, nullable=false)
     * @Assert\NotBlank(message="Email must not be null!")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=200, nullable=false)
     * 
     */
    private $password;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_card", type="integer", nullable=true, options={"default"="NULL"})
     * 
     */
    private $idCard = NULL;

    /**
     * @var float|null
     *@Assert\Type("float")
     * @ORM\Column(name="height", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     * 
     */
    private $height = NULL;

    /**
     * @var float|null
     *@Assert\Type("float")
     * @ORM\Column(name="weight", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     * 
     */
    private $weight = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="training_level", type="string", length=30, nullable=true, options={"default"="NULL"})
     * 
     */
    private $trainingLevel = NULL;

    /**
     * @var float|null
     *@Assert\Type("float")
     * @ORM\Column(name="cost_per_hour", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     * 
     */
    private $costPerHour = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true, options={"default"="NULL"})
     * 
     */
    private $description = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="experience", type="string", length=200, nullable=true, options={"default"="NULL"})
     * 
     */
    private $experience = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="picture", type="string", length=300, nullable=true, options={"default"="'file:/C:/Users/Asma/Downloads/img.png'"})
     */
    private $picture = '\'file:/C:/Users/Asma/Downloads/img.png\'';

    /**
     * @var int|null
     *
     * @ORM\Column(name="code", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $code = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="block", type="string", length=10, nullable=true, options={"default"="NULL"})
     */
    private $block = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="reports", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $reports = NULL;

    /**
     * @var \Membership
     *
     * @ORM\ManyToOne(targetEntity="Membership")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="membership", referencedColumnName="idm")
     * })
     */
    private $membership;

    /**
     * @var \Gym
     *
     * @ORM\ManyToOne(targetEntity="Gym")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gym", referencedColumnName="idG")
     * })
     */
    private $gym;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIdCard(): ?int
    {
        return $this->idCard;
    }

    public function setIdCard(?int $idCard): self
    {
        $this->idCard = $idCard;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getTrainingLevel(): ?string
    {
        return $this->trainingLevel;
    }

    public function setTrainingLevel(?string $trainingLevel): self
    {
        $this->trainingLevel = $trainingLevel;

        return $this;
    }

    public function getCostPerHour(): ?float
    {
        return $this->costPerHour;
    }

    public function setCostPerHour(?float $costPerHour): self
    {
        $this->costPerHour = $costPerHour;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getBlock(): ?string
    {
        return $this->block;
    }

    public function setBlock(?string $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function getReports(): ?int
    {
        return $this->reports;
    }

    public function setReports(?int $reports): self
    {
        $this->reports = $reports;

        return $this;
    }

    public function getMembership(): ?Membership
    {
        return $this->membership;
    }

    public function setMembership(?Membership $membership): self
    {
        $this->membership = $membership;

        return $this;
    }

    public function getGym(): ?Gym
    {
        return $this->gym;
    }

    public function setGym(?Gym $gym): self
    {
        $this->gym = $gym;

        return $this;
    }

    public function sendPassword(MailerInterface $mailer)
    {
        
        $email = (new Email())
            ->from('asma.hejaiej@esprit.tn')
            ->to('hejaiej.asma@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject("Password")
            ->text('This is your password :'.$this->password);

        $mailer->send($email);


        // ...
    }

    public function sendCode(MailerInterface $mailer)
    {
        
        $email = (new Email())
            ->from('asma.hejaiej@esprit.tn')
            ->to('hejaiej.asma@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject("Code")
            ->text('This is your code :'.$this->code);

        $mailer->send($email);


        // ...
    }


}
