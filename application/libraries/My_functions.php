<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class My_functions {
    
    function addCss($filename,$set_base_folder = false){
        $css = '';
        if(!$set_base_folder){
            $css = "<link rel = \"stylesheet\" media = \"screen\" href=\"".base_url().ASSETS_FOLDER."css/".$filename."\" charset=\"utf-8\" />";
        }else{
            $css = "<link rel = \"stylesheet\" media = \"screen\" href=\"".base_url().ASSETS_FOLDER.$filename."\" charset=\"utf-8\" />";
        }
        return $css;
    }
    
    function addJs($filename, $set_base_folder = false){
        $js ="";
        if(!$set_base_folder){
            $js = "<script type=\"text/javascript\" src=\"".base_url().ASSETS_FOLDER."js/".$filename."\" charset=\"utf-8\"></script>";
        }else{
            $js = "<script type=\"text/javascript\" src=\"".base_url().ASSETS_FOLDER.$filename."\" charset=\"utf-8\"></script>";
        }        
        return $js;
    }
    
    public function filterParameters($array) {
            /** get CI instances for ci library usage **/
            $CI =& get_instance();
            // Check if the parameter is an array
            if(is_array($array)) {
                // Loop through the initial dimension
                foreach($array as $key => $value) {
                    // Check if any nodes are arrays themselves
                    if(is_array($array[$key]))
                        // If they are, let the function call itself over that particular node
                        $array[$key] = $this->filterParameters($array[$key]);
               
                    // Check if the nodes are strings
                    if(is_string($array[$key]))
                        // If they are, perform the real escape function over the selected node
                        $array[$key] = htmlspecialchars(trim($array[$key]), ENT_QUOTES, $CI->config->item('charset'));
                }           
            }
            // Check if the parameter is a string
            if(is_string($array))
                // If it is, perform a  mysql_real_escape_string on the parameter
                $array = htmlspecialchars(trim($array), ENT_QUOTES, $CI->config->item('charset'));
           
            // Return the filtered result
            return $array;       
    }
    
    public function filterParameters2($array) {
            /** get CI instances for ci library usage **/
            $CI =& get_instance();
            // Check if the parameter is an array
            if(is_array($array)) {
                // Loop through the initial dimension
                foreach($array as $key => $value) {
                    // Check if any nodes are arrays themselves
                    if(is_array($array[$key]))
                        // If they are, let the function call itself over that particular node
                        $array[$key] = $this->filterParameters($array[$key]);
               
                    // Check if the nodes are strings
                    if(is_string($array[$key]))
                        // If they are, perform the real escape function over the selected node
                        $array[$key] = htmlspecialchars(trim($array[$key]), ENT_COMPAT , $CI->config->item('charset'));
                }           
            }
            // Check if the parameter is a string
            if(is_string($array))
                // If it is, perform a  mysql_real_escape_string on the parameter
                $array = htmlspecialchars(trim($array), ENT_COMPAT , $CI->config->item('charset'));
           
            // Return the filtered result
            return $array;       
    }
    
     public function filterInverterCommas($array) {
            /** get CI instances for ci library usage **/
            $CI =& get_instance();
            // Check if the parameter is an array
            if(is_array($array)) {
                // Loop through the initial dimension
                foreach($array as $key => $value) {
                    // Check if any nodes are arrays themselves
                    if(is_array($array[$key]))
                        // If they are, let the function call itself over that particular node
                        $array[$key] = $this->filterInverterCommas($array[$key]);                       
               
                    // Check if the nodes are strings
                    if(is_string($array[$key]))
                        // If they are, perform the real escape function over the selected node                        
                        $array[$key] = str_replace(array('"', '\''),  array('&quot;', '&apos;'), $array[$key]);                    
                }           
            }
            // Check if the parameter is a string
            if(is_string($array))
                // If it is, perform a  mysql_real_escape_string on the parameter
                //$array = htmlspecialchars(trim($array), ENT_QUOTES, $CI->config->item('charset'));
                $array = str_replace(array('"', '\''),  array('&quot;', '&apos;'), $array);
           
            // Return the filtered result
            return $array;       
    }
    
    function convertDate($date){
        if($date == null || $date == ""){
            return $date="";
        }else{
            $dateArray = explode("-", $date);
            $date = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
            return $date;
        }    
    }
    
    function fetchTokenNumber($tokenNumber)
    {
        $tokenNumber = explode('-', $tokenNumber);
        return array_pop($tokenNumber);
    }
    
    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    function validateBankingHours($date)
    {
        $hour = explode(" ", $date);
        $hour = explode(":",$hour[1]);
        $hour = $hour[0];
        
        if($hour < 10 || $hour > 16):
            return false;
        endif;
        return true;
    }
    
    function validateWorkingDays($date)
    {
        $date = new DateTime($date);
        if(strtolower($date->format('l')) == "friday"):
            return false;
        endif;
        return TRUE;
    }
    
    function createRange($startDate, $endDate) 
    {
        $tmpDate = new DateTime($startDate);
        $tmpEndDate = new DateTime($endDate);

        $outArray = array();
        do {
            $outArray[] = $tmpDate->format('Y-m-d');
        } while ($tmpDate->modify('+1 day') <= $tmpEndDate);

        return $outArray;
    }
    
    /**
     * Build Api Url for online integration with BAT and CIBL Common interface
     * @param object $branchInfo
     * @param int $categoryId
     * @param string $apiSuffix
     * @return string
     */
    function build_api_url($branchInfo, $categoryId, $apiSuffix)
    {
        $categoryIdUrl = ((int)$categoryId <= 0) ? "" : $categoryId;
        
        $apiUrlSuffix = $apiSuffix.$branchInfo->branchCode.'/'.$categoryIdUrl;
        
        $apiUrl = (trim($branchInfo->apiUrl) == "") ? base_url().$apiUrlSuffix : $branchInfo->apiUrl.$apiUrlSuffix;
        
        return $apiUrl;
    }
    
    function getPaginationConfig($config = array()){
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        
        return $config;
    }
    
    /**
     * Returns the number of days between two dates
     * @param string $startdate
     * @param string $endDate
     * @return float Difference
     */
    function getDateDifference($startDate, $endDate)
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        
        $diff = $endDate - $startDate;
        
        return floor($diff/(60*60*24));
    }
    
    function getMonthsBetweenDates($startDate, $endDate, $format = "m-Y")
    {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);

        $dateMonthYear = array();
        while ($begin <= $end) {
            $dateMonthYear[(string)$begin->format($format)] = (string)$begin->format($format);                        
            $begin->modify('first day of next month');
        }
        
        return $dateMonthYear;
    }
    
    function sgetMonthsBetweenDates($startDate, $endDate)
    {
        $start    = (new DateTime($startDate))->modify('first day of this month');
        $end      = (new DateTime($endDate))->modify('first day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        
        $dateMonthYear = array();
        foreach ($period as $dt) {
            $dateMonthYear[(string)$dt->format("m-Y")] = (string)$dt->format("m-Y");            
        }
        
        if(empty($dateMonthYear)){
            $dateMonthYear[(string)$start->format("m-Y")] = (string)$start->format("m-Y");
        }
        
        return $dateMonthYear;
    }
    
    function getCompanyUnit()
    {
        $data = array();
        $data[1] = "KG";
        $data[2] = "Sq Ft";
        
        return $data;
    }
    
    function convert_number_to_words($number) {

        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
    
    /**
     * return file extention name
     * @param type $file_name
     * @param array $allowed_ext
     * @return boolen
     */
    function check_file_extension($file_name ,$allowed_ext = null){
        if($allowed_ext == null):
            $allowed_ext = array('docx','doc','xlsx','xls','ppt','pptx','zip','rar','pdf','csv');
        endif;        
        
        $file = explode('.',$file_name);
        $ext = strtolower(array_pop($file));
        
        if(in_array($ext,$allowed_ext))
        {
            return $ext;
        }        
        return false;
    }

}
/*?>*/