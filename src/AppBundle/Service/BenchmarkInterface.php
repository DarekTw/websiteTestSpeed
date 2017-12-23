<?php

namespace AppBundle\Service;

use AppBundle\Model\Website;

interface BenchmarkInterface {
    /**
     * getWebsite
     * @return Website|null
     */
    public function getWebsite();

    /**
     * setWebsite
     * @param Website|string $website
     */
    public function setWebsite($website);

    /**
     * getCompetitors
     * @return array of Websites
     */
    public function getCompetitors();

    /**
     * setCompetitors
     * @param array $competitors
     */
    public function setCompetitors(array $competitors);

    /**
     * Benchmark website and all website comptetitors,
     * sort results,
     * send sms or email if website is slow
     */
    public function benchmark();
}

