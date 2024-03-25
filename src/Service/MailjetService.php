<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class MailjetService
{
    protected $publicKey;
    protected $secretKey;

    public function __construct(
        string $mjPublicKey,
        string $mjSecretKey
    ) {
        $this->secretKey = $mjSecretKey;
        $this->publicKey = $mjPublicKey;
    }

    /**
     * Envoi des email via l'api Mailjet
     */
    public function sendNotification(
        string $to_email,
        string $to_name,
        string $subject,
        array $variables
    ) {
        $mj = new Client($this->publicKey, $this->secretKey, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "lekokeliko@gmail.com",
                        'Name' => "OrganiZe"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 5818045,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => $variables
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dump($response->getData());
    }
}
