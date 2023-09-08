<?php
namespace PF\ThankYou\Plugin\Contact;

use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Contact\Controller\Index\Post as ContactPostController;

class PostPlugin
{
    protected $response;
    protected $url;
    protected $scopeConfig;
    protected $resultFactory;

    public function __construct(
        ResponseHttp $response,
        UrlInterface $url,
        ScopeConfigInterface $scopeConfig,
        ResultFactory $resultFactory
    ) {
        $this->response = $response;
        $this->url = $url;
        $this->scopeConfig = $scopeConfig;
        $this->resultFactory = $resultFactory;
    }

    public function afterExecute(ContactPostController $subject, $resultRedirect)
    {
      	$email = $subject->getRequest()->getParam('email');
        if ($email) {
            // Get the custom thank you page URL from configuration
            $thankYouPageUrl = $this->scopeConfig->getValue('thankyou/general/contact_url');

            if ($thankYouPageUrl) {
                // Redirect to the custom thank you page
                $url = $this->url->getUrl($thankYouPageUrl);
                $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($url);
                return $resultRedirect;
            }
        }

        return $resultRedirect;
    }
}
