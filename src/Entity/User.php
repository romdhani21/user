<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Please enter an email')]
    #[Assert\Email(message: 'Invalid email address')]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please choose a role')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    /*#[Assert\NotBlank(message: 'Please enter a password')]*/
    private ?string $password = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Please enter a username")]
    #[Assert\Length(
        min: 4,
        max: 100,
        minMessage: "Username must be at least {{ limit }} characters long",
        maxMessage: "Username cannot be longer than {{ limit }} characters"
    )]

    private ?string $username = null;


    
    #[Assert\NotBlank(message: "Please enter an address")]
    #[ORM\Column(length: 255)]
    private ?string $adress = null;
    
    #[Assert\NotBlank(message: "Please enter a phone number")]
    #[Assert\Regex(pattern: "/^[5|2|9|7]+[0-9]*$/",message: "Numero Orange ou Telecom ou Ooreedoo ou FAX only" )]
    #[Assert\Regex(pattern: "/^[0-9]*$/",message: "NumberOnly" )]
    #[ORM\Column(length: 255)]
    private ?string $phonenumber = null;

    #[ORM\Column(nullable: true)]
    private ?bool $blocked = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $blockedUntil = null;
   
    
    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }



    public function __toString()
{
    return $this->username;
}

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function isBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(?bool $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function getBlockedUntil(): ?\DateTimeInterface
    {
        return $this->blockedUntil;
    }

    public function setBlockedUntil(?\DateTimeInterface $blockedUntil): self
    {
        $this->blockedUntil = $blockedUntil;

        return $this;
    }
    
   
}
