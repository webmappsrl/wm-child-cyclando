<?php 


class routeProductsOC {

    protected $cookies;
    protected $post_id;
    protected $price;
    protected $searchpage;

    public function __construct($cookies,$post_id,$searchpage){
        $this->cookies = $cookies;
        $this->post_id = $post_id;
        $this->searchpage = $searchpage;
    }

    public function getHotelProducts () {
        $seasonal_products = $this->getHotelSeasonalVariations($this->post_id,$this->cookies['departureDate']);
        $general_products = get_field('product',$this->post_id);
        if ($seasonal_products) {
            $products = $seasonal_products;
        } else {
            $products = $general_products;
        }
        if ($products) {
            return $this->getProductsHotelVariations($products);
        }
    }
    
    public function getExtraProducts () {
        $products = get_field('product',$this->post_id);
        if ($products) {
            return $this->getProductsExtraVariations($products);
        }
    }

    public function getProductsExtraVariations ($products) {
        if ($products){  //----------- start hotel product table
            $extra_variation_name_price = array();
                foreach( $products as $p ){ // variables of each product
                $product = wc_get_product($p); 
                    if($product->is_type('variable')){
                        $category = $product->get_categories();
                        if(strip_tags($category) == 'extra'){
                            foreach($product->get_available_variations() as $variation ){
                                // Extra Name
                                $xattributes = $variation['attributes'];
                                $xvariation_name = '';
                                foreach($xattributes as $name_var){
                                    $xvariation_name = $name_var;
                                }
                                // Prices
                                if ($variation['display_price'] == 0){
                                    $xprice = __('Free' ,'wm-child-verdenatura');
                                } 
                                elseif (!empty($variation['display_price'])){
                                    $xprice = strip_tags($variation['display_price']);
                                } else {
                                    $xprice = strip_tags($variation['price_html']);
                                }
                                $extra_name_price = array($xvariation_name => array('id'=>$variation['variation_id'],'price'=>intval($xprice)));
                                $extra_variation_name_price += $extra_name_price;
                            }
                        }
                    }
                }
            return $extra_variation_name_price;
        }

    }


    public function getProductsHotelVariations ($products) {
        if ($products){  //----------- start hotel product table
            $attributes_name_hotel = array();
            $variations_name_price = array();
                foreach( $products as $p ){ // variables of each product
                $product = wc_get_product($p); 
                    if($product->is_type('variable')){
                        $category = $product->get_categories();
                        $attributes_list = $product->get_variation_attributes();
                        foreach ($attributes_list as $value => $key ) {
                            $product_attribute_name = $value;
                        }
                        if(strip_tags($category) == 'hotel'){
                            array_push($attributes_name_hotel,$product_attribute_name);
                            $product_variation_name_price = array();
                            foreach($product->get_available_variations() as $variation ){

                                // hotel Name
                                $attributes = $variation['attributes'];
                                $variation_name = '';
                                foreach($attributes as $name_var){
                                    $variation_name = $name_var;
                                }
                                // Prices
                                if ($variation['display_price'] == 0){
                                    $price = __('Free' ,'wm-child-verdenatura');
                                } 
                                elseif (!empty($variation['display_price'])){
                                    $price = strip_tags($variation['display_price']);
                                } else {
                                    $price = strip_tags($variation['price_html']);
                                }
                                $variation_name_price = array($variation_name => array('id'=>$variation['variation_id'],'price'=>intval($price)));
                                $product_variation_name_price += $variation_name_price;
                            }
                            $variations_name_price[$product_attribute_name] = $product_variation_name_price;
                        }
                    }
                }
            return $variations_name_price;
        }
    }

    public function calculatePrice () {
        $hotel = $this->getHotelProducts();
        if (!$this->searchpage) {
            $extra = $this->getExtraProducts();
        }

        $adults = intval($this->cookies['adults']);
        
        if ($this->cookies['kids'])
            $kids = intval($this->cookies['kids']);
        
        if ($this->cookies['ages'])
            $ages = $this->cookies['ages'];

        if ($this->cookies['regular'])
            $regular = intval($this->cookies['regular']);

        if ($this->cookies['electric'])
            $electric = intval($this->cookies['electric']);

        if (!$this->searchpage) {
            if ($this->cookies[$this->post_id]['extra'])
                $has_extra = $this->cookies[$this->post_id]['extra'];
        }

        $addToCart = array();
        $deposit = 0;
        $percentToGet = 25;
        $percentInDecimal = $percentToGet / 100;

        if ($this->cookies['departureDate'])
            $departureDate = $this->cookies['departureDate'];
            
        $departureDateExplod = explode('-',$departureDate);
        $departureDateFormated = $departureDateExplod[2] . '-' . $departureDateExplod[1] . '-' . $departureDateExplod[0];
        $datetime = date("Y-m-d");
        $todayPlus30 = date('Y-m-d', strtotime('+30 day', strtotime($datetime)));
        

        if (array_key_exists($this->cookies['category'],$hotel)) {
            $category = $this->cookies['category'];
        } else {
            // Choose the less expensive category if the category name is not specified
            $keyAdultPrice = 0;
            $categoryName = '';
            foreach ($hotel as $key => $value) {
                if ($keyAdultPrice == 0) {
                    $keyAdultPrice = $value['adult']['price'];
                    $categoryName = $key;
                } elseif ($keyAdultPrice > $value['adult']['price']) {
                    $keyAdultPrice = $value['adult']['price'];
                    $categoryName = $key;
                }
            }
            $category = $categoryName;
        }

        if ($adults) {
            $this->price += $hotel[$category]['adult']['price'] * intval($adults);
            $addToCart[] = $hotel[$category]['adult']['id'] . ':' . intval($adults);
        }
        if ($kids) {
            $calculateKidsPriceResult = $this->calculateKidsPrice($hotel[$category],$kids,$ages);
            $this->price += $calculateKidsPriceResult['price'];
            $addToCart[] = $calculateKidsPriceResult['add_to_cart'];
        }
        if ($regular && $extra['bike']) {
            $this->price += $extra['bike']['price'] * intval($regular);
            $addToCart[] = $extra['bike']['id'] . ':' . $regular;
        }
        if ($electric && $extra['ebike']) {
            $this->price += $extra['ebike']['price'] * intval($electric);
            $addToCart[] = $extra['ebike']['id'] . ':' . $electric;
        }
        if (!$this->searchpage) {
            if ($has_extra && $extra) {
                foreach ($has_extra as $label => $num) {
                    $this->price += $extra[$label]['price'] * intval($num);
                    $addToCart[] = $extra[$label]['id'] . ':' . $num;
                }
            }
        }
        if ($departureDateFormated > $todayPlus30) {
            $deposit = $percentInDecimal * $this->price;
        }

        $object['departureDateFormated'] = $departureDateFormated;
        $object['todayPlus30'] = $todayPlus30;
        $object['price'] = number_format($this->price, 2, ',', '.');
        $object['category'] = array_keys($hotel);
        $object['categoryname'] = $category;
        $object['addtocart'] = implode(',',$addToCart);
        if ($deposit) {
            $object['deposit'] = number_format($deposit, 2, ',', '.');
            $object['depositaddtocart'] = $this->createDepositProduct($this->price,$deposit);
        }
        return $object;
    }



