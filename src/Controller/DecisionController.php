<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 05/04/2019
 * Time: 21:56
 */

namespace App\Controller;

use App\Entity\Decision;
use App\Form\DecisionType;
use App\Repository\ContributorRepository;
use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\DecisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/decision")
 * Class DecisionController
 * @package App\Controller
 */
class DecisionController extends AbstractController
{
    /**
     * @Route("/index", name="decision_index")
     * @return Response
     */
    public function index(DecisionRepository $decisionRepository) : Response{
        $decisions = $decisionRepository->findAll();
        return $this->render('decision/index.html.twig',[
                            'decisions' => $decisions
                    ]);
    }
    /**
     * @Route("/waiting", name="decision_waiting")
     * @param DecisionRepository $decisionRepository
     * @return Response
     */
    public function showNoTaken(DecisionRepository $decisionRepository): Response{
        $decisions = $decisionRepository->findNotTaken();
        return $this->render('decision/nottaken.html.twig',[
            'decisions' => $decisions
        ]);
    }

    /**
     * @Route("/create", name="decision_create")
     * @param DecisionRepository $decisionRepository
     * @param ContributorRepository $contributorRepository
     * @param DocumentRepository $documentRepository
     * @return Response
     */
    public function create(EntityManagerInterface $manager, Request $request):Response{
        $form = $this->createForm(DecisionType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var Decision $decision
             */
            $decision = $form->getData();
           // $data=$form->getData();
           // $decision = new Decision();
           // $decision->setContent($data->getContent());
             //   $decision->setIsTaken($data->getIsTaken());
            //$decision->addContributor($this->getUser());
            $manager->persist($decision);
            $manager->flush();
            $this->addFlash('success','décision crée');
            return $this->redirectToRoute('decision_index');
        }
        return $this->render('decision/create.html.twig',[
            'decisionForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/show",name="decision_show")
     * @param DecisionRepository $decisionRepository
     * @return Response
     */
    public function show(DecisionRepository $decisionRepository): Response{
        $waitingDoc = [];
        $waitingCon = [];
        foreach ($decisionRepository->findNotTaken() as $decision){
            $waitingDoc[]= $decision->getDocuments();
            $waitingCon[]= $decision->getContributors();
        }
        return $this->render('decision/show.html.twig',[
            'waitingCon' => $waitingCon,
            'waitingDoc' => $waitingDoc
        ]);
    }

    /**
     * @Route("/show/{id}", name="decision_show_id", methods={"GET","POST"})
     * @param DecisionRepository $decisionRepository
     * @return Response
     */
    public function showNotTaken(DecisionRepository $decisionRepository,ContributorRepository $contributorRepository,$id):Response{
        $decisions = $decisionRepository->findNotTakenOne($id);
        dump($decisions);die;
        $contributor = $contributorRepository->find($id);
        return $this->render('decision/showme.html.twig',[
            'decisions' => $decisions,
            'id' => $id,
            'contributor' => $contributor
        ]);
    }

    /**
     * @Route("/update/{id}",name="decision_update")
     * @return Response
     */
    public function update(Decision $decision, $id,Request $request):Response
    {
        $form = $this->createForm(DecisionType::class,$decision);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Decision $decision
             */
            $decision = $form->getData();
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('decision_index');
        }
        return $this->render('decision/update.html.twig',
            ['form' => $form->createView()]);
    }
}