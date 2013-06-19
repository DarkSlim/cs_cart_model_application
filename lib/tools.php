<?php

class Tools {
    ///////////////////////////////////////////////////////////////////////////////
    //TYPE
    //Јакни
    public static $DRESS_TYPE_JACKETS = "jackets";
    //subcategories
    public static $SUB_JACKETS = array('Blazer', 'Fur', 'Jeans jacket', 'Jacket', 'Coat', 'Cape', 'Leather jacket', 'Suit jacket', 'Wind breaker', 'Rain jacket', 'Trench coat', 'Winter jacket');
    //Блузи
    public static $DRESS_TYPE_TOPS = "tops";
    public static $SUB_TOPS = array('Top', 'Tank top', 'T-shirt', 'Blouse', 'Turtleneck', 'Polo shirt', 'Shirt', 'Tunic', 'Sweater', 'Vest', 'Cardigan', 'Hoodie', 'Tracksuit jack', 'Suspemders', 'Pullover', 'Cache-ceur');
    //Пантолони, кратки пантолони, Фармерки, сукњи, 
    public static $DRESS_TYPE_BOTTOMS = "bottoms";
    public static $SUB_BOTTOMS = array('Pants', 'Shorts', 'Jeans', 'Sweatpants', 'Suit pants', 'Pedal pusher', 'Knickers', 'Long shorts', 'Skirt', 'Suit skirt', 'Sarong');
    //фустани
    public static $DRESS_TYPE_DRESSES = "dresses";
    public static $SUB_DRESSES = array('Dress', 'Jumpsuit', 'Maxi Dress', 'Mini Dress', 'Dungarees', 'Playsuit');
    //public static $DRESS_TYPE_SUITS="suits";
    //долна облека
    public static $DRESS_TYPE_UNDERWEAR = "underwear";
    public static $SUB_UNDERWEAR = array('Underwear', 'Body', 'Corset', 'Swimsuit', 'Negligee', 'Bikini', 'Bra', 'Pantie', 'Garter', 'Pyjamas', 'Sports bra', 'Boxer shorts', 'Swim pants', 'Long johns');
    //хулахопки, трикотажа
    public static $DRESS_TYPE_HOSIERY = "hosiery";
    public static $SUB_HOSIERY = array('Tights', 'Treggings', 'Socks', 'Over-knees', 'Stay ups', 'Leg warmers');
    //накит
    public static $DRESS_TYPE_JEWELLERY = "jewellery";
    public static $SUB_JEWELLERY = array('Necklase', 'Braclet', 'Brooch', 'Ring', 'Earring', 'Anklet', 'Watch');
    //капи
    public static $DRESS_TYPE_HATS = "hats";
    public static $SUB_HATS = array('Hat', 'Cap', 'Head band', 'Beret', 'Turban', 'Helmet', 'Ear muffs', 'Winter hat');
    //марами
    public static $DRESS_TYPE_SCARVES = "scarves";
    public static $SUB_SCARVES = array('Scarf', 'Bow tie', 'Collar', 'Tie', 'Hoodie scarf');
    //ракавици
    public static $DRESS_TYPE_GLOVES = "gloves";
    public static $SUB_GLOVES = array('Gloves', 'Boxing gloves');
    //торби
    public static $DRESS_TYPE_BAGS = "bags";
    public static $SUB_BAGS = array('Handbag', 'Shoulderbag', 'Clutch', 'Briefcase', 'Paperbag', 'Basket', 'Fanny pack', 'Rucksack', 'Suitcase', 'Shopping bag', 'Wallet', 'iPhone Case', 'iPad Bag');
    //појаси
    public static $DRESS_TYPE_BELTS = "belts";
    //цвикери
    public static $DRESS_TYPE_EYEWEAR = "eyewear";
    public static $SUB_EYEWEAR = array('Glasses', 'Sunglasses', 'Mask');
    //обувки
    public static $DRESS_TYPE_SHOES = "shoes";
    public static $SUB_SHOES = array('Heels', 'Boots', 'Sneakers', 'Wedges', 'Sandals', 'Flats', 'Rubber boot', 'Skates');
    //додатоци, миленици, маски, украси, и други додатоци.
    public static $DRESS_TYPE_EXTRAS = "extras";
    public static $SUB_EXTRAS = array('Tattoos');
    //////////////////////////////////////////////////////////////////////////////////
    //DESIGNER
    public static $DESIGNERS = array("Acne", "Adidas", "Alexander Wang", "Burberry Prorsum", "By Marlene Birger", "Cheap Monday", "D&G", "Diane von Furstenb", "Fifth Avenue Shoe",
        "Hugo Boss", "Issey Miyake", "Karren Millen", "Thopshop", "Longchamp", "Lipsy London", "Marc Jacobs", "Matthew Williamson", "Max Mara", "Minimarket", "Mulberry", "Ralph Lauren",
        "Rita Saardi", "Rodebjer", "Chloe", "Zadig&Voltarie", "Zara");
    public static $totalSearchPages = 0;
    public function __construct() {
        
    }
/////////////////////////////////////////////////////////////////////
// Get data from url
    public static function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
/////////////////////////////////////////////////////////////////////
//Get total product count
    public static function getTotalProductCount() {
        Db_Actions::DbSelect("SELECT COUNT(id) FROM  cscart_products");
    }
/////////////////////////////////////////////////////////////////////
//Get all top level categories
    public static function getCategories() {
        $top_level_cats = array();
//Select all top level categories
        Db_Actions::DbSelect("SELECT * FROM cscart_categories LEFT OUTER JOIN cscart_category_descriptions ON cscart_categories.category_id = cscart_category_descriptions.category_id WHERE cscart_categories.parent_id=0");
        $result = Db_Actions::DbGetResults();
        if (!isset($result->empty_result)) {
            foreach ($result as $cat) {
                $top_level_cats[] = array('id' => $cat->category_id,
                    'cat_name' => $cat->category,
                    'company_id' => $cat->company_id,
                    'status' => $cat->status,
                    'product_count' => $cat->product_count,
                    'is_op' => $cat->is_op,
                    'usergroup_ids' => $cat->usergroup_ids);
            }
        }
        if (!isset($result->empty_result)) {
            return $result;
        }
        else {
            return new stdClass();
        }
    }
/////////////////////////////////////////////////////////////////////
//Get subcategories from some category
    public static function getSubCategories($parent_cat_id) {
        Db_Actions::DbSelect("SELECT * FROM cscart_categories LEFT OUTER JOIN cscart_category_descriptions ON cscart_categories.category_id = cscart_category_descriptions.category_id WHERE cscart_categories.parent_id=$parent_cat_id");
        $result = Db_Actions::DbGetResults();
        if (!isset($result->empty_result)) {
            return $result;
        }
        else {
            return new stdClass();
        }
    }
////////////////////////////////////////////////////////////////////////
// Get category data
    public static function getCategoryData($category_id) {
        $data = fn_get_category_data($category_id);
        return $data;
    }
/////////////////////////////////////////////////////////////////////////
// Get product data
    public static function getProductData($product_id) {
        $auth = fn_fill_auth();
        $data = fn_get_product_data($product_id, $auth);
        return $data;
    }
////////////////////////////////////////////////////////////////////////
//Add product to cart
    public static function addProductToCart($product_id) {
        $auth = fn_fill_auth();
        $cart = & $_SESSION['cart'];
        $products_array = array(
            $product_id => array(
                'product_id' => $product_id,
                'amount' => 1
            )
        );
        $data = fn_add_product_to_cart($products_array, $cart, $auth);
        return $data;
    }
////////////////////////////////////////////////////////////////////////
//Delete product from cart
    public static function deleteProductFromCart($product_id) {
        $auth = fn_fill_auth();
        $cart = $_SESSION['cart'];
        fn_delete_cart_product($cart, $cart_id);
    }
////////////////////////////////////////////////////////////////////////
// Get product name
    public static function getProductName($product_id) {
        $name = fn_get_product_name($product_id, $lang_code = CART_LANGUAGE, $as_array = false);
        return $name;
    }
////////////////////////////////////////////////////////////////////////
// Get product price
    public static function getProductPrice($product_id, $amount = 1) {
        $auth = fn_fill_auth();
        $price = fn_get_product_price($product_id, $amount, $auth);
        return $price;
    }
/////////////////////////////////////////////////////////////////////////
// Get product image
    public static function getProductImage($product_id) {
        $products = array($product_id);
        $data = fn_get_image_pairs($products, 'product', 'M', true, true);
        foreach ($data as $img) {
            if (is_array($img)) {
                foreach ($img as $img_paths) {
                    if (array_key_exists('detailed', $img_paths)) {
                        return $img_paths['detailed']['http_image_path'];
                    }
                }
            }
        }
        return false;
    }
/////////////////////////////////////////////////////////////////////////
// Get products filters
    public static function getProductsFilters($category_ids = 0, $items_per_page = 0) {
        $params = array('category_ids' => $category_ids);
        $data = fn_get_product_filters($params, $items_per_page);
        return $data;
    }
/////////////////////////////////////////////////////////////////////////
// Get products from category
    public static function getCategoryProducts($category_id, $products_per_page = 9, $curr_page = 1) {
        $params = array();
        $params['cid'] = $category_id;
        $params['extend'] = array('categories');
        $params['page'] = $curr_page;
        list($products, $search) = fn_get_products($params, $products_per_page);
        return $products;
    }
/////////////////////////////////////////////////////////////////////////
// Get products data from any category in desired format
    public static function getMyProductsData($category_id, $products_per_page = 9, $curr_page = 1) {
//First get the products ids from the selected category
        $products_ids = self::getCategoryProducts($category_id, $products_per_page, $curr_page);
        $product_data = array();
        $category_data = self::getCategoryData($category_id);
        foreach ($products_ids as $product) {
            $product_data[] = array('product_id' => $product['product_id'],
                'product_name' => self::getProductName($product['product_id']),
                'product_image_url' => $root_url . self::getProductImage($product['product_id']),
                'product_price' => self::getProductPrice($product['product_id']),
                'product_count' => $category_data['product_count'],
                'dress_type' => $product['dress_type']);
        }
        return $product_data;
    }
    ////////////////////////////////////////////////////////////////////////
    //Search
    public static function SEARCH_PRODUCTS($curr_page = 1) {
        $searh_term = $_POST['search_term'];
        $model_type = $_POST['model_type'];

        Db_Actions::DbSelect("SELECT product_id FROM cscart_product_descriptions WHERE product LIKE '%" . $searh_term . "%'");
        $product_ids = Db_Actions::DbGetResults();

        $idsArrayFromCats = array();
        if (!isset($product_ids->empty_result)) {
            $counter = 0;
            foreach ($product_ids as $id) {
                Db_Actions::DbSelect("SELECT category_id, dress_type FROM cscart_products_categories WHERE product_id=$id->product_id");
                $cat_ids = Db_Actions::DbGetResults();
                if (!isset($cat_ids->empty_result)) {

                    foreach ($cat_ids as $cat) {
                        switch ($model_type) {
                            case "girl":
                                if ($cat->category_id == 260) {
                                    $idsArrayFromCats[$counter]['id'] = $id->product_id;
                                    $idsArrayFromCats[$counter]['dress_type'] = $cat->dress_type;
                                }
                                break;
                            case "boy":
                                if ($cat->category_id == 261) {
                                    $idsArrayFromCats[$counter]['id'] = $id->product_id;
                                    $idsArrayFromCats[$counter]['dress_type'] = $cat->dress_type;
                                }
                                break;
                        }
                        $counter++;
                    }
                }
            }
        }
        self::$totalSearchPages = count($idsArrayFromCats);
        //return results
        $product_data = array();
        foreach ($idsArrayFromCats as $product) {
            if (empty($product['id'])) {
                continue;
            }
            $product_data[] = array('product_id' => $product['id'],
                'product_name' => self::getProductName($product['id']),
                'product_image_url' => $root_url . self::getProductImage($product['id']),
                'product_price' => self::getProductPrice($product['id']),
                'dress_type' => $product['dress_type']);
        }

        $max_products_per_page = 9;
        $offset = $curr_page * $max_products_per_page - $max_products_per_page;
        $slice = array_slice($product_data, $offset, $max_products_per_page);
        $counter = 0;
        foreach ($slice as $product_item) {
            $counter++;
            if ($counter == 1) {
                ?><div class="cs-product-row"><?php
            }
            ?>
                <div class="cs-product  <?php if ($counter == 2) echo 'cs-prd-middle'; ?>" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>" dress_type="<?php echo $product_item['dress_type'] ?>">
                    <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" dress_type="<?php echo $product_item['dress_type'] ?>" />
                    <h3 class="cs-product-title"><?php echo substr($product_item['product_name'], 0, 14) ?></h3>
                    <h4 class="cs-price">$<?php echo number_format($product_item['product_price'], 2) ?></h4>
                    <div class="cs-variations">
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                    </div>
                </div>
                <?php
                if ($counter == 3) {
                    ?></div><?php
                $counter = 0;
            }
        }
    }
    ////////////////////////////////////////////////////////////////////////
    //Search
    public static function SEARCH_PRODUCTS_COUNT() {
        $searh_term = $_POST['search_term'];
        $model_type = $_POST['model_type'];

        Db_Actions::DbSelect("SELECT product_id FROM cscart_product_descriptions WHERE product LIKE '%" . $searh_term . "%'");
        $product_ids = Db_Actions::DbGetResults();

        $idsArrayFromCats = array();
        if (!isset($product_ids->empty_result)) {
            $counter = 0;
            foreach ($product_ids as $id) {
                Db_Actions::DbSelect("SELECT category_id, dress_type FROM cscart_products_categories WHERE product_id=$id->product_id");
                $cat_ids = Db_Actions::DbGetResults();
                if (!isset($cat_ids->empty_result)) {

                    foreach ($cat_ids as $cat) {
                        switch ($model_type) {
                            case "girl":
                                if ($cat->category_id == 260) {
                                    $idsArrayFromCats[$counter]['id'] = $id->product_id;
                                    $idsArrayFromCats[$counter]['dress_type'] = $cat->dress_type;
                                }
                                break;
                            case "boy":
                                if ($cat->category_id == 261) {
                                    $idsArrayFromCats[$counter]['id'] = $id->product_id;
                                    $idsArrayFromCats[$counter]['dress_type'] = $cat->dress_type;
                                }
                                break;
                        }
                        $counter++;
                    }
                }
            }
        }
        self::$totalSearchPages = count($idsArrayFromCats);
    }
    ////////////////////////////////////////////////////////////////////////
    //GET ALL PRODUCTS BY PRODUCT TYPE
    public static function GET_ALL_PRODUCTS_BY_TYPE($prd_type, $curr_page = 1) {
        $model_type = $_POST['model_type'];
        $designer_type = isset($_POST['designer_type']) && !empty($_POST['designer_type']) ? $_POST['designer_type'] : "";

        if ($prd_type == 'no-type' && $designer_type == "") {
            switch ($model_type) {
                case "girl":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE category_id=260");
                    break;
                case "boy":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE category_id=261");
                    break;
                default:
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE category_id=260");
                    break;
            }
            $products_ids = Db_Actions::DbGetResults();
        }
        else if ($prd_type != 'no-type') {
            switch ($model_type) {
                case "girl":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  dress_type='" . $prd_type . "' AND category_id=260");
                    break;
                case "boy":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  dress_type='" . $prd_type . "' AND category_id=261");
                    break;
                default:
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  dress_type='" . $prd_type . "' AND category_id=260");
                    break;
            }
            $products_ids = Db_Actions::DbGetResults();
        }
        else if ($prd_type == 'no-type' && $designer_type != "") {
            switch ($model_type) {
                case "girl":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  brand_type='" . $designer_type . "' AND category_id=260");
                    break;
                case "boy":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  brand_type='" . $designer_type . "' AND category_id=261");
                    break;
                default:
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  brand_type='" . $designer_type . "' AND category_id=260");
                    break;
            }
            $products_ids = Db_Actions::DbGetResults();
        }



        if (!isset($products_ids->empty_result)) {
            $product_data = array();
            foreach ($products_ids as $product) {
                $product_data[] = array('product_id' => $product->product_id,
                    'product_name' => self::getProductName($product->product_id),
                    'product_image_url' => $root_url . self::getProductImage($product->product_id),
                    'product_price' => self::getProductPrice($product->product_id),
                    'dress_type' => $product->dress_type);
            }

            $max_products_per_page = 9;
            $offset = $curr_page * $max_products_per_page - $max_products_per_page;
            $slice = array_slice($product_data, $offset, $max_products_per_page);
            $counter = 0;
            foreach ($slice as $product_item) {
                $counter++;
                if ($counter == 1) {
                    ?><div class="cs-product-row"><?php
                    }
                    ?>
                    <div class="cs-product <?php if ($counter == 2) echo 'cs-prd-middle'; ?>" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>" dress_type="<?php echo $product_item['dress_type'] ?>">
                        <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" dress_type="<?php echo $product_item['dress_type'] ?>" />
                        <h3 class="cs-product-title"><?php echo substr($product_item['product_name'], 0, 14) ?></h3>
                        <h4 class="cs-price">$<?php echo number_format($product_item['product_price'], 2) ?></h4>
                        <div class="cs-variations">
                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                        </div>
                    </div>
                    <?php
                    if ($counter == 3) {
                        ?></div><?php
                    $counter = 0;
                }
            }
        }
        else {
            return new stdClass();
        }
    }
    /////////////////////////////////////////////////////////////////////////
    // Get total products count
    public static function getTotalSearchResultsCount() {

        $dress_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
        $search_term = $_POST['search_term'];

        if (isset($_POST['model_type'])) {
            $model_type = $_POST['model_type'];
            switch ($model_type) {
                case "girl":
                    Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260");
                    Db_Actions::DbSelect("SELECT product_id FROM cscart_product_descriptions WHERE product LIKE '%" . $search_term . "%'");
                    break;
                case "boy":
                    Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=261");

                    break;
            }
        }
        $products_count = Db_Actions::DbGetResults();
        if (!isset($products_count->empty_result)) {
            foreach ($products_count as $count) {
                echo ceil($count->totalprds / 9);
            }
        }
        else {
            echo 0;
        }
    }
/////////////////////////////////////////////////////////////////////////
// Get total products count
    public static function getTotalproductsCount($cat_id) {
//First get the products ids from the selected category
        
        $dress_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
        $designer_type = isset($_POST['designer_type']) && !empty($_POST['designer_type']) ? $_POST['designer_type'] : "";
        if (isset($_POST['model_type'])) {
            $model_type = $_POST['model_type'];
            switch ($model_type) {
                case "girl":
                    if (!empty($dress_type)) {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260 AND dress_type='$dress_type'");
                    }
                    else if(!empty($designer_type)){
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260 AND brand_type='$designer_type'");
                    }
                    else {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260");
                    }
                    break;
                case "boy":
                    if (!empty($dress_type)) {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=261 AND dress_type='$dress_type'");
                    }
                    else if(!empty($designer_type)){
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=261 AND brand_type='$designer_type'");
                    }
                    else {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=261");
                    }

                    break;
                default:
                    if (!empty($dress_type)) {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260 AND dress_type='$dress_type'");
                    }
                    else if(!empty($designer_type)){
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260 AND brand_type='$designer_type'");
                    }
                    else {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260");
                    }
                    break;
            }
        }
        $products_count = Db_Actions::DbGetResults();
        if (!isset($products_count->empty_result)) {
            foreach ($products_count as $count) {
                echo ceil($count->totalprds / 9);
            }
        }
        else {
            echo 0;
        }
    }
/////////////////////////////////////////////////////////////////////////
// Display products
    public static function displayProductsData($category_id, $curr_page = 1) {
//First get the products ids from the selected category
        $products_ids = self::getCategoryProducts($category_id, "", $curr_page);
        $product_data = array();
        foreach ($products_ids as $product) {
            $product_data[] = array('product_id' => $product['product_id'],
                'product_name' => self::getProductName($product['product_id']),
                'product_image_url' => $root_url . self::getProductImage($product['product_id']),
                'product_price' => self::getProductPrice($product['product_id']),
                'category_id' => $product['category_ids'],
                'dress_type' => $product['dress_type']);
        }

//display the products
        $max_products_per_page = 9;
        $offset = $curr_page * $max_products_per_page - $max_products_per_page;
        $slice = array_slice($product_data, $offset, $max_products_per_page);
        $counter = 0;
        foreach ($slice as $product_item) {
            $counter++;
            if ($counter == 1) {
                ?><div class="cs-product-row"><?php
                }
                ?>
                <div class="cs-product <?php if ($counter == 2) echo 'cs-prd-middle'; ?>" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>" dress_type="<?php echo $product_item['dress_type'] ?>">
                    <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" dress_type="<?php echo $product_item['dress_type'] ?>" />
                    <h3 class="cs-product-title"><?php echo substr($product_item['product_name'], 0, 14) ?></h3>
                    <h4 class="cs-price">$<?php echo number_format($product_item['product_price'], 2) ?></h4>
                    <div class="cs-variations">
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                    </div>
                </div>
                <?php
                if ($counter == 3) {
                    ?></div><?php
                $counter = 0;
            }
        }
    }
    ////////////////////////////////////////////////////////////////////////
    //Get total products count in category
    public static function getTotalCategoryPageCount($cat_id) {

        $category_data = self::getCategoryData($cat_id);
        echo ceil((int) $category_data['product_count'] / 9);
    }
    /////////////////////////////////////////////////
    //parse int
    public static function parseInt($string) {

        if (preg_match('/(\d+)/', $string, $array)) {
            return $array[1];
        }
        else {
            return 0;
        }
    }
    /////////////////////////////////////////////////////////////////////////
    // Load background images
    public static function loadBackgroundImages() {
        $dir = scandir('../img/bgs');
        $bg_images = array();
        foreach ($dir as $file) {
            if ($file != "." && $file != "..") {
                if (file_exists("../img/bgs/" . $file)) {
                    $currFileExt = pathinfo("../img/bgs/" . $file, 4);
                    if ($currFileExt == "jpg" || $currFileExt == "png" || $currFileExt == "gif" || $currFileExt == "jpeg" || $currFileExt == "bmp") {
                        $bg_images[] = $file;
                    }
                }
            }
        }
        $curr_page = isset($_POST['curr_page']) ? intval($_POST['curr_page']) : 1;
        $max_bgs_per_page = 9;
        $offset = $curr_page * $max_bgs_per_page - $max_bgs_per_page;
        $slice = array_slice($bg_images, $offset, $max_bgs_per_page);

        $counter = 0;
        $middleClass = "";
        foreach ($slice as $bg) {
            $counter++;
            if ($counter == 1) {
                ?><div class="cs-product-row"><?php
                }
                if ($counter == 2) {
                    $middleClass = "cs-prd-middle";
                }
                else {
                    $middleClass = "";
                }
                ?>
                <?php $bg_index = self::parseInt($bg); ?>
                <div class="cs-product pad-bot thumb_za_pozadini  <?php echo $middleClass; ?>"
                     index_za_pozadina_e="<?php echo $bg_index; ?>"> 

                    <img src="img/bgs/<?php echo $bg ?>" width="97" height="126" data-bgname="<?php echo $bg ?>" class="cs-main-bg" />
                </div>
                <?php
                if ($counter == 3) {
                    ?></div><?php
                $counter = 0;
            }
        }
    }
    /////////////////////////////////////////////////////////////////////////
    // get number of pages with background images
    public static function getBgsNumpages() {
        $dir = scandir('../img/bgs');
        $bg_images = array();
        foreach ($dir as $file) {
            if ($file != "." && $file != "..") {
                if (file_exists("../img/bgs/" . $file)) {
                    $currFileExt = pathinfo("../img/bgs/" . $file, 4);
                    if ($currFileExt == "jpg" || $currFileExt == "png" || $currFileExt == "gif" || $currFileExt == "jpeg" || $currFileExt == "bmp") {
                        $bg_images[] = $file;
                    }
                }
            }
        }
        $max_bgs_per_page = 9;
        echo ceil(count($bg_images) / $max_bgs_per_page) < 1 ? 1 : ceil(count($bg_images) / $max_bgs_per_page);
    }
    /////////////////////////////////////////////////////////////////////////
    // Load filter images
    public static function loadFilterImages() {
        $dir = scandir('../img/effects');
        $bg_images = array();
        foreach ($dir as $file) {
            if ($file != "." && $file != "..") {
                if (file_exists("../img/effects/" . $file)) {
                    $currFileExt = pathinfo("../img/effects/" . $file, 4);
                    if ($currFileExt == "jpg" || $currFileExt == "png" || $currFileExt == "gif" || $currFileExt == "jpeg" || $currFileExt == "bmp") {
                        $bg_images[] = $file;
                    }
                }
            }
        }
        $curr_page = isset($_POST['curr_page']) ? intval($_POST['curr_page']) : 1;
        $max_bgs_per_page = 9;
        $offset = $curr_page * $max_bgs_per_page - $max_bgs_per_page;
        $slice = array_slice($bg_images, $offset, $max_bgs_per_page);
        echo json_encode($slice);
    }
    /////////////////////////////////////////////////////////////////////////
    // get number of pages with  effect images
    public static function getEffectsNumPages() {
        $dir = scandir('../img/effects');
        $bg_images = array();
        foreach ($dir as $file) {
            if ($file != "." && $file != "..") {
                if (file_exists("../img/bgs/" . $file)) {
                    $currFileExt = pathinfo("../img/bgs/" . $file, 4);
                    if ($currFileExt == "jpg" || $currFileExt == "png" || $currFileExt == "gif" || $currFileExt == "jpeg" || $currFileExt == "bmp") {
                        $bg_images[] = $file;
                    }
                }
            }
        }
        $max_bgs_per_page = 9;
        echo ceil(count($bg_images) / $max_bgs_per_page) < 1 ? 1 : ceil(count($bg_images) / $max_bgs_per_page);
    }
    ///////////////////////////////////////////////////////////////////////////
    //Get dress type by product id
    public static function GetDressTypeByID($productID=null){
            
        $dress_type =  Db_Actions::DbSelectRow("SELECT dress_type FROM cscart_products WHERE product_id=$productID");  
        if (!isset($dress_type->empty_result)) {
            return $dress_type->dress_type;
        }
        return 0;
    }
    /////////////////////////////////////////////////////////////////////////
    // Load recent products
    public static function loadRecentProducts($curr_page = 1) {
        $recent_products = $_SESSION['recently_viewed_products'];
        //First get the products ids from the selected category
        $product_data = array();
        if (!empty($recent_products)) {
            foreach ($recent_products as $product) {
                $product_data[] = array('product_id' => $product,
                    'product_name' => self::getProductName($product),
                    'product_image_url' => $root_url . self::getProductImage($product),
                    'product_price' => self::getProductPrice($product),
                    'dress_type' => self::GetDressTypeByID($product)
                );
            }
            //display the products
            $max_products_per_page = 9;
            $offset = $curr_page * $max_products_per_page - $max_products_per_page;
            $slice = array_slice($product_data, $offset, $max_products_per_page);
            $counter = 0;
            foreach ($slice as $product_item) {
                $counter++;
                if ($counter == 1) {
                    ?><div class="cs-product-row"><?php
                    }
                    ?>
                    <div class="cs-product <?php if ($counter == 2) echo 'cs-prd-middle'; ?>" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>" dress_type="<?php echo $product_item['dress_type'] ?>">
                    <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" dress_type="<?php echo $product_item['dress_type'] ?>" />
                    <h3 class="cs-product-title"><?php echo substr($product_item['product_name'], 0, 14) ?></h3>
                    <h4 class="cs-price">$<?php echo number_format($product_item['product_price'], 2) ?></h4>
                    <div class="cs-variations">
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                        <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                    </div>
                </div>
                    <?php
                    if ($counter == 3) {
                        ?></div><?php
                    $counter = 0;
                }
            }
        }
        else {
            echo "<h4 class='empty-result'>Nothing found.</h4>";
        }
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Select all products from selected category
if (isset($_POST['get_category_data'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('db_actions.php');
    $root_url = $config['current_location'];

    $curr_page = isset($_POST['curr_page']) ? $_POST['curr_page'] : 1;
    $cat_id = isset($_POST['cat_id']) ? $_POST['cat_id'] : "";
    $data = Tools::getMyProductsData($cat_id, 9, $curr_page);
    echo json_encode($data);
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// Load background images
if (isset($_POST['load_bgs'])) {
    Tools::loadBackgroundImages();
}
////////////////////////////////////////абе ти ме слуса го////////////////////////////////////////////////////////////
// Get number of bg pages
if (isset($_POST['getBgNumPages'])) {
    Tools::getBgsNumpages();
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//Load effects
if (isset($_POST['load_efx'])) {
    Tools::loadFilterImages();
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//Get number of effects pages
if (isset($_POST['geEfxNumPages'])) {
    Tools::getEffectsNumPages();
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//Add product to cart
if (isset($_POST['add_prd'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('db_actions.php');
    $root_url = $config['current_location'];

    $success = false;
    //print_r($_SESSION['cart']);
    if (isset($_POST['product_ids'])) {
        if (is_array($_POST['product_ids'])) {
            $products = $_POST['product_ids'];
            foreach ($products as $prd_id) {
                $addProduct = Tools::addProductToCart($prd_id);
                //print_r($addProduct);
                if ($addProduct != false) {
                    $success = true;
                }
            }
        }
    }
    if ($success) {
        echo 1;
    }
    else {
        echo 2;
    }
}
/////////////////////////////////////////////kaj ti e ebaniot kod za toa ////////////////////////////////////////////////////
//Get products count
if (isset($_POST['products_count'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../lib/db_actions.php');
    require_once("../lib/tools.php");
    $root_url = $config['current_location'];
    //$cat_id = (isset($_POST['cat_id']) && !empty($_POST['cat_id']) && is_numeric($_POST['cat_id'])) ? $_POST['cat_id'] : "";

    $model_type = $_POST['model_type'];

    switch ($model_type) {
        case "girl":
            $cat_id = 260;
            break;
        case "boy":
            $cat_id = 261;
            break;
        default:
            $cat_id = 260;
            break;
    }

    Tools::getTotalproductsCount($cat_id);
}
/////////////////////////////////////////////////////////////////////////////////////////////////
//Load products
if (isset($_POST['load_products'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../lib/db_actions.php');
    require_once("../lib/tools.php");
    $root_url = $config['current_location'];


    $cat_id = (isset($_POST['cat_id']) && !empty($_POST['cat_id']) && is_numeric($_POST['cat_id'])) ? $_POST['cat_id'] : "";

    //Tools::displayProductsData($cat_id, $_POST['page']);
    $prd_type = isset($_POST['product_type']) && !empty($_POST['product_type']) ? $_POST['product_type'] : 'no-type';

    Tools::GET_ALL_PRODUCTS_BY_TYPE($prd_type, $_POST['page']);
}
////////////////////////////////////////////////////////////////////////////////////////////////
//Load recent products
if (isset($_POST['load_recent_products'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../lib/db_actions.php');
    require_once("../lib/tools.php");
    $root_url = $config['current_location'];

    Tools::loadRecentProducts();
}
//Get category products count
if (isset($_POST['cat_products_count'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../lib/db_actions.php');
    require_once("../lib/tools.php");
    $root_url = $config['current_location'];

    //Tools::getTotalCategoryPageCount($_POST['catt_id']);

    $model_type = $_POST['model_type'];

    switch ($model_type) {
        case "girl":
            $cat_id = 260;
            break;
        case "boy":
            $cat_id = 261;
            break;
        default:
            $cat_id = 260;
            break;
    }

    Tools::getTotalproductsCount($cat_id);
}
////////////////////////////////////////////////////////////
//Search products
if (isset($_POST['search_me'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../lib/db_actions.php');
    require_once("../lib/tools.php");
    $root_url = $config['current_location'];

    Tools::SEARCH_PRODUCTS($_POST['page']);
}
//////////////////////////
//total search results
if (isset($_POST['search_pages_count'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../lib/db_actions.php');
    require_once("../lib/tools.php");
    $root_url = $config['current_location'];

    Tools::SEARCH_PRODUCTS_COUNT();
    echo ceil(Tools::$totalSearchPages / 9);
}