<?php 
if(isset($_POST['action']) && !empty($_POST['action'])) {
    
    $term_slug = $_POST['action'];
    $calendar_json = "https://www.cyclando.com/wp-json/wp/v2/where?per_page=100";
    $c = json_decode(file_get_contents($calendar_json),TRUE);
    $get_term = '';
    foreach ($c as $r) {
        if ($r['slug'] == $term_slug) {
            $get_term  = $r['name'];
        }
    }
    
    echo $get_term;

}

?>