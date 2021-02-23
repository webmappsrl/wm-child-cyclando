<?php 


class routeProductsOC {

    protected $cookies;
    protected $post_id;
    protected $price;

    public function __construct($cookies,$post_id){
        $this->cookies = $cookies;
        $this->post_id = $post_id;
    }

    public function getHotelProducts () {
        $seasonal_products = $this->getHotelSeasonalVariations($this->post_id);
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
                                $extra_name_price = array($xvariation_name => intval($xprice));
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
            $list_all_variations_name = array();
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
                                $variation_name_price = array($variation_name => intval($price));
                                $list_all_variations_name += array($variation_name => $variation['price_html']);
                                $product_variation_name_price += $variation_name_price;
                            }
                            array_push($variations_name_price,$product_variation_name_price);
                        }
                    }
                }
            return $variations_name_price;
        }
    }

    public function calculatePrice () {
        $hotel = $this->getHotelProducts();
        $extra = $this->getExtraProducts();

        $adults = intval($this->cookies['adults']);
        $kids = intval($this->cookies['kids']);
        $regular = intval($this->cookies['regular']);
        $electric = intval($this->cookies['electric']);
        
        if ($adults) {
            $this->price += $hotel[0]['adult'] * intval($adults);
        }
        if ($kids) {
            $this->price += $hotel[0]['adult'] * intval($kids);
        }
        if ($regular && $extra['bike']) {
            $this->price += $extra['bike'] * intval($regular);
        }
        if ($electric && $extra['ebike']) {
            $this->price += $extra['ebike'] * intval($electric);
        }
        
        return $this->price;
    }



    public function getHotelSeasonalVariations($post_id){
        $seasonal_variations = array();
        if (have_rows('model_season',$post_id)) {
            while( have_rows('model_season',$post_id) ): the_row();
                $variation = get_sub_field('wm_route_quote_model_season_product');
                // $variations = $this->getProductsHotelVariations($variation);
                array_push($seasonal_variations,$variation[0]);
            endwhile;
        }
        
        return $seasonal_variations;
    }
}