<?php

namespace AppBundle\Service;

interface MailerInterface {
    public function sendWebsiteIsSlowEmail(BenchmarkInterface $benchmark);
}

