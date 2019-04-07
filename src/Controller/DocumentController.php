<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 05/04/2019
 * Time: 21:56
 */

namespace App\Controller;


use App\Entity\Decision;
use App\Entity\Document;
use App\Form\DecisionType;
use App\Form\DocumentType;
use App\Repository\ContributorRepository;
use App\Repository\DecisionRepository;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentController
 * @Route("/document")
 * @package App\Controller
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/index", name="document_index")
     * @param DocumentRepository $documentRepository
     * @return Response
     */
    public function index(DocumentRepository $documentRepository):Response{
        $documents  = $documentRepository->findAll();
        return $this->render('document/index.html.twig',[
            'documents'=> $documents
        ]);
    }
    /**
     * @Route("/create", name="document_create")
     * @return Response
     *
     */
    public function create(DecisionRepository $decisionRepository,Request $request) : Response{

        $manager = $this->getDoctrine()->getManager();
        //récupérer la décision qui doit être associée par défaut au document
        //$default = $decisionRepository->find(16);
    $document = new Document();
         $form = $this->createForm(DocumentType::class,$document);
        // $form = $this->createForm(DocumentType::class,$document, [
     //                                                     'default'=> $default]);
    $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var Document $document
             */
            $document = $form->getData();
            $manager->persist($document);
            $manager->flush();
            $this->addFlash('success','document crée');
            return $this->redirectToRoute('document_index');
        }
    return $this->render('document/create.html.twig',[
        'form'=>$form->createView()
    ]);
}
    /**
     * A function that update the decision related to the $id document
     * @Route("/update/{id}", name="document_update")
     * @return Response
     */
    public function update(DocumentRepository $documentRepository, $id, Request $request):Response{
        $manager = $this->getDoctrine()->getManager();
        $document = $documentRepository->find($id);
        $decision = $document->getDecision();
        $form = $this->createForm(DecisionType::class,$decision);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var Decision $decision
             */
            $decision = $form->getData();
            switch ($decision->getDeposit()){
                case 'oui' : $decision->setIsTaken(true);$decision->setContent('Dépôt');break;
                case 'non' : $decision->setIsTaken(false);$decision->setContent('Refus Dépôt');break;
                default : $decision->setIsTaken(null);$decision->setContent('En attente');break;
            }
              //dump($decision);die;
            $manager->persist($decision);
            $manager->flush();
            $this->addFlash('success','document décision modifiée');
            return $this->redirectToRoute('document_index');
        }
        return $this->render('document/update.html.twig',[
            'document' =>$document,
            'decision' => $decision,
            'form'=>$form->createView()
        ]);
    }
    /**
     * La liste des documents en attente lie au contributor $id
     * @Route("/show/{id}", name="waiting_document_contributor")
     * @param DocumentRepository $documentRepository
     * @param $id
     * @return Response
     */
    public function showme(ContributorRepository $contributorRepository,DocumentRepository $documentRepository,$id):Response{
        $contributor = $contributorRepository->find($id);
        $waitingDocs = $documentRepository->findAllContributorWaitingDocs($id);
         //dump($waitingDocs);die;
     return $this->render('document/showme.html.twig', [
                        'contributor' => $contributor,
                        'waitingdocs' => $waitingDocs
                            ]);
    }
    /**
     * Une route vers la liste des documents en attente
     * @Route("/show" , name="document_waiting_show")
     * @param DocumentRepository $documentRepository
     * @return Response
     */
    public function show(DocumentRepository $documentRepository) : Response{
    $documents = $documentRepository->findAllDocumentsNotTaken();
    //dump($documents);die;
    return $this->render('document/show.html.twig',
        ['documents'=> $documents]);
    }

}