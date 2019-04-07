<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 05/04/2019
 * Time: 21:56
 */

namespace App\Controller;
use App\Entity\Contributor;
use App\Form\DecisionType;
use App\Form\DepositContributorType;
use App\Repository\ContributorRepository;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContributorController
 * @Route("/contributor")
 * @package App\Controller
 */

class ContributorController extends AbstractController
{
    /**
     * @Route("/index", name = "contributor_index")
     * @return Response
     */
    public function index(ContributorRepository $contributorRepository): Response{
        $contributors = $contributorRepository->findAll();
        return $this->render('contributor/index.html.twig', [
                        'contributors' => $contributors
                                  ]);
    }

    /**
     * update the contributor's document decision
     * @Route("/update/{id}", name="contributor_update")
     * @return Response
     */
    public function update(Contributor $contributor,DocumentRepository $documentRepository):Response
    {
        /**
         * Verification of the contributor existence
         */
        if ($contributor != null) {
            /**
             * Searching the contributor's waiting documents
             */
            $waitingDocs = $documentRepository->findAllContributorWaitingDocs($contributor->getId());
            /**
             * Rendering the contributor's waiting documents for deposit
             */
            return $this->render('contributor/waiting.html.twig', [
                'contributor' => $contributor,
                'waitingdocs' => $waitingDocs
            ]);
        }
        //return $this->redirectToRoute('contributor_update');
    }

    /**
     * @Route("/show/{id}", name="contributor_show")
     * @return Response
     */
    public function show(Contributor $contributor,DocumentRepository $documentRepository,Request $request): Response{

        $waitingdocs = $documentRepository->findAllContributorWaitingDocs($contributor->getId());
        /**
         * Updating the documents contributor by the waiting documents only
         */
        foreach ($contributor->getDocuments() as $document)
             $contributor->removeDocument($document);
        foreach ($waitingdocs as $document) {
            $contributor->addDocument($document);
        }
        $form = $this->createForm(DepositContributorType::class,$contributor);

        return $this->render('contributor/show.html.twig',[
                                             'form' => $form->createView()
            ]);
    }
}