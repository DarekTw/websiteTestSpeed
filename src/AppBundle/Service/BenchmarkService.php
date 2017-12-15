<?php

namespace AppBundle\Service;

use AppBundle\Model\Website;
use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Swift_Message;

class BenchmarkService
{
    /**
     * Main website
     * @var Website 
     */
    private $website;

    /**
     * Arrray of websites
     * @var array
     */
    private $competitors;

    /**
     * mailer service
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * logger service
     * @var LoggerInterface
     */
    private $logger;

    /**
     * templating service
     */
    private $templating;

    public function __construct(Swift_Mailer $mailer, LoggerInterface $logger, $templating)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->templating = $templating;
        $this->competitors = array();
    }

    /**
     * getWebsite
     * @return Website|null
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * setWebsite
     * @param Website|string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website instanceof Website ? $website : new Website($website);
    }

    /**
     * getCompetitors
     * @return array
     */
    public function getCompetitors()
    {
        return $this->competitors;
    }

    /**
     * setCompetitors
     * @param array $competitors
     */
    public function setCompetitors(array $competitors)
    {
        foreach ($competitors as $cm) {
            $this->competitors[] = $cm instanceof Website ? $cm : new Website($cm);
        }
    }

    /**
     * Benchmark website and all website comptetitors,
     * sort results,
     * send sms or email if website is slow
     */
    public function benchmark()
    {
        $this->logger->info('Benchmark has been started.');

        $this->website->benchmark();

        $this->logger->info("Website: " . $this->website->getDomain() . " | Load time: " . $this->website->getTime() . " s.");

        foreach ($this->competitors as $cm) {
            $cm->benchmark();
        }

        uasort($this->competitors, array(BenchmarkService::class, "compareWebsites"));

        foreach ($this->competitors as $cm) {
            if ($cm->getError()) {
                $this->logger->warning("Website competitor: " . $cm->getDomain() . " | " . $cm->getError());
            } else {
                $this->logger->info("Website competitor: " . $cm->getDomain() . " | Load time: " . $cm->getTime() . " s. | Difference: " . ($cm->getTime() - $this->website->getTime()) . " s.");
            }
        }

        // send email
        if ($this->isSlower()) {
            $message = (new Swift_Message('Website benchmark: tested site is slow'))
                    ->setBody(
                    $this->templating->render(
                            'AppBundle:Emails:slower.html.twig', array('benchmark' => $this)
                    ), 'text/html'
                    )
            ;

            $this->mailer->send($message);
        }

        // send sms
        if ($this->isSlowerTwoTimes()) {
            
        }

        $this->logger->info('Benchmark has finished job.');
    }

    /**
     * Compare two websites by load time
     * 
     * @param Website $a
     * @param Website $b
     * @return int
     */
    static function compareWebsites(Website $a, Website $b)
    {
        if ($a->getTime() == $b->getTime()) {
            return 0;
        }

        return ($a->getTime() < $b->getTime()) ? -1 : 1;
    }

    /**
     * Checks if main website is slower than other websites
     * @return boolean
     */
    public function isSlower()
    {
        foreach ($this->competitors as $cm) {
            if ($this->website->getTime() > $cm->getTime()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if main website is slower as twice as other websites
     * @return boolean
     */
    public function isSlowerTwoTimes()
    {
        foreach ($this->competitors as $cm) {
            if ($this->website->getTime() > $cm->getTime() * 2) {
                return true;
            }
        }

        return false;
    }

}
