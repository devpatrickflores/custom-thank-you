<?php
namespace PF\ThankYou\Plugin\Newsletter\Subscriber;

use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Newsletter\Controller\Subscriber\NewAction as SubscriberNewAction;
use Magento\Newsletter\Model\SubscriberFactory;

class NewActionPlugin
{
    protected $response;
    protected $url;
    protected $scopeConfig;
    protected $resultFactory;
    protected $subscriberFactory;

    public function __construct(
        ResponseHttp $response,
        UrlInterface $url,
        ScopeConfigInterface $scopeConfig,
        ResultFactory $resultFactory,
        SubscriberFactory $subscriberFactory
    ) {
        $this->response = $response;
        $this->url = $url;
        $this->scopeConfig = $scopeConfig;
        $this->resultFactory = $resultFactory;
        $this->subscriberFactory = $subscriberFactory;
    }

    public function afterExecute(SubscriberNewAction $subject, $resultRedirect)
    {
        $email = $subject->getRequest()->getParam('email');

        if ($email) {
            $existingSubscriber = $this->subscriberFactory->create()->loadByEmail($email);
            if ($existingSubscriber->getId()) {
              	// Subscriber exists, redirect to custom thank you URL
                $thankYouPageUrl = $this->scopeConfig->getValue('thankyou/general/newsletter_url');
                $url = $this->url->getUrl($thankYouPageUrl);
                $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($url);
            } else {
                // Subscriber does not exist, redirect to the homepage
                $url = $this->url->getUrl('/');
                $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($url);
            }
        }
      
        return $resultRedirect;
    }
}
