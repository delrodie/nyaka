<?php

namespace App\Controller;

use App\Service\AllRepositories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/webhook')]
class WebhookController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_webhook_handlewebhook', methods: ['POST'])]
    public function handleWebhook(Request $request)
    {
        $wave_webhook_secret = $this->getParameter('wave_webhook_secret');
        $wave_signature = $request->headers->get('Wave-Signature');
        $webhook_body = $request->getContent();

//        dd($webhook_body);

        $webhook_json = $this->webhook_verify_signature_and_decode($wave_webhook_secret, $wave_signature, $webhook_body);

        // Vous pouvez maintenant utiliser les données du webhook pour traiter la demande :
        $webhook_type = $webhook_json->type;
        $webhook_data = $webhook_json->data;

//        $aspirant = $this->allRepositories->getAspirantByWaveId($webhook_data['id']);
//        if (!$aspirant) return new Response('Webhook echec', Response::HTTP_OK);
//
//        $aspirant->setWaveCheckoutStatus($webhook_data['checkout_status']);
//        $aspirant->setWavePaymentStatus($webhook_data['payment_status']);
//        $aspirant->setWaveWhenCompleted($webhook_data['when_completed']);
//        $aspirant->setWaveTransactionId($webhook_data['transaction_id']);
//
//        $this->entityManager->flush();

        return new Response('Webhook handled', Response::HTTP_OK);
    }

    private function webhook_verify_signature_and_decode($wave_webhook_secret, $wave_signature, $webhook_body)
    {
        $parts = explode(",", $wave_signature);
        $timestamp = explode("=", $parts[0])[1];
//        $timestamp = explode("=", $parts[0]);

        $signatures = array();
        foreach (array_slice($parts, 1) as $signature) {
            $signatures[] = explode("=", $signature)[1];
        }

        $computed_hmac = hash_hmac("sha256", $timestamp . $webhook_body, $wave_webhook_secret);
        $valid = in_array($computed_hmac, $signatures);

        if($valid) {
            return json_decode($webhook_body);
        } else {
            throw new \Exception("Invalid webhook signature.");
        }
    }
}