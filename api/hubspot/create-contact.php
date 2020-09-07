<?php

add_action( 'user_register', 'wm_hs_api_create_contact', 10, 1 );

function wm_hs_api_create_contact( $user_id ) {

    //Hubspot APIKEY location => wp-config.php
    $hapikey = HUBSPOTAPIKEY;
    
    $user_obj = get_userdata($user_id);
    $username = ucfirst($user_obj->user_login);
    $userfname = ucfirst($user_obj->first_name);
    $userlname = ucfirst($user_obj->last_name);
    $useremail = $user_obj->user_email;

    if (empty($userfname) || empty($userlname)) {
        $CURLOPT_POSTFIELDS = "{\"properties\":{\"app_user\":\"true\",\"email\":\"$useremail\",\"firstname\":\"$username\"}}";
    } else {
        $CURLOPT_POSTFIELDS = "{\"properties\":{\"app_user\":\"true\",\"email\":\"$useremail\",\"firstname\":\"$userfname\",\"lastname\":\"$userlname\"}}";
    }
    $curl = curl_init();

    curl_setopt_array($curl, array(
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

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
    echo $response;
    }

}

