<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('profil')]
class UserController extends AbstractController
{
    #[Route('/show', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/edit/{id?0}', name: 'app_edit_profile')]

    public function editProfile(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger, User $user = null): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserType::class, $user); //$user is the image of the form - CreateForm using the UserType we already defined

        $form->handleRequest($request); //the form traits the request

        if ($form->isSubmitted()) {
            $user = $form->getData(); //if true get data from the form
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);

            $photoFile = $form->get('photo')->getData(); // Before persist data in Database get the 'photo' property from the form

            //this condition is needed because the 'photo' field is not required in the form
            if ($photoFile) { // if the user uploads a file (photo)
                $originalFileName = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME); //this is needed to safely include the file name as part of the URL
                $safeFileName    = $slugger->slug($originalFileName); //the slugger create a slug to our file 
                $newFileName     = $safeFileName . '-' . uniqid() . '.' . $photoFile->guessExtension(); // this for retrieve the slug from the fileName and add a unique id

                //move the file to the directory where image profile are stored
                try {
                    $photoFile->move(
                        $this->getParameter('profileImage_directory'), //this name is defined in 'service.yaml' the directory where files are uploaded
                        $newFileName
                    );
                } catch (FileException $e) {
                }

                //updates the 'fileName' property to store the photo file name instead of its contents
                $user->setAvatar($newFileName);
            }

            $entityManager->flush();
            //display a success message after update profile
            $this->addFlash("success", "Your profile has been updated successfully! ");
            return  $this->redirectToRoute('app_home');
        } else {

            return $this->render('user/editProfile.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
}
