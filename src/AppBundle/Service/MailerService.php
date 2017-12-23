<?php

namespace AppBundle\Service;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

class MailerService implements MailerInterface
{
    /**
     * templating service
     * @var EngineInterface
     */
    private $templating;

    /**
     * mailer service
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendWebsiteIsSlowEmail(BenchmarkInterface $benchmark)
    {
        $message = (new Swift_Message('Website benchmark: tested site is slow'))
                ->setBody(
                $this->templating->render(
                        'AppBundle:Emails:slower.html.twig', array('benchmark' => $benchmark)
                ), 'text/html'
                )
        ;

        $this->mailer->send($message);
    }

}
