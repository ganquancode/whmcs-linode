<?php

function linode_ConfigOptions() {
  $configarray = array(
    "api_key" => array(
      "FriendlyName" => "API Key",
      "Type" => "text",
      "Size" => "25"
    ),
    #"Plan" => array("Type" => "text", "Size" => "25" ),
    #"Data Center" => array( "Type" => "text", "Size" => "25" ),
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
  $serviceid = $params["serviceid"];
  $pid = $params["pid"];
  $producttype = $params["producttype"];
  $domain = $params["domain"];
  $username = $params["username"];
  $password = $params["password"];
  $clientsdetails = $params["clientsdetails"];
  $customfields = $params["customfields"];
  $configoptions = $params["configoptions"];

  $configoption1 = $params["configoption1"];
  $configoption2 = $params["configoption2"];
  $configoption3 = $params["configoption3"];
  $configoption4 = $params["configoption4"];

  $server = $params["server"];
  $serverid = $params["serverid"];
  $serverip = $params["serverip"];
  $serverusername = $params["serverusername"];
  $serverpassword = $params["serverpassword"];
  $serveraccesshash = $params["serveraccesshash"];
  $serversecure = $params["serversecure"];

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

?>