<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RegistrationController extends AbstractController
{ 
    private $slugger;
    private $imgDirectory;

    public function __construct(SluggerInterface $slugger, string $imgDirectory)
    {
        $this->slugger = $slugger;
        $this->imgDirectory = $imgDirectory;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request,UserRepository $repository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            
           

            $entityManager->persist($user);
        
           // $repository->sms();
            $entityManager->flush();
            
            // Set their role
            $user->setRoles(['roles']);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    } 
    /**
 * @Route("/traiter/{id}", name="app_regis")
 */

 function Traiter(UserRepository $repository, $id, Request $request, EntityManager $entityManager)
 {

     $user = new User();
     $user = $repository->find($id);
     // $reclamation->setEtat(1 );
     $em = $this-> $entityManager->getManager();
     $em->flush();
     $repository->sms();
     $this->addFlash('danger', 'reponse envoyée avec succées');
     return $this->redirectToRoute('app_login');
 }
}
