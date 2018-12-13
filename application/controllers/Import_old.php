<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import extends CI_Controller {
	
    public function index() {
              $this->load->database();
		$query = $this->db->select("creationDtTm, adminUserId, activityJson")
            ->from("bo_activity_log")
            ->where("tableName", "apps_users")
            ->where("actionCode", 'add')
            ->get();

        echo '<table border="1">
			<tr>
				<td>Customer ID</td>
				<td>SKY ID</td>
				<td>Created By</td>
				<td>Created Date</td>
			</tr>';

        $i=0;
$json=array();

        foreach($query->result() as $rows){
			$i++;

$r=$rows->activityJson;

if($r){
$json=json_decode($r);
}

        

                echo '<tr>
				<td>'.$json->cfId.'</td>
				<td>'.$json->eblSkyId.'</td>
				<td>'.$json->makerActionBy.'</td>
				<td>'.$json->makerActionDt.' '.$json->makerActionTm.'</td>
			</tr>';
      }
	
    echo '<tr>
		        <td>Total</td>
				<td>&nbsp</td>
				<td>Reg : '.$this->db->count_all('apps_users').'</td>
				<td>Log : '.$i.'</td>
     </tr>';
	
echo '</table>';
		
      
    
}
}


