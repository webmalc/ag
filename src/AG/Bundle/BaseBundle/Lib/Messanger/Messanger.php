<?php

namespace AG\Bundle\BaseBundle\Lib\Messanger;

use AG\Bundle\BaseBundle\Lib\Messanger\Providers\ProviderInterface;

class Messanger
{
    /**
     * @var ProviderInterface[] 
     */
    private $providers = [];

    /**
     * @param \AG\Bundle\BaseBundle\Lib\Messanger\Providers\ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[$provider->getName()] = $provider;
    }
    
    /**
     * Send messange by all providers
     * @return string[]
     */
    public function send()
    {
        $result = [];
        
        foreach ($this->providers as $provider) {
            
            $result[$provider->getName()]['success'] = true;
            
            try {
                $provider->send();
            } catch (\Exception $ex) {
                $result[$provider->getName()]['success'] = false;
                $result[$provider->getName()]['exception'] = $ex;
            }
        }
        
        return $result;
    }
}
