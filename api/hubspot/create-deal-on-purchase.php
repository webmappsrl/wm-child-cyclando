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
  $hotel_category = '';
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
  if ($cookies['category']) {
    $hotel_category = $cookies['category'];
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

  //Calculate routes taxonomies for contact creation
  $target = 'who';
  $places_to_go = 'where';
  $activity = 'activity';
  $tax_activities = get_the_terms($post_id, $activity);
  $tax_targets = get_the_terms($post_id, $target);
  $tax_places_to_go = get_the_terms($post_id, $places_to_go);
  $tax_activities_slug = array();
  if ($tax_activities)
    foreach ($tax_activities as $tax_activity) {
      array_push($tax_activities_slug, $tax_activity->slug);
    }
  $st_activities = implode(";", $tax_activities_slug);
  $tax_targets_slug = array();
  if ($tax_targets)
    foreach ($tax_targets as $tax_target) {
      if ($tax_target->slug == 'con-guida') {
        array_push($tax_targets_slug, 'guided');
      } elseif ($tax_target->slug == 'di-gruppo') {
        array_push($tax_targets_slug, 'di gruppo');
      } else {
        array_push($tax_targets_slug, $tax_target->slug);
      }
    }
  $st_targets = implode(";", $tax_targets_slug);
  $tax_places_to_go_slug = array();
  if ($tax_places_to_go)
    foreach ($tax_places_to_go as $tax_place) {
      array_push($tax_places_to_go_slug, $tax_place->slug);
    }
  $st_places = implode(";", $tax_places_to_go_slug);


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
    \"eta_bambini\": \"$kids_age\",
    \"categoria_hotel\": \"$hotel_category\"
  }}";

  // Start creating contact on hub spot

  $curl_contact_search = curl_init();

  // search if contact exists
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
  $err_contact_search = curl_error($curl_contact_search);

  curl_close($curl_contact_search);

  if ($err_contact_search) {
    wm_write_log_file($err_contact_search,'a+','contactHS_error_search');
    echo "cURL Error #:" . $err_contact_search;
  } else {
    wm_write_log_file($response_search,'a+','contactHS_success_search');
    $search_log = $billing_email . '->' . $CURLOPT_POSTFIELDS_ARRAY;
    wm_write_log_file($search_log,'a+','contactHS_success_search_email_with_properties');
    if ($response_total && $response_total !== 0 ) {
      $res_contact_id = $response_search->results[0]->id;

      $curl_get_contact = curl_init();
      curl_setopt_array($curl_get_contact, array(
        CURLOPT_URL => "https://api.hubapi.com/crm/v3/objects/contacts/$res_contact_id?properties=target%2Cactivities%2Cplace_to_go&archived=false&hapikey=$hapikey",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "accept: application/json"
        ),
      ));
      $response_get_contact = curl_exec($curl_get_contact);
      $response_get_contact = json_decode($response_get_contact);
      $explode_targe = explode(";",$response_get_contact->properties->target);
      foreach ($explode_targe as $target) {
        array_push($tax_targets_slug,$target);
      }
      $contact_targets =  array_unique($tax_targets_slug);
      
      $explode_activities = explode(";",$response_get_contact->properties->activities);
      foreach ($explode_activities as $activities) {
        array_push($tax_activities_slug,$activities);
      }
      $contact_activities =  array_unique($tax_activities_slug);

      $explode_places_to_go = explode(";",$response_get_contact->properties->place_to_go);
      foreach ($explode_places_to_go as $places_to_go) {
        array_push($tax_places_to_go_slug,$places_to_go);
      }
      $contact_tax_places_to_go_slug =  array_unique($tax_places_to_go_slug);

      $err_get_contact = curl_error($curl_get_contact);
      curl_close($curl_get_contact);
      $st_places_update = implode(";", $contact_tax_places_to_go_slug);
      $st_activities_update = implode(";", $contact_activities);
      $st_targets_update = implode(";", $contact_targets);

      // ------- updating parameters
      $CURLOPT_POSTFIELDS = "{\"properties\":{
        \"firstname\":\"$billing_first_name\",
        \"lastname\":\"$billing_last_name\",
        \"app_user_iscritto_newsletter\":\"$newsletter\",
        \"tour_operator\":\"Privati\",
        \"target\":\"$st_targets_update\",
        \"activities\":\"$st_activities_update\",
        \"place_to_go\":\"$st_places_update\"
      }}";

      $curl_contact_update = curl_init();
      //Start updating contact info with taxonomies and excetra
      curl_setopt_array($curl_contact_update, array(
      CURLOPT_URL => "https://api.hubapi.com/crm/v3/objects/contacts/$res_contact_id?hapikey=$hapikey",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PATCH",
      CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS,
      CURLOPT_HTTPHEADER => array(
          "accept: application/json",
          "content-type: application/json"
      ),
      ));
      $response_contact_update = curl_exec($curl_contact_update);
      $response_contact_update = json_decode($response_contact_update);
      $err_contact_update = curl_error($curl_contact_update);
      curl_close($curl_contact_update);
      if ($err_contact_update) {
        wm_write_log_file($err_contact_update,'a+','contactHS_error_update');
        echo "cURL Error #:" . $err_contact_update;
      } else {
        wm_write_log_file($response_contact_update,'a+','contactHS_success_update');
      }
    } else {
        // -------
        $CURLOPT_POSTFIELDS = "{\"properties\":{
          \"email\":\"$billing_email\",
          \"firstname\":\"$billing_first_name\",
          \"lastname\":\"$billing_last_name\",
          \"app_user_iscritto_newsletter\":\"$newsletter\",
          \"lifecyclestage\":\"opportunity\",
          \"tour_operator\":\"Privati\",
          \"target\":\"$st_targets\",
          \"activities\":\"$st_activities\",
          \"place_to_go\":\"$st_places\"
        }}";

        $curl_contact = curl_init();
        //Start creating contact
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
        $err_contact_create = curl_error($curl_contact);
        curl_close($curl_contact);
        if ($err_contact_create) {
          wm_write_log_file($err_contact_create,'a+','contactHS_error_create');
          echo "cURL Error #:" . $err_contact_create;
        } else {
          wm_write_log_file($response_contact,'a+','contactHS_success_create');
        }
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
  $err_create_deal = curl_error($curl_deal);

  curl_close($curl_deal);
  if ($err_create_deal) {
    wm_write_log_file($err_create_deal,'a+','dealHS_error_create');
    echo "cURL Error #:" . $err_create_deal;
  } else {
    wm_write_log_file($response,'a+','dealHS_success_create');
  }
  
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
  $err_create_assoc = curl_error($curl_assoc);
  curl_close($curl_assoc); 

  if ($err_create_assoc) {
    wm_write_log_file($err_create_assoc,'a+','associationHS_error_create');
    echo "cURL Error #:" . $err_create_assoc;
  } else {
    wm_write_log_file($response_assoc,'a+','associationHS_success_create');
  }
  // start Assosiation between contact and deal  

  if ($err_create_deal) {
    return "cURL Error #:" . $err_create_deal;
  } else {
    return $response;
  }
}; 

