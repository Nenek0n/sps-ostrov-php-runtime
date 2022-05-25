<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Model\ChristmasTree;

class TreeController extends AbstractController
{

    /**
     * @Route("/tree", name="term")
     */
     
    public function tree(Request $request): Response
    {
        $response = $this->render('term.html.twig', [
            "treeUrl" => $this->generateUrl("tree-data", ["treeId" => 456]),
        
        ]);
        return $response;
    }
    
    /**
     * @Route("/tree-data/{treeId}", name="tree-data")
     */
     
    public function treeData(string $treeId, Request $request): Response
    {
        $tree = new ChristmasTree;
        $tree->putStar(1);
        $tree->putGift("Adam", 9, 6);
        $rows = (int)$request->query->get("rows", "80");
        $cols = (int)$request->query->get("cols", "25");
        $redraw = strtolower($request->query->get("redraw", "false"));
        $output = $tree->getTerminalOutput($rows, $cols, $redraw === "true");
        $response = new Response($output);
        $response->headers->set("Content-Type", "text/plain");
        return $response;
    }
}
