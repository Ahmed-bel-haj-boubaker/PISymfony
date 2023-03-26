<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;

/**
 * @Route("/sentiment") 
 */
#[Route('/sentiment')]
class SentimentController extends AbstractController
{
    #[Route('/{text}', name: 'app_sentiment')]
    public function analyze($text)
{
    $client = new Client([
        'headers' => [
            'content-type' => 'application/json',
            'X-RapidAPI-Key' => 'a34f684eccmsh9cde57f933b4198p10570bjsn30410847e2a1',
            'X-RapidAPI-Host' => 'text-analysis12.p.rapidapi.com'
        ]
    ]);

    $response = $client->post('https://text-analysis12.p.rapidapi.com/sentiment-analysis/api/v1.1', [
        'json' => [
            'language' => 'english',
            'text' => $text
        ]
    ]);

    $result = json_decode($response->getBody()->getContents(), true);

    return new Response($result['sentiment']);
}
}