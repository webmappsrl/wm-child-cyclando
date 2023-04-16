<?php


require 'hubspot/create-contact.php';
require 'hubspot/create-deal-on-purchase.php';
require 'general/email-to-tour-operator.php';
require 'general/update_route_taxonomy_on_durata.php';



function cyclando_get_hubspot_api_request_headers(){

  //Hubspot APIKEY location => wp-config.php
  return array(
    "accept: application/json",
    "content-type: application/json",
    'Authorization: Bearer ' . HUBSPOTAPIKEY,
  );
}
