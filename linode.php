<?php

function linode_ConfigOptions() {
  $configarray = array(
    "plan_id" => array (
      "FriendlyName" => "Plan ID",
      "Type" => "text",
      "Size" => "25",
      "Description" => "Linode plan ID"
    ),
    "datecenter_id" => array (
      "FriendlyName" => "Data Center ID",
      "Type" => "text",
      "Size" => "25",
      "Description" => "Linode date center ID"
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
  if ($successful) {
    $result = "success";
  } else {
    $result = "Error Message Goes Here...";
  }
  return $result;
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

  $return['result'] = "error";
  $return['message'] = "Unknow error";

  if( !class_exists('Services_Linode') ) {
    $return['message'] = "Linode API wrapper missing";
    return $return;
  }

  if( !$linode_api_key ) {
    $return['message'] = "Linode API key missing";
    return $return;
  }

  if( !$command ) {
    $return['message'] = "Linode API command missing";
    return $return;
  }

  try {
    $linode = new Services_Linode($linode_api_key);
    $result =  $linode->$command( $post );

    if( !empty( $result['ERRORARRAY'] ) ) {
      $return['message'] = $result['ERRORARRAY'][0]['ERRORMESSAGE'];
      return $return;
    }

    return $result['DATA'];

  } catch (Services_Linode_Exception $e) {
    $return['message'] = $e->getMessage();
    return $return;
  }
}

?>