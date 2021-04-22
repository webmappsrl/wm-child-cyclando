<?php


// action that process ajax call : webmapp_anypost-cy_route_advancedsearch-oneclick.php to update route price
add_action( 'wp_ajax_nopriv_oc_ajax_create_hs_deal', 'oc_ajax_create_hs_deal' );
add_action( 'wp_ajax_oc_ajax_create_hs_deal', 'oc_ajax_create_hs_deal' );
function oc_ajax_create_hs_deal(){
    $cookies = $_POST['cookies'];
    $post_id = $_POST['postid']; 
    $result =  wm_sync_create_deal_hubspot($cookies,$post_id);    
    
    echo json_encode($result);
    wp_die();
}



function wm_sync_create_deal_hubspot( $cookies,$post_id ) { 
  //Hubspot APIKEY location => wp-config.php
  $hapikey = HUBSPOTAPIKEY;

  // Get the deposit
  $deposit_amount = "0";
  if ($cookies['deposit']) {
    $deposit_amount = $cookies['deposit'];
  }
  $single_room = '';
  $extra = array();
  $supplement = array();
  if ($cookies['supplement']) {
    foreach ($cookies['supplement'] as $supp => $num) {
      if ($supp == 'single_room') {
        $single_room = $num;
      } else {
        $supplement += array($supp => $num);
      }
    }
  }
  if ($cookies['extra']) {
    foreach ($cookies['extra'] as $supp => $num) {
        $extra += array($supp => $num);
    }
  }
  if ($cookies['regular']) {
    $extra += array('regular' => $cookies['regular']);
  }
  if ($cookies['electric']) {
    $extra += array('electric' => $cookies['electric']);
  }
  $extra = http_build_query($extra,'',',');
  $supplement = http_build_query($supplement,'',',');

  // Get the issued date
  $departure_date = explode('-',$cookies['departureDate']);
  $departure_date = $departure_date[2].'-'.$departure_date[1].'-'.$departure_date[0];

  $order_issued_date = date('Y-m-d');
  
  // Get the order total amount and billing name
  $order_total = $cookies['price'];
  $billing_first_name = $cookies['billingname'];
  $billing_last_name = $cookies['billingsurname'];

  // Get route info
  $routePermalink = $cookies['routePermalink'];

  $adults_number = $cookies['adults'];
  $kids_number = $cookies['kids'];

  $CURLOPT_POSTFIELDS_ARRAY = "{\"properties\":{
    \"dealname\": \"$billing_first_name $billing_last_name\",
    \"dealstage\": \"presentationscheduled\",
    \"dealtype\": \"newbusiness\",
    \"hubspot_owner_id\": \"40292283\",
    \"amount\": \"$order_total\",
    \"createdate\": \"$order_issued_date\",
    \"data_di_partenza\": \"$departure_date\",
    \"descrizione\": \"$post_id\",
    \"nr_adulti\": \"$adults_number\",
    \"nr_bambini\": \"$kids_number\",
    \"amount_acconto\": \"$deposit_amount\",
    \"url_route\": \"$routePermalink\",
    \"camere_singole\": \"$single_room\",
    \"extra\": \"$extra\",
    \"supplemento\": \"$supplement\",
  }}";

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.hubapi.com/crm/v3/objects/deals?hapikey=$hapikey",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS_ARRAY,
    CURLOPT_HTTPHEADER => array(
      "accept: application/json",
      "content-type: application/json"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    return "cURL Error #:" . $err;
  } else {
    return $response;
  }
}; 

