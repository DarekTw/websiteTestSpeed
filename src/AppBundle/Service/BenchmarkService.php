<?php

namespace AppBundle\Service;

use AppBundle\Model\Website;
use Http\Client\Common\Plugin\StopwatchPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Http\Message\MessageFactory;

class BenchmarkService implements BenchmarkInterface
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
     * logger service
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MessageFactory
     */
    private $httplug_message_factory;
    
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(LoggerInterface $logger, MessageFactory $httplug_message_factory, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->httplug_message_factory = $httplug_message_factory;
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

        $this->benchmarkSingleWebsite($this->website);

        foreach ($this->competitors as $cm) {
            /* @var $cm Website */
            $this->benchmarkSingleWebsite($cm);
        }

        uasort($this->competitors, array(BenchmarkService::class, "compareWebsites"));

        // send email
        if ($this->isSlower()) {
            $this->mailer->sendWebsiteIsSlowEmail($this);
        }

        // send sms
        if ($this->isSlowerTwoTimes()) {
            
        }

        $this->logger->info('Benchmark has finished job.');
    }

    protected function benchmarkSingleWebsite(Website $website){
        $request = $this->httplug_message_factory->createRequest('GET', $website->getDomain());
        $stopwatch = new Stopwatch();
        $stopwatchPlugin = new StopwatchPlugin($stopwatch);
        $pluginClient = new PluginClient(
                HttpClientDiscovery::find(), [$stopwatchPlugin]
        );
        $response = $pluginClient->sendRequest($request);
        $eventName = "GET ".$website->getDomain();
        $event = $stopwatch->getEvent($eventName);
        $this->logger->info(sprintf('Request %s took %s ms and used %s bytes of memory', $eventName, $event->getDuration(), $event->getMemory()));
        $website->setTime($event->getDuration());
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

        // if a is 0 or null a is always greatest
        if (is_null($a->getTime())) {
            return 1;
        }

        // if b is null b is always greatest
        if (is_null($b->getTime())) {
            return -1;
        }

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
