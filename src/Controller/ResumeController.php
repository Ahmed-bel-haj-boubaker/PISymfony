<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;


/**
 * @Route("/resume") 
 */
#[Route('/resume')]
class ResumeController extends AbstractController
{
    #[Route('/{text}', name: 'app_resume')]
    public function resume($text)
    {
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'X-RapidAPI-Key' => 'a34f684eccmsh9cde57f933b4198p10570bjsn30410847e2a1',
                'X-RapidAPI-Host' => 'text-analysis12.p.rapidapi.com'
            ]
        ]);
    
        $response = $client->post('https://text-analysis12.p.rapidapi.com/summarize-text/api/v1.1', [
            'json' => [
                'language' => 'english',
                'summary_percent' => 30,
                'text' => $text
            ]
        ]);
    
        $result = json_decode($response->getBody()->getContents(), true);
            
        return new Response($result['summary']);
    }
}
