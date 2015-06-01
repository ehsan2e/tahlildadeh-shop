<?php
    global $dependencies;
    $dependencies=array('checkInput','authHelper');
    
function main()
{
    if (hasPrivilege('customer')){ 
        
            // Check customer Loged in
        $userId = $_SESSION[getSpKey()]['customer'];
        $sql = "SELECT * FROM `customers` WHERE `id` = '$userId' ";
        $result = dbQuery($sql);
        
        while (($records = mysql_fetch_assoc($result)) !== false){
            $customerDetails = array(
                                        'id' => $records['id'],
                                        'customer_name' => $records['customer_name'],
                                        'customer_family' => $records['customer_family'],
                                        'customer_email' => $records['customer_email'],
                                        'customer_gender' => $records['customer_gender'],
                                        'customer_mobile' => $records['customer_mobile'],
                                        'customer_city' => $records['customer_city'],
                                        'customer_state' => $records['customer_state'],
                                        'customer_zipcode' => $records['customer_zipcode'],
                                        'customer_emergency_number' => $records['customer_emergency_number'],
                                        'customer_address' => $records['customer_address']
                                        );
            
        }
        mysql_free_result($result);

        
        // edit Customer Details
        

        if (isset($_POST['btnEditSubmit'])){
            
            $txtDetails = array (
                'customer_name' => isset($_POST['txtName'])?$_POST['txtName']:null,
                'customer_family' => isset($_POST['txtFamily'])?$_POST['txtFamily']:null,
                'customer_email' => isset($_POST['txtEmail'])?$_POST['txtEmail']:null,
                'customer_mobile' => isset($_POST['txtMobile'])?$_POST['txtMobile']:null,
                'customer_city' => isset($_POST['txtCity'])?$_POST['txtCity']:null,
                'customer_state' => isset($_POST['txtState'])?$_POST['txtState']:null,
                'customer_zipcode' => isset($_POST['txtZipCode'])?$_POST['txtZipCode']:null,
                'customer_emergency_number' => isset($_POST['txtEmergencyNumber'])?$_POST['txtEmergencyNumber']:null,
                'customer_address' => isset($_POST['txtAddress'])?$_POST['txtAddress']:null
            );
            
        $dataIsCorrect=true;
        
        foreach($txtDetails as $pieceOfData){
            if(is_null($pieceOfData)){
                
                addMessage('اطلاعات محصول به درستی وارد نشده است',FAILURE);
                $dataIsCorrect=false;
                break;
            }
        }
        
        
        
        
        
        
        
        
        }
        
        
    } // End Check customer Loged in
    else {
        
        $url = BASE_URL.'signup';
        return array('redirect'=>$url);
        
       }
       
    $resp['data']=array(
                    'customerDetails' => $customerDetails
                    );

    return $resp;
	
}

require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php');