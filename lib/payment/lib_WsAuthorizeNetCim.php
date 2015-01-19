<?php 
/**
 * Easily interact with the Authorize.Net CIM XML API.
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetCIM
 * @link       http://www.authorize.net/support/CIM_XML_guide.pdf CIM XML Guide
 */



/**
 * A class to send a request to the CIM XML API.
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetCIM
 */ 
class WsAuthorizeNetCim extends AuthorizeNetCIM {
	
	const LIVE_URL = "https://api.authorize.net/xml/v1/request.api";
    const SANDBOX_URL = "https://apitest.authorize.net/xml/v1/request.api";

    
    private $_xml;
    private $_refId = false;
    private $_validationMode = "none"; // "none","testMode","liveMode"
    private $_extraOptions;
    private $_transactionTypes = array(
        'AuthOnly',
        'AuthCapture',
        'CaptureOnly',
        'PriorAuthCapture',
        'Refund',
        'Void',
    );
    
	/**
     * Get a customer profile authorization token
     *
     * @param int $customerProfileId
     *
     * @return AuthorizeNetCIM_Response
     */
	public function getHostedProfilePage($customerProfileId) 
	{
		$this->_constructXml("getHostedProfilePageRequest");
        $this->_xml->addChild("customerProfileId", $customerProfileId);
        return $this->_sendRequest();	
	}
	
	/**
     * Start the SimpleXMLElement that will be posted.
     *
     * @param string $request_type The action to be performed.
     */
    private function _constructXml($request_type) 
    {
    	// Disable showing warnings on the screen
    	libxml_use_internal_errors(true);
        $string = '<?xml version="1.0" encoding="utf-8"?><'.$request_type.' xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd"></'.$request_type.'>';
        
        $this->_xml = @new SimpleXMLElement($string);
        $merchant = $this->_xml->addChild('merchantAuthentication');
        $merchant->addChild('name',$this->_api_login);
        $merchant->addChild('transactionKey',$this->_transaction_key);
        ($this->_refId ? $this->_xml->addChild('refId',$this->_refId) : "");
    }
    
	/**
     * Prepare the XML post string.
     */
    protected function _setPostString()
    {
        ($this->_validationMode != "none" ? $this->_xml->addChild('validationMode',$this->_validationMode) : "");
        $this->_post_string = $this->_xml->asXML();
        
        // Add extraOptions CDATA
        if ($this->_extraOptions) {
            $this->_xml->addChild("extraOptions");
            $this->_post_string = str_replace("<extraOptions></extraOptions>",'<extraOptions><![CDATA[' . $this->_extraOptions . ']]></extraOptions>', $this->_xml->asXML());
            $this->_extraOptions = false;
        }
        // Blank out our validation mode, so that we don't include it in calls that
        // don't use it.
        $this->_validationMode = "none";
    }
}