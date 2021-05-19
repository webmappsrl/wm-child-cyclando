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
    $deposit_amount = str_replace('.', '', $cookies['deposit']);
    $deposit_amount = str_replace(',', '.', $deposit_amount);
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
  $order_total = str_replace('.', '', $cookies['price']);
  $order_total = str_replace(',', '.', $order_total);
  $billing_first_name = $cookies['billingname'];
  $billing_last_name = $cookies['billingsurname'];
  $billing_email = $cookies['billingemail'];
  $billing_newsletter = $cookies['billingnewsletter'];
  if ($billing_newsletter == 'on' ){
    $newsletter = "true";
  } else {
      $newsletter = "false";
  }
  // Get route info
  $routePermalink = $cookies['routePermalink'];
  $routeName = $cookies['routeName'];

  $adults_number = $cookies['adults'];
  $kids_number = $cookies['kids'];
  $kids_age = http_build_query($cookies['ages'],'',',');

  $CURLOPT_POSTFIELDS_ARRAY = "{\"properties\":{
    \"dealname\": \"$billing_first_name $billing_last_name - $routeName\",
    \"dealstage\": \"presentationscheduled\",
    \"dealtype\": \"newbusiness\",
    \"hubspot_owner_id\": \"40292283\",
    \"amount\": \"$order_total\",
    \"createdate\": \"$order_issued_date\",
    \"data_di_partenza\": \"$departure_date\",
    \"descrizione\": \"Route ID: $post_id\",
    \"nr_adulti\": \"$adults_number\",
    \"nr_bambini\": \"$kids_number\",
    \"amount_acconto\": \"$deposit_amount\",
    \"url_route\": \"$routePermalink\",
    \"camere_singole\": \"$single_room\",
    \"extra\": \"$extra\",
    \"supplemento\": \"$supplement\",
    \"eta_bambini\": \"$kids_age\"
  }}";

  // Start creating contact on hub spot

  $curl_contact_search = curl_init();

  curl_setopt_array($curl_contact_search, array(
    CURLOPT_URL => "https://api.hubapi.com/crm/v3/objects/contacts/search?hapikey=$hapikey",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{
      \"filterGroups\": [
          {
              \"filters\": [
                  {
                      \"operator\": \"EQ\",
                      \"propertyName\": \"email\",
                      \"value\": \"$billing_email\"
                  }
              ]
          }
      ]
  }",
    CURLOPT_HTTPHEADER => array(
      "accept: application/json",
      "content-type: application/json"
    ),
  ));

  $response_search = curl_exec($curl_contact_search);
  $response_search = json_decode($response_search);
  $response_total = $response_search->total; 
  $err_search = curl_error($curl_contact_search);

  curl_close($curl_contact_search);

  if ($err_search) {
    echo "cURL Error #:" . $err_search;
  } else {
    if ($response_total && $response_total !== 0 ) {
      $res_contact_id = $response_search->results[0]->id;
    } else {
        // -------
        $CURLOPT_POSTFIELDS = "{\"properties\":{\"email\":\"$billing_email\",\"firstname\":\"$billing_first_name\",\"lastname\":\"$billing_last_name\",\"app_user_iscritto_newsletter\":\"$newsletter\"}}";

        $curl_contact = curl_init();

        curl_setopt_array($curl_contact, array(
        CURLOPT_URL => "https://api.hubapi.com/crm/v3/objects/contacts?hapikey=$hapikey",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS,
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "content-type: application/json"
        ),
        ));
        $response_contact = curl_exec($curl_contact);
        $response_contact = json_decode($response_contact);
        $res_contact_id = $response_contact->id;
        curl_close($curl_contact);
    }
  }
  // END creating contact on hub spot

  // START creating Deal on hubspot
  $curl_deal = curl_init();

  curl_setopt_array($curl_deal, array(
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

  $response = curl_exec($curl_deal);
  $response = json_decode($response);
  $res_deal_id = $response->id;
  $err = curl_error($curl_deal);

  curl_close($curl_deal);
  // END creating Deal on hubspot

  // start Assosiation between contact and deal 
  $curl_assoc = curl_init();

  curl_setopt_array($curl_assoc, array(
    CURLOPT_URL => "https://api.hubapi.com/crm/v3/associations/deal/contact/batch/create?hapikey=$hapikey",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\"inputs\":[{\"from\":{\"id\":\"$res_deal_id\"},\"to\":{\"id\":\"$res_contact_id\"},\"type\":\"deal_to_contact\"}]}",
    CURLOPT_HTTPHEADER => array(
      "accept: application/json",
      "content-type: application/json"
    ),
  ));

  $response_assoc = curl_exec($curl_assoc);

  curl_close($curl_assoc); 
  // start Assosiation between contact and deal  

  if ($err) {
    return "cURL Error #:" . $err;
  } else {
    return $response;
  }
}; 

