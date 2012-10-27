<?php

function linode_ConfigOptions() {
  $configarray = array(
    "datecenter_id" => array (
      "FriendlyName" => "Data Center ID",
      "Type" => "text",
      "Size" => "25",
      "Description" => "Linode date center ID"
    ),
    "plan_id" => array (
      "FriendlyName" => "Plan ID",
      "Type" => "text",
      "Size" => "25",
      "Description" => "Linode plan ID"
    ),
    "subscription" => array (
      "FriendlyName" => "Subscription Term",
      "Type" => "dropdown",
      "Options" => "1,12,24",
      "Default" => "1",
      "Description" => "Linode subscription term, in months,"
    ),
  );
  return $configarray;
}

function linode_CreateAccount($params) {
  //Just checking if you followed the install notes & that the required custom field exists
  //if( !array_key_exists('Linode ID', $params['customfields'] ) ) {
  //  return "The Linode ID custom field doesn't exist. Check the install notes.";
  //}
  //Yeah, I could've used the above but I need the ID anyway later on
  $result = select_query(
    'tblcustomfields',
    '*',
    array(
      "relid" => $params['pid'],
      "type" => "product",
      "fieldname" => "Linode ID"
    )
  );
  $tblcustomfield = mysql_fetch_array($result);
  if( !$tblcustomfield ) {
    return "The Linode ID custom field doesn't exist. Check the install notes.";
  }
  //If Linode ID is not set, the service is probably already created
  if( !empty( $params['customfields']['Linode ID'] ) ) {
    return "Seems the service is already created";
  }
  //Remote call
  $return = linode_remote(
    'linode_create',
    array(
      'DatacenterID' => $params['configoption1'],
      'PlanID' => $params['configoption2'],
      'PaymentTerm' => $params['configoption3']
    )
  );
  //Processing the result for failures and the odd success
  if( $return['result'] == 'error' ) {
    return $return['message'];
  }
  //TODO: Test the below. I don't have a Linode account
  //Check that the Linode ID is in the result, cause i'm just paranoid like that
  if( !array_key_exists('LinodeID', $return ) ) {
    return "The Linode ID wan't return by the API";
  }
  //Set the Linode ID custom field, well find it first
  $result = update_query(
    'tblcustomfieldsvalues',
    array(
      "value" => $return['LinodeID']
    ),
    array(
      "relid" => $tblcustomfield['id']
    )
  );
  return "success";
}

function linode_TerminateAccount($params) {
  if ($successful) {
    $result = "success";
  } else {
    $result = "Error Message Goes Here...";
  }
  return $result;
}

function linode_SuspendAccount($params) {
  if ($successful) {
    $result = "success";
  } else {
    $result = "Error Message Goes Here...";
  }
  return $result;
}

function linode_UnsuspendAccount($params) {
  if ($successful) {
    $result = "success";
  } else {
    $result = "Error Message Goes Here...";
  }
  return $result;
}

function linode_ChangePassword($params) {
  if ($successful) {
    $result = "success";
  } else {
    $result = "Error Message Goes Here...";
  }
  return $result;
}

function linode_ChangePackage($params) {
  if ($successful) {
    $result = "success";
  } else {
    $result = "Error Message Goes Here...";
  }
  return $result;
}

function linode_reboot($params) {
  if ($successful) {
    $result = "success";
  } else {
    $result = "Error Message Goes Here...";
  }
  return $result;
}

function linode_shutdown($params) {
  if ($successful) {
    $result = "success";
  } else {
    $result = "Error Message Goes Here...";
  }
  return $result;
}

function linode_ClientAreaCustomButtonArray() {
  $buttonarray = array(
    "Reboot Server" => "reboot",
  );
  return $buttonarray;
}

function linode_AdminCustomButtonArray() {
  $buttonarray = array(
    "Reboot Server" => "reboot",
    "Shutdown Server" => "shutdown",
  );
  return $buttonarray;
}

function linode_remote( $command=false, $post=array() ) {
  require('Services/Linode.php');
  require(ROOTDIR.'/configuration.php');
  //Default return stuff
  $return['result'] = "error";
  $return['message'] = "Unknow error";
  //This is needed
  if( !class_exists('Services_Linode') ) {
    $return['message'] = "Linode API wrapper missing";
    return $return;
  }
  //This too
  if( !$linode_api_key ) {
    $return['message'] = "Linode API key missing";
    return $return;
  }
  //And this
  if( !$command ) {
    $return['message'] = "Linode API command missing";
    return $return;
  }

  try {
    $linode = new Services_Linode($linode_api_key);
    $result =  $linode->$command( $post );
    //I'm not running any batch operations so just the first error message will do
    if( !empty( $result['ERRORARRAY'] ) ) {
      $return['message'] = $result['ERRORARRAY'][0]['ERRORMESSAGE'];
      return $return;
    }
    //Yay, good stuff :)
    $return['result'] = $return['message'] = "success";
    return $result['DATA'];
    //Not so good stuff :(
  } catch (Services_Linode_Exception $e) {
    $return['message'] = $e->getMessage();
    return $return;
  }
}

?>