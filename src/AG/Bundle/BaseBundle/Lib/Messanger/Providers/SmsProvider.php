<?php

namespace AG\Bundle\BaseBundle\Lib\Messanger\Providers;

/**
 * Send message by sms (Epochta)
 */
class SmsProvider extends AbstractProvider
{
    //http://atompark.com/api/sms/3.0/sendSMS?key=public_key&sum=control_sum&sender=Info&text=Testing%20SMS&phone=380972920383&datetime=&sms_lifetime=0
    
    /**
     * Name of provider
     */
    const NAME = 'sms';
    
    /**
     * Service url
     */
    const URL = 'http://atompark.com/api/sms/';
    
    /**
     * Service version
     */
    const VERSION = '3.0';
    
    /**
     * Service action
     */
    const ACTION = 'sendSMS';
    
    /**
     * Sender Id
     */
    const SENDER = 'autoGRU';

    /**
     * Service private key
     */
    const PRIVATE_KEY = '9ac07c0f63100fd3ca6c7823d537c666';
    
    /**
     * Service public key
     */
    const PUBLIC_KEY = 'f9bffbb49e41548d7b2ae7903945f056';

    /**
     * SMS max length
     */
    const CROP = 70;
    
    /**
     * Test mode on/off
     */
    const TEST = true;
    
    /**
     * Crop sms or not
     * @var boolean
     */
    private $crop = true;

    /**
     * {@inheritdoc}
     */
    public function getText()
    {
        $text = $this->getSubject() . '. ' . $this->getData()['content'];
        
        if ($this->crop) {
            $text = mb_substr($text, 0, self::CROP - mb_strlen(self::SENDER, 'UTF-8') - 1, 'UTF-8');
        }
        
        return $text;
    }
    
    /**
     * @param boolean $crop
     * @return \AG\Bundle\BaseBundle\Lib\Messanger\Providers\SmsProvider
     */
    public function setCrop($crop)
    {
        $this->crop = (empty($crop)) ? false : true;
        
        return $this;
    }

    /**
     * {@inheridoc}
     */
    public function send()
    {
        parent::send();
        
        $result = $this->execCommad();
        
        if (!isset($result['result']) || $result['result'] == 'false') {
            if(isset($result['error'])) {
                throw new \Exception($result['error'], $result['code']);
            } else {
                throw new \Exception('Ошибка отправки смс');
            }
        }

        return true;
    }
    
    /**
     * Send curl request
     * @return string[]|boolean
     * @throws \Exception
     */
    public function execCommad()
    {
        $params = [
            'key'          => self::PUBLIC_KEY,
            'datetime'     => '',
            'sms_lifetime' => 1,
            'sender'       => self::SENDER,
            'phone'        => $this->getRecipient(),
            'text'         => $this->getText()
        ];
        
        if (self::TEST) {
            $params['test'] = 1;
        }
        
        $params['sum'] = $this->getControlSum($params);
        
        $curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_URL, self::URL . self::VERSION . '/' . self::ACTION);
	$result = curl_exec($curl);
        
	if(curl_errno($curl)) {
            throw new \Exception(curl_error($curl), curl_errno($curl));
        }
        return json_decode($result, true);
    }
    
    /**
     * Calculate control sum
     * @param string[] $params
     * @return string
     */
    private function getControlSum($params)
    {
        $params['version'] = self::VERSION;
        $params['action'] = self::ACTION;
        ksort($params);
        $sum = implode('', $params);
        $sum .= self::PRIVATE_KEY;
        return md5($sum);
    }
}
