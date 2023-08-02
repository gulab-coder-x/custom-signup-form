<?php

namespace Kinex\EditForm\Block\Customer;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\View\Element\Template;

class Account extends Template
{
      protected $urlBuilder;
      protected $customerSession;
      protected $storeManager;
      protected $customerModel;
      protected $customerRepository;

      public function __construct(
            Context $context,
            UrlInterface $urlBuilder,
            SessionFactory $customerSession,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Magento\Customer\Model\Customer $customerModel,
            CustomerRepositoryInterface $customerRepository,
            array $data = []
      )
      {
            $this->customerRepository = $customerRepository;
            $this->urlBuilder                  = $urlBuilder;
            $this->customerSession          = $customerSession->create();
            $this->storeManager               = $storeManager;
            $this->customerModel             = $customerModel;

            parent::__construct($context, $data);

            $collection = $this->getContracts();
            $this->setCollection($collection);
      }

      public function getBaseUrl()
      {
            return "http://magentolocal.com/";
      }

      public function getMediaUrl()
      {
            return $this->getBaseUrl() . 'media/';
      }

      public function getCustomerImageUrl($filePath)
      {
            return $this->getMediaUrl() . 'customer/' . $filePath;
      }
      public function getFileName()
      {
            $customerData = $this->customerModel->load($this->customerSession->getId());
            return $customerData->getData('customer_image');
      }
      public function getFileUrl()
      {
            $customerData = $this->customerModel->load($this->customerSession->getId());
            $url = $customerData->getData('customer_image');
            if (!empty($url)) {
                  return $this->getCustomerImageUrl($url);
            }
            return false;
      }

      public function setCustomAttributeValue($value)
      {
          $customer = $this->getCustomer();
          $customer->setCustomAttribute('customer_image', $value);
          $this->customerRepository->save($customer);
      }
}