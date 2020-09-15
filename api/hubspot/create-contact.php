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
    $usernewsletter = get_user_meta($user_id,'newsletter');
    if ($usernewsletter[0] == '1' ){
        $newsletter = "true";
    } else {
        $newsletter = "false";
    }

    if (empty($userfname) || empty($userlname)) {
        $CURLOPT_POSTFIELDS = "{\"properties\":{\"app_user\":\"true\",\"email\":\"$useremail\",\"firstname\":\"$username\",\"app_user_iscritto_newsletter\":\"$newsletter\"}}";
    } else {
        $CURLOPT_POSTFIELDS = "{\"properties\":{\"app_user\":\"true\",\"email\":\"$useremail\",\"firstname\":\"$userfname\",\"lastname\":\"$userlname\",\"app_user_iscritto_newsletter\":\"$newsletter\"}}";
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
}


add_action( 'updated_user_meta', 'wm_hs_api_update_contact', 10, 4 );
function wm_hs_api_update_contact($meta_id, $object_id, $meta_key, $_meta_value) {
    if ($meta_key == 'newsletter') {
        //Hubspot APIKEY location => wp-config.php
        $hapikey = HUBSPOTAPIKEY;

        $user_obj = get_userdata($object_id);
        $userfname = ucfirst($user_obj->first_name);
        $userlname = ucfirst($user_obj->last_name);
        $useremail = $user_obj->user_email;
        $usernewsletter = get_user_meta($object_id,'newsletter');
        if ($usernewsletter[0] == '1' ){
            $newsletter = "true";
        } else {
            $newsletter = "false";
        }

        if (empty($userfname) || empty($userlname)) {
            $CURLOPT_POSTFIELDS = "{\"properties\":[{\"property\":\"app_user\",\"value\":\"true\"},{\"property\":\"app_user_iscritto_newsletter\",\"value\":\"$newsletter\"}]}";
        } else {
            $CURLOPT_POSTFIELDS = "{\"properties\":[{\"property\":\"app_user\",\"value\":\"true\"},{\"property\":\"firstname\",\"value\":\"$userfname\"},{\"property\":\"lastname\",\"value\":\"$userlname\"},{\"property\":\"app_user_iscritto_newsletter\",\"value\":\"$newsletter\"}]}";
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.hubapi.com/contacts/v1/contact/email/$useremail/profile?hapikey=$hapikey",
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
    }
}

add_action( 'profile_update', 'wm_hs_api_update_profile', 10, 2 );
function wm_hs_api_update_profile($user_id, $old_user_data) {
        //Hubspot APIKEY location => wp-config.php
        $hapikey = HUBSPOTAPIKEY;

        $user_obj = get_userdata($user_id);
        $userfname = ucfirst($user_obj->first_name);
        $userlname = ucfirst($user_obj->last_name);
        $useremail = $user_obj->user_email;
        $usernewsletter = get_user_meta($user_id,'newsletter');
        if ($usernewsletter[0] == '1' ){
            $newsletter = "true";
        } else {
            $newsletter = "false";
        }

        if (empty($userfname) || empty($userlname)) {
            $CURLOPT_POSTFIELDS = "{\"properties\":[{\"property\":\"app_user\",\"value\":\"true\"},{\"property\":\"app_user_iscritto_newsletter\",\"value\":\"$newsletter\"}]}";
        } else {
            $CURLOPT_POSTFIELDS = "{\"properties\":[{\"property\":\"app_user\",\"value\":\"true\"},{\"property\":\"firstname\",\"value\":\"$userfname\"},{\"property\":\"lastname\",\"value\":\"$userlname\"},{\"property\":\"app_user_iscritto_newsletter\",\"value\":\"$newsletter\"}]}";
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.hubapi.com/contacts/v1/contact/email/$useremail/profile?hapikey=$hapikey",
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
}