    public function getHotelSeasonalVariations($post_id,$departureDate){
        $seasonal_variations = array();
        if (have_rows('model_season',$post_id)) {
            while( have_rows('model_season',$post_id) ): the_row();
                $variation_disacitve = get_sub_field('wm_route_quote_model_season_disactive');
                $product = get_sub_field('product');
                if (!$variation_disacitve) {
                    if (have_rows('wm_route_quote_model_season_dates_periods_repeater')) {
                        while( have_rows('wm_route_quote_model_season_dates_periods_repeater') ): the_row();
                        $start = get_sub_field('wm_route_quote_model_season_dates_periods_start');
                        $stop = get_sub_field('wm_route_quote_model_season_dates_periods_stop');
                        $start = DateTime::createFromFormat('d/m/Y', $start);
                        $stop = DateTime::createFromFormat('d/m/Y', $stop);
                        $start = $start->format('m/d/Y');
                        $stop = $stop->format('m/d/Y');
                        $days = getDatesFromRange($start, $stop); 
                        foreach ( $days as $day )
                        {
                            if ( $day == $departureDate ) 
                            {
                               $seasonal_variations = $product;
                            }
                        }
                        endwhile;
                    }
                }
                
            endwhile;
        }
        
        return $seasonal_variations;
    }

    public function calculateKidsPrice($category,$kids,$ages){

        $price = array();
        $minAge = 0;
        $maxAge1 = 0;
        $minAge2 = 0;
        $maxAge2 = 0;
        $minAge3 = 0;
        $maxAge = 0;
        $adultPrice = array();

        foreach($category as $key => $value) {
            if (strpos($key, 'kid')!== false) {
                $kidExplode = explode('_',$key);
                if ($kidExplode[0] == 'kid1') {
                    if ($kidExplode[2]) {
                        $minAge = $kidExplode[2];
                    } else {
                        $minAge = 1;
                    }
                    $maxAge1 = $kidExplode[1];
                    $maxAge = $kidExplode[1];
                }
                if ($kidExplode[0] == 'kid2') {
                    $minAge2 = $maxAge1 + 1;
                    $maxAge2 = $kidExplode[1];
                    $maxAge = $kidExplode[1];
                }
                if ($kidExplode[0] == 'kid3') {
                    $minAge3 = $maxAge2 + 1;
                    $maxAge = $kidExplode[1];
                }
            }
            if ($key == 'adult') {
                $adultPrice['price'] = $value['price'];
                $adultPrice['id'] = $value['id'];
            }
        }
        foreach($ages as $age) {
            foreach($category as $key => $value) {
                if (strpos($key, 'kid')!== false) {
                    $kidExplode = explode('_',$key);
                    if ($minAge <= $age && $age <= $maxAge1 ) {
                        if ($kidExplode[0] == 'kid1') {
                            $price['price'] += intval($value['price']);
                            $add_to_cart[] = $value['id'] . ':' . '1';
                        }
                    }
                    if ($minAge2 <= $age && $age <= $maxAge2 ) {
                        if ($kidExplode[0] == 'kid2') {
                            $price['price'] += intval($value['price']);
                            $add_to_cart[] = $value['id'] . ':' . '1';
                        }
                    }
                    if ($minAge3 <= $age && $age <= $maxAge ) {
                        if ($kidExplode[0] == 'kid3') {
                            $price['price'] += intval($value['price']);
                            $add_to_cart[] = $value['id'] . ':' . '1';
                        }
                    }
                }
            }
            if ($maxAge < $age ) {
                $price['price'] += intval($adultPrice['price']);
                $add_to_cart[] = $adultPrice['id'] . ':' . '1';
            }
        }
        $price['add_to_cart'] = implode(',',$add_to_cart);
        return $price;
    }

    public function createDepositProduct($price,$depositamount) {
        $product_title = time().$price; // Code

        $product_args = array(
        'post_title' => $product_title,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'product');

        $new_product_id = wp_insert_post( $product_args );

        // Add meta
        update_post_meta( $new_product_id, '_price', $depositamount );

        return $new_product_id.':1';
    }
}

