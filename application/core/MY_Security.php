<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * Description of MY_Security
 * Extending Security Class
 * @author Arif
 */
class MY_Security extends CI_Security 
{
    function __construct() 
    {
        parent::__construct();
    }
    
    /**
     * Clean and Convert Html Special Characters.
     * @param type $str
     * @param type $is_image
     * @return string
     */
    function xss_clean($str, $is_image = FALSE) 
    {   
        $str = parent::xss_clean($str, $is_image);        
        
        $CI =& get_instance();        
        $CI->load->library("my_functions");        
        $str = $CI->my_functions->filterParameters($str);                
        
        return $str;
    }
}

/** end of file MY_Security.php **/