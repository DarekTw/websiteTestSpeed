<?php

namespace AppBundle\Controller;

use AppBundle\Form\WebsiteForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\BenchmarkService;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /* @var $comparator BenchmarkService */
        $benchmark = $this->get(BenchmarkService::class);
        $showResults = false;
        $form = $this->createForm(WebsiteForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $benchmark->setWebsite($data['website']);
            $benchmark->setCompetitors($data['competitors']);
            $benchmark->benchmark();
            $showResults = true;
        }
        
        return $this->render('AppBundle:default:index.html.twig', [
                    'form' => $form->createView(),
                    'benchmark' => $benchmark,
                    'showResults' => $showResults
        ]);
    }

}
