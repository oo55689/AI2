<?php

namespace App\Controller;

use App\Form\ExternalAPIType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalAPIController extends AbstractController
{
    #[Route('/external-api', name: 'app_external_api')]
    public function index(
        Request $request,
        HttpClientInterface $httpClient
    ): Response {
        $form = $this->createForm(ExternalAPIType::class);
        $form->handleRequest($request);

        $weatherData = null;
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $latitude = $data['latitude'];
            $longitude = $data['longitude'];

            $apiUrl = sprintf(
                'https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s&daily=temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=UTC',
                $latitude,
                $longitude
            );

            try {
                $response = $httpClient->request('GET', $apiUrl);
                $weatherData = $response->toArray();
            } catch (\Exception $e) {
                $error = 'Nie udało się pobrać prognozy pogody.';
            }
        }

        return $this->render('external_api/index.html.twig', [
            'form' => $form->createView(),
            'weatherData' => $weatherData,
            'error' => $error,
        ]);
    }
}
