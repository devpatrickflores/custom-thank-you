<?php
namespace PF\ThankYou\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;

class CmsPages implements ArrayInterface
{
    protected $pageCollectionFactory;

    public function __construct(CollectionFactory $pageCollectionFactory)
    {
        $this->pageCollectionFactory = $pageCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];
        $pages = $this->pageCollectionFactory->create()->addFieldToFilter('is_active', 1);

        foreach ($pages as $page) {
            $options[] = [
                'value' => $page->getIdentifier(),
                'label' => $page->getTitle(),
            ];
        }

        return $options;
    }
}
