<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 07/04/2019
 * Time: 21:06
 */

namespace App\Controller;


use App\Entity\Conference;
use App\Entity\Speaker;
use App\Form\ConferenceType;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConferenceController
 * @Route("/conference")
 * @package App\Controller
 */
class ConferenceController extends AbstractController
{
    /**
     * @Route("/index", name="conference_index")
     * @return Response
     */
    public function index(ConferenceRepository $repository): Response{
        $conferences = $repository->findAll();
        return $this->render('conference/index.html.twig',['conferences' => $conferences]);
    }
    /**
     * @Route("/create", name="conference_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) :Response{
        $conference = new Conference();
        $speaker1 = new Speaker();
        $speaker1->setNamespeaker('Khaoula abaidi');
        $speaker2 = new Speaker();
        $speaker2->setNamespeaker('Bruno Marmol');
        $conference->addSpeaker($speaker1);$conference->addSpeaker($speaker2);
        $form = $this->createForm(ConferenceType::class,$conference);
        $form->handleRequest($request);
        dump($form->getData());
        if($form->isSubmitted() && $form->isValid()){
            $manager= $this->getDoctrine()->getManager();
            $manager->persist($speaker1);$manager->persist($speaker2);
            $manager->persist($conference);
            $manager->flush();
            return $this->redirectToRoute('conference_index');
        }
        return $this->render('conference/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}