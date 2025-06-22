<?php
namespace Strativ\ProductTags\Controller\Adminhtml\Tag;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Index action for Product Tags grid in admin panel.
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute action to display Product Tags grid.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Strativ_ProductTags::producttags');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Tags'));
        return $resultPage;
    }

    /**
     * Check if the user is allowed to access this action.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Strativ_ProductTags::producttags');
    }
}