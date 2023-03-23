<?php

namespace App\Controller;


use App\Entity\Servers;

use App\Repository\DurationRepository;

use App\Repository\JeuxRepository;
use App\Repository\ServersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/servers')]
class ServersController extends AbstractController
{
    #[Route('/list', name: 'list_servers'), IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(ServersRepository $repositoryServers): Response 
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $servers = $repositoryServers->findAll();
        }
        else if ($this->isGranted('ROLE_USER')) {
            $servers = $repositoryServers->findBy(['user_id' => $this->getUser()]);
        } 
        return $this->render('servers/list.html.twig', [
            'servers' => $servers,
        ]);
    }

    #[Route('/detail/{id?0}', name: 'server_detail'), IsGranted('IS_AUTHENTICATED_FULLY')]
    public function detail(Servers $servers = null): Response {
         
        return $this->render('servers/detail.html.twig', [
            'servers' => $servers
        ]);
    }



    #[Route('/add', name: 'add_server'), IsGranted('IS_AUTHENTICATED_FULLY')]
    public function addServer (Request $request, ManagerRegistry $doctrine, JeuxRepository $repositoryJeux, DurationRepository $repositoryDuration)
    {
        
            $jeu = $repositoryJeux->find($request->get('jeu_id'));
            $duration = $repositoryDuration->find($request->get('duration_id'));

            if (!$jeu) {
                $this->addFlash(
                    'error',
                    'Game doesn\'t exist'
                 );
                 return $this->redirectToRoute('list_games'); 
            }
            $server = (new Servers)->setUserId($this->getUser())->setJeuId($jeu)->setDurationId($duration);
       
        
            $manager = $doctrine->getManager();
            $manager->persist($server);

            $manager->flush();

            $this->addFlash(
               'success',
               $server . ' server has been created '
            );

            return $this->redirectToRoute('list_servers');
        
    }

    

    #[Route('/delete/{id}', name: 'delete_server')]
    public function deleteGame(Servers $servers = null, ManagerRegistry $doctrine): RedirectResponse 
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // Récupérer la personne
        if ($servers) {
            // Si la personne existe => le supprimer et retourner un flashmessage de succés
            $manager = $doctrine->getManager();
            $manager->remove($servers);
            $manager->flush();
            $this->addFlash(
               'success',
               'The server '. $servers->getId(). ' is deleted'
            );
        } else {
            // Sinon retourner un flashmessage
            $this->addFlash(
               'error',
               'Server does not exist'
            );
        }
        return $this->redirectToRoute('list_servers');
    }
        
    
}
