<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Form\JeuxType;
use App\Repository\DurationRepository;
use App\Repository\JeuxRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/games')]
class JeuxController extends AbstractController
{

    #[Route('/', name: 'list_games')]
    public function index(JeuxRepository $repositoryJeux): Response {

        
        $jeux = $repositoryJeux->findAll();

        
        return $this->render('games/index.html.twig', [
            'jeux' => $jeux
        ]);
    }
    

    #[Route('/detail/{id?0}', name: 'game_detail')]
    public function detail(Jeux $jeu = null, DurationRepository $reposotiryDuration): Response {

         $duration = $reposotiryDuration->findAll();

        return $this->render('games/detail.html.twig', [
            'jeu' => $jeu,
            'duration' => $duration
        ]);
    }


    #[Route('/edit/{id?0}', name: 'edit_game')]
    public function addGame(Jeux $jeux = null, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $new = false;

        if (!$jeux) {
            $new = true;
            $jeux = new Jeux();

        }
        
        $jeuxForm = $this->createForm(JeuxType::class, $jeux);
        

        $jeuxForm->handleRequest($request);
        
        //  formulaire soumis ?
        if ($jeuxForm->isSubmitted()) {
            

            $imageJeuxFile = $jeuxForm->get('picture')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageJeuxFile) {
                $originalFilename = pathinfo($imageJeuxFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageJeuxFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageJeuxFile->move(
                        $this->getParameter('imagejeux_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $jeux->setImageFilename($newFilename);
            }

            $manager = $doctrine->getManager();
            $manager->persist($jeux);

            $manager->flush();

            if ($new) {
                $message = ' a été ajouté.';
            } else {
                $message = ' a été mis à jour.';
            }
            $this->addFlash(
               'info',
               'Le jeu ' . $jeux->getname(). $message
            );

            return $this->redirectToRoute('list_games');

        } else {
            // si non => afficher le formulaire
        return $this->render('games/add-jeux.html.twig', [
            'jeuxform' => $jeuxForm->createView()
        ]);
        }
        

        
    }


    #[Route('/delete/{id}', name: 'delete_game')]
    public function deleteGame(Jeux $jeux = null, ManagerRegistry $doctrine): RedirectResponse 
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Récupérer la personne
        if ($jeux) {
            // Si la personne existe => le supprimer et retourner un flashmessage de succés
            $manager = $doctrine->getManager();
            $manager->remove($jeux);
            $manager->flush();
            $this->addFlash(
               'success',
               'Le jeu '. $jeux->getname(). ' est supprimé'
            );
        } else {
            // Sinon retourner un flashmessage
            $this->addFlash(
               'error',
               'Le jeu n\'existe pas'
            );
        }
        return $this->redirectToRoute('list_games');
    }



}
