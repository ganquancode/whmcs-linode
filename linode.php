<?php

function linode_ConfigOptions() {
  //I'm going to populate the options with the related names and then look those up later on in the other functions
  //Remote call for datacenters
  $results = linode_remote('avail_datacenters');
  $datacenters = "";
  if( $return['result'] == 'success' ) {
    $datacenters = implode_on_key($return, 'LOCATION', ',');
  }

  //Remote call for plans
  $results = linode_remote('avail_linodeplans');
  $plans = "";
  if( $return['result'] == 'success' ) {
    $plans = implode_on_key($return, 'LABEL', ',');
  }

  //Remote call for distributions
  $results = linode_remote('avail_distributions');
  $distributions = "";
  if( $return['result'] == 'success' ) {
    $distributions = implode_on_key($return, 'LABEL', ',');
  }

  $configarray = array(
    "datacenter" => array (
      "FriendlyName" => "Data Center",
      "Type" => "dropdown",
      "Options" => $datacenters,
      "Default" => "1"
    ),
    "plan" => array (
      "FriendlyName" => "Plan",
      "Type" => "dropdown",
      "Options" => $datacenters,
      "Default" => "1"
    ),
    "distribution" => array (
      "FriendlyName" => "Distribution",
      "Type" => "text",
      "Size" => "25",
    ),
    "subscription" => array (
      "FriendlyName" => "Subscription Term",
      "Type" => "dropdown",
      "Options" => "1,12,24",
      "Default" => "1",
    ),
  );
  return $configarray;
}

function linode_CreateAccount($params) {
  //Just checking if you followed the install notes & that the required custom field exists
  //if( !array_key_exists('Linode ID', $params['customfields'] ) ) {
  //  return "The Linode ID custom field doesn't exist. Check the install notes.";
  //}
  //Yeah, I could've used the above but I need the ID later on anyway
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
  //Remote call to create the Linode
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
  //Set the Linode ID custom field
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
  //Need to get the Linode ID, also need the field ID again though
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
  //If Linode ID is not set, the service is probably not created
  if( empty( $params['customfields']['Linode ID'] ) ) {
    return "Seems the service is already created";
  }
  //Remote call
  $return = linode_remote(
    'linode_create',
    array(
      'DatacenterID' => $params['customfields']['Linode ID'],
      'skipChecks' => 1
    )
  );
  //Processing the result for failures and the odd success
  if( $return['result'] == 'error' ) {
    return $return['message'];
  }
  //Nullify Linode ID custom field
  $result = update_query(
    'tblcustomfieldsvalues',
    array(
      "value" => ""
    ),
    array(
      "relid" => $tblcustomfield['id']
    )
  );
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

function implode_on_key($array, $key, $sep, $prefix = '') {
  $string = '';
  if(empty($array)) return '';
  foreach($array as $element) {
    $string .= $prefix.$element[$key].$sep;
  }
  return substr($string, 0, -strlen($sep));
}

?>