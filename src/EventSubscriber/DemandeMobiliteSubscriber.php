<?php
// src/EventSubscriber/DemandeMobiliteSubscriber.php
namespace App\EventSubscriber;

use App\Event\DemandeCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class DemandeMobiliteSubscriber implements EventSubscriberInterface
{
private MailerInterface $mailer;
private Environment    $twig;

public function __construct(MailerInterface $mailer, Environment $twig)
{
$this->mailer = $mailer;
$this->twig   = $twig;
}

public static function getSubscribedEvents(): array
{
return [
DemandeCreatedEvent::NAME => 'onDemandeCreated',
];
}

public function onDemandeCreated(DemandeCreatedEvent $event): void
{
$demande = $event->getDemande();
$email = (new Email())
->from('no-reply@votre-domaine.fr')
->to('responsable@votre-domaine.fr')
->subject('Nouvelle demande de mobilité')
->html($this->twig->render('emails/nouvelle_mobilite.html.twig', [
'demande' => $demande,
]));

$this->mailer->send($email);
}
}
