<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    #[Route('/listeRegion', name: 'listeRegion')]
    public function listeRegion(SerializerInterface $serializer): Response
    {
        $mesRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        // $mesRegionsTab=$serializer->decode($mesRegions, 'json');
        // $mesRegionsObjet=$serializer->denormalize($mesRegionsTab, 'App\Entity\Region[]');
        $mesRegions=$serializer->deserialize($mesRegions, 'App\Entity\Region[]', 'json');
        return $this->render('api/index.html.twig',[
            'mesRegions'=>$mesRegions
        ]);

    }

    // #[Route('/listeDepsParRegion', name: 'listeDepsParRegion')]
    // public function listeDepsParRegion(SerializerInterface $serializer, Request $request): Response
    // {
    //     $codeRegion = $request->query->get('region');

    //     $mesRegionsJson = file_get_contents('https://geo.api.gouv.fr/regions');
    //     $mesRegions = $serializer->deserialize($mesRegionsJson, 'App\Entity\Region[]', 'json');

    //     $mesDeps = null;

    //     if ($codeRegion !== null && $codeRegion !== "Toutes") {
    //         $mesDepsJson = file_get_contents('https://geo.api.gouv.fr/regions/' . $codeRegion . '/departements');
    //         $mesDeps = $serializer->decode($mesDepsJson, 'json');
    //     }

    //     return $this->render('api/listDepsParRegion.html.twig', [
    //         'mesRegions' => $mesRegions,
    //         'mesDeps' => $mesDeps
    //     ]);
    // }


    #[Route('/listeDepsParRegion', name: 'listeDepsParRegion')]
    public function listeDepsParRegion(SerializerInterface $serializer, Request $request): Response
    {
        $codeRegion=$request->query->get('region');
        $mesRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegions=$serializer->deserialize($mesRegions, 'App\Entity\Region[]', 'json');

        if ($codeRegion == null || $codeRegion == "Toutes") {
            $mesDeps=file_get_contents('https://geo.api.gouv.fr/regions');
        }else{
            $mesDeps=file_get_contents('https://geo.api.gouv.fr/regions/' . $codeRegion . '/departements');
        }

        $mesDeps = $serializer->decode($mesDeps,'json');
        return $this->render('api/listDepsParRegion.html.twig',[
            'mesRegions'=>$mesRegions,
            'mesDeps'=>$mesDeps
        ]);

    }
}
