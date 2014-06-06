<?php
 
namespace Ag\Bundle\UserBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
 
class StatsExtension extends \Twig_Extension
{
 
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;
    
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager 
     */
    protected $dm;
    
    /**
     * @var \AG\Bundle\UserBundle\Document\Statistics; 
     */
    protected $stats = null;
 
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->dm = $this->container->get('doctrine_mongodb');
        $stats = $this->dm->getRepository('AGUserBundle:Statistics')->findAll();
 
        if(!empty($stats[0])) {
            $this->stats = $stats[0];
        }
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'ag_stats_extension';
    }
    
    public function carsTotal()
    {
        if(!$this->stats) {
            return 0;
        }
        return $this->stats->getCarsTotal();
    }
    
    public function carsToday()
    {
        if(!$this->stats) {
            return 0;
        }
        return $this->stats->getCarsToday();
    }
    
    public function messagesTotal()
    {
        if(!$this->stats) {
            return 0;
        }
        return $this->stats->getMessagesTotal();
    }
    
    public function messagesToday()
    {
        if(!$this->stats) {
            return 0;
        }
        return $this->stats->getMessagesToday();
    }
    
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'cars_total' => new \Twig_Function_Method($this, 'carsTotal', array('is_safe' => array('html'))),
            'cars_today' => new \Twig_Function_Method($this, 'carsToday', array('is_safe' => array('html'))),
            'messages_total' => new \Twig_Function_Method($this, 'messagesTotal', array('is_safe' => array('html'))),
            'messages_today' => new \Twig_Function_Method($this, 'messagesToday', array('is_safe' => array('html'))),
        );
    }
 
}