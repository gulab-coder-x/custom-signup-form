<?php
namespace Kinex\EditForm\Block;
class Customer extends \Magento\Framework\View\Element\Template
{
	protected $httpContext;

	public function __construct(
    	\Magento\Framework\View\Element\Template\Context $context,
    	\Magento\Framework\App\Http\Context $httpContext,
    	array $data = []
	) {
    	$this->httpContext = $httpContext;
    	parent::__construct($context, $data);
	}

	public function getCustomerIsLoggedIn()
	{
    	return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
	}

	public function getcustomerRole()
	{
    	return $this->httpContext->getValue('customer_role');
	}
	public function getcustomerId()
	{
    	return $this->httpContext->getValue('customer_id');
	}
}
