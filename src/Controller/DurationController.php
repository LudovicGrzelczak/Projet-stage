<?php

namespace App\Controller;

use App\Entity\Duration;
use App\Form\DurationType;
use App\Repository\DurationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/duration')]
class DurationController extends AbstractController
{
    #[Route('/', name: 'list_duration')]
    public function index(DurationRepository $repositoryduration): Response {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $duration = $repositoryduration->findAll();

        
        return $this->render('duration/list.html.twig', [
            'duration' => $duration
        ]);
    }


    #[Route('/edit/{id?0}', name: 'edit_duration')]
    public function addGame(Duration $duration = null, ManagerRegistry $doctrine, Request $request): Response
    {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $new = false;

        if (!$duration) {
            $new = true;
            $duration = new Duration();
        }
        
        $durationform = $this->createForm(DurationType::class, $duration);

        $durationform->handleRequest($request);
        
        //  formulaire soumis ?
        if ($durationform->isSubmitted()) {

            $manager = $doctrine->getManager();
            $manager->persist($duration);

            $manager->flush();

            if ($new) {
                $message = ' a été ajouté.';
            } else {
                $message = ' a été mis à jour.';
            }
            $this->addFlash(
               'info',
               'Le forfait au/à ' . $duration->getTypeDuration(). $message
            );

            return $this->redirectToRoute('list_duration');

        } else {
            // si non => afficher le formulaire
        return $this->render('duration/add-duration.html.twig', [
            'durationform' => $durationform->createView()
        ]);
        }
    }


    #[Route('/delete/{id}', name: 'delete_duration')]
    public function deleteGame(Duration $duration = null, ManagerRegistry $doctrine): RedirectResponse 
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Récupérer la personne
        if ($duration) {
            // Si la personne existe => le supprimer et retourner un flashmessage de succés
            $manager = $doctrine->getManager();
            $manager->remove($duration);
            $manager->flush();
            $this->addFlash(
               'success',
               'Le forfait au/à '. $duration->getTypeDuration(). ' est supprimé'
            );
        } else {
            // Sinon retourner un flashmessage
            $this->addFlash(
               'error',
               'Ce forfait n\'existe pas'
            );
        }
        return $this->redirectToRoute('list_duration');
    }
}
