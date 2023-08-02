<?php

namespace Kinex\EditForm\Observer\Customer;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Image\AdapterFactory;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class RegisterSuccess implements \Magento\Framework\Event\ObserverInterface
{
    protected $_request;
    protected $_logger;
    protected $customerRepository;
    protected $_uploaderFactory;
    protected $_varDirectory;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $_uploaderFactory,  
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        array $data = []
    ) {
        $this->_request = $request;
        $this->_logger = $logger;
        $this->_uploaderFactory = $_uploaderFactory;
        $this->_varDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->customerRepository = $customerRepository;
    }
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $customer = $observer->getEvent()->getCustomer();
        $this->_logger->debug($customer->getId());
        $customer=$this->customerRepository->getById($customer->getId());


        $customText = $this->_request->getPost('customText');
        $files = $this->_request->getFiles();
        $this->_logger->debug($files['upload_document']["name"]);

        if(isset($files['upload_document']['name'])) {
            $uploader = $this->_uploaderFactory->create(['fileId' => 'upload_document']);
            $workingDir = $this->_varDirectory->getAbsolutePath('customer/');
            $result = $uploader->save($workingDir);
            $imagePath = $result['file'];
            $data['upload_document'] = $imagePath;
            $customer->setCustomAttribute("customer_image",$data);
        }
        $customer->setCustomAttribute("customText",$customText);
        
        $this->customerRepository->save($customer);
    }

   
      

}
