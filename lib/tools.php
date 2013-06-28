<?php

class Tools {
    //////////////////////////////////////////////////////////////////////////////////
    //DESIGNER
    public static $DESIGNERS = array("Acne", "Adidas", "Alexander Wang", "Burberry Prorsum", "By Marlene Birger", "Cheap Monday", "D&G", "Diane von Furstenb", "Fifth Avenue Shoe",
        "Hugo Boss", "Issey Miyake", "Karren Millen", "Thopshop", "Longchamp", "Lipsy London", "Marc Jacobs", "Matthew Williamson", "Max Mara", "Minimarket", "Mulberry", "Ralph Lauren",
        "Rita Saardi", "Rodebjer", "Chloe", "Zadig&Voltarie", "Zara");
    public static $totalSearchPages = 0;
    public function __construct() {
        
    }
    //Get dress categories
    public static function getDressCategories() {
        Db_Actions::DbSelect("SELECT * FROM cscart_dress_type_category");
        $result = Db_Actions::DbGetResults();
        if (!isset($result->empty_result)) {
            foreach ($result as $cat) {
                ?>
                <li><a href="#" data-typec="<?php echo $cat->label ?>" order_id="<?php echo $cat->order_id ?>" data-parentcat="1"><?php echo $cat->label ?></a>
                    <?php self::getDressSubacategories($cat->id); ?>
                </li>
                <?php
            }
        }
    }
    //Get dress category sub categories
    public static function getDressSubacategories($parent_cat_id) {
        Db_Actions::DbSelect("SELECT * FROM cscart_dress_type_subcategory WHERE dress_type_category_id=$parent_cat_id");
        $result = Db_Actions::DbGetResults();
        if (!isset($result->empty_result)) {
            ?><ul><?php
                foreach ($result as $subcat) {
                    ?>
                    <li><a href="#" data-typec="<?php echo $subcat->label ?>"><?php echo $subcat->label ?></a></li>
                    <?php
                }
                ?></ul><?php
            }
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
                    'category_dress_type_id' => $product['category_dress_type_id'],
                    'subcategory_dress_type_id' => $product['subcategory_dress_type_id']);
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
                    Db_Actions::DbSelect("SELECT category_id, category_dress_type_id FROM cscart_products_categories WHERE product_id=$id->product_id");
                    $cat_ids = Db_Actions::DbGetResults();
                    if (!isset($cat_ids->empty_result)) {

                        foreach ($cat_ids as $cat) {
                            switch ($model_type) {
                                case "girl":
                                    if ($cat->category_id == 260) {
                                        $idsArrayFromCats[$counter]['id'] = $id->product_id;
                                        $idsArrayFromCats[$counter]['category_dress_type_id'] = $cat->category_dress_type_id;
                                    }
                                    break;
                                case "boy":
                                    if ($cat->category_id == 261) {
                                        $idsArrayFromCats[$counter]['id'] = $id->product_id;
                                        $idsArrayFromCats[$counter]['category_dress_type_id'] = $cat->category_dress_type_id;
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
                    'category_dress_type_id' => $product['category_dress_type_id'],
                    'subcategory_dress_type_id' => $product['subcategory_dress_type_id']);
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
                <div class="cs-product  <?php if ($counter == 2) echo 'cs-prd-middle'; ?>" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>" category_dress_type_id="<?php echo $product_item['category_dress_type_id'] ?>" subcategory_dress_type_id="<?php echo $product_item['subcategory_dress_type_id'] ?>">
                    <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" category_dress_type_id="<?php echo $product_item['category_dress_type_id'] ?>"  subcategory_dress_type_id="<?php echo $product_item['subcategory_dress_type_id'] ?>" />
                    <h3 class="cs-product-title"><?php echo substr($product_item['product_name'], 0, 14) ?></h3>
                    <h4 class="cs-price">$<?php echo number_format($product_item['product_price'], 2) ?></h4>
                    <div class="cs-variations">
                        <?php //self::getProductColorVariations($product_item['product_id']); ?>
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
                Db_Actions::DbSelect("SELECT category_id, category_dress_type_id FROM cscart_products_categories WHERE product_id=$id->product_id");
                $cat_ids = Db_Actions::DbGetResults();
                if (!isset($cat_ids->empty_result)) {

                    foreach ($cat_ids as $cat) {
                        switch ($model_type) {
                            case "girl":
                                if ($cat->category_id == 260) {
                                    $idsArrayFromCats[$counter]['id'] = $id->product_id;
                                    $idsArrayFromCats[$counter]['category_dress_type_id'] = $cat->category_dress_type_id;
                                }
                                break;
                            case "boy":
                                if ($cat->category_id == 261) {
                                    $idsArrayFromCats[$counter]['id'] = $id->product_id;
                                    $idsArrayFromCats[$counter]['category_dress_type_id'] = $cat->category_dress_type_id;
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
        $parent_cat = (isset($_POST['parent_cat']) && $_POST['parent_cat'] == 1) ? true : false;

        //Parent cat
        if ($parent_cat == true) {
            switch ($model_type) {
                case "girl":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE category_id=260 AND  category_dress_type_id='" . $prd_type . "'");
                    break;
                case "boy":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE category_id=261 AND  category_dress_type_id='" . $prd_type . "'");
                    break;
                default:
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE category_id=260 AND  category_dress_type_id='" . $prd_type . "'");
                    break;
            }
            $products_ids = Db_Actions::DbGetResults();
        }
        else if ($prd_type == 'no-type' && $designer_type == "") {
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
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  category_dress_type_id='" . $prd_type . "' AND category_id=260");
                    break;
                case "boy":
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  category_dress_type_id='" . $prd_type . "' AND category_id=261");
                    break;
                default:
                    Db_Actions::DbSelect("SELECT * FROM cscart_products_categories WHERE  category_dress_type_id='" . $prd_type . "' AND category_id=260");
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
                    'category_dress_type_id' => $product->category_dress_type_id,
                    'subcategory_dress_type_id' => $product->subcategory_dress_type_id);
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
                    <div class="cs-product <?php if ($counter == 2) echo 'cs-prd-middle'; ?>" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>" category_dress_type_id="<?php echo $product_item['category_dress_type_id'] ?>"  subcategory_dress_type_id="<?php echo $product_item['subcategory_dress_type_id'] ?>">
                        <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" category_dress_type_id="<?php echo $product_item['category_dress_type_id'] ?>"  subcategory_dress_type_id="<?php echo $product_item['subcategory_dress_type_id'] ?>" />
                        <h3 class="cs-product-title"><?php echo substr($product_item['product_name'], 0, 14) ?></h3>
                        <h4 class="cs-price">$<?php echo number_format($product_item['product_price'], 2) ?></h4>
                        <div class="cs-variations">
                            <?php //self::getProductColorVariations($product_item['product_id']); ?>
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

        $category_dress_type_id = isset($_POST['product_type']) ? $_POST['product_type'] : '';
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

        $category_dress_type_id = isset($_POST['product_type']) ? $_POST['product_type'] : '';
        $designer_type = isset($_POST['designer_type']) && !empty($_POST['designer_type']) ? $_POST['designer_type'] : "";
        if (isset($_POST['model_type'])) {
            $model_type = $_POST['model_type'];
            switch ($model_type) {
                case "girl":
                    if (!empty($category_dress_type_id)) {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260 AND category_dress_type_id='$category_dress_type_id'");
                    }
                    else if (!empty($designer_type)) {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260 AND brand_type='$designer_type'");
                    }
                    else {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260");
                    }
                    break;
                case "boy":
                    if (!empty($category_dress_type_id)) {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=261 AND category_dress_type_id='$category_dress_type_id'");
                    }
                    else if (!empty($designer_type)) {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=261 AND brand_type='$designer_type'");
                    }
                    else {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=261");
                    }

                    break;
                default:
                    if (!empty($category_dress_type_id)) {
                        Db_Actions::DbSelect("SELECT DISTINCT COUNT(product_id) AS totalprds FROM cscart_products_categories WHERE category_id=260 AND category_dress_type_id='$category_dress_type_id'");
                    }
                    else if (!empty($designer_type)) {
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
                'category_dress_type_id' => $product['category_dress_type_id'],
                'subcategory_dress_type_id' => $product->subcategory_dress_type_id);
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
                <div class="cs-product <?php if ($counter == 2) echo 'cs-prd-middle'; ?>" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>" category_dress_type_id="<?php echo $product_item['category_dress_type_id'] ?>"  subcategory_dress_type_id="<?php echo $product_item['subcategory_dress_type_id'] ?>">
                    <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" category_dress_type_id="<?php echo $product_item['category_dress_type_id'] ?>"  subcategory_dress_type_id="<?php echo $product_item['subcategory_dress_type_id'] ?>" />
                    <h3 class="cs-product-title"><?php echo substr($product_item['product_name'], 0, 14) ?></h3>
                    <h4 class="cs-price">$<?php echo number_format($product_item['product_price'], 2) ?></h4>
                    <div class="cs-variations">
                        <?php //self::getProductColorVariations($product_item['product_id']); ?>
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
    public static function GetDressTypeByID($productID = null) {

        $category_dress_type_id = Db_Actions::DbSelectRow("SELECT category_dress_type_id FROM cscart_products WHERE product_id=$productID");
        if (!isset($category_dress_type_id->empty_result)) {
            return $category_dress_type_id->category_dress_type_id;
        }
        return 0;
    }
    /////////////////////////////////////////////////////////////////////////
    // Load recent products
    public static function loadRecentProducts($curr_page = 1) {
        $recent_products = $_SESSION['recently_viewed_products'];
        $model_type = $_POST['model_type'];
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
        if (!isset($products_ids->empty_result)) {
            $allowed_products = array();
            foreach ($products_ids as $id) {
                array_push($allowed_products, $id->product_id);
            }
        }


        //First get the products ids from the selected category
        $product_data = array();
        if (!empty($recent_products)) {
            foreach ($recent_products as $product) {
                if (in_array($product, $allowed_products)) {
                    $product_data[] = array('product_id' => $product,
                        'product_name' => self::getProductName($product),
                        'product_image_url' => $root_url . self::getProductImage($product),
                        'product_price' => self::getProductPrice($product),
                        'category_dress_type_id' => $product->category_dress_type_id,
                        'subcategory_dress_type_id' => $product->subcategory_dress_type_id);
                }
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
                    <div class="cs-product <?php if ($counter == 2) echo 'cs-prd-middle'; ?>" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>" category_dress_type_id="<?php echo $product_item['category_dress_type_id'] ?>"  subcategory_dress_type_id="<?php echo $product_item['subcategory_dress_type_id'] ?>">
                        <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" category_dress_type_id="<?php echo $product_item['category_dress_type_id'] ?>"  subcategory_dress_type_id="<?php echo $product_item['subcategory_dress_type_id'] ?>" />
                        <h3 class="cs-product-title"><?php echo substr($product_item['product_name'], 0, 14) ?></h3>
                        <h4 class="cs-price">$<?php echo number_format($product_item['product_price'], 2) ?></h4>
                        <div class="cs-variations">
                            <?php //self::getProductColorVariations($product_item['product_id']);  ?>
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
    //////////////////////////////////////////////////////////
    //Get product color variations
    public static function getProductColorVariations($productID) {
        Db_Actions::DbSelect("SELECT * FROM cscart_product_color_variations WHERE product_id=$productID ORDER BY id DESC");
        $data = Db_Actions::DbGetResults();
        if (!isset($data->empty_result)) {
            $counter = 1;
            $product_data = array();
            foreach ($data as $product) {
                if ($counter <= 3) {

                    $product_data[] = array('product_id' => $product->color_product_id,
                    'product_name' => self::getProductName($product->color_product_id),
                    'product_image_url' => $root_url . self::getProductImage($product->color_product_id),
                    'product_price' => self::getProductPrice($product->color_product_id),
                    'category_dress_type_id' => self::getCategoryDressTypeID($product->color_product_id),
                    'subcategory_dress_type_id' => self::getSubCategoryDressTypeID($product->color_product_id),
                    'color' => $product->color_variation
                    );
                }
                $counter++;
            }

            foreach ($product_data as $variation) {
                ?><a href="#" class="cs-varr <?php echo $variation['color'] ?>" product_id="<?php echo $variation['product_id'] ?>" product_title="<?php echo $variation['product_name'] ?>" product_price="<?php echo $variation['product_price'] ?>" category_dress_type_id="<?php echo $variation['category_dress_type_id'] ?>"  subcategory_dress_type_id="<?php echo $variation['subcategory_dress_type_id'] ?>" img_url="<?php echo $variation['product_image_url'] ?>" ><img src="img/product-images/variation-<?php echo $variation['color'] ?>.png" width="14" height="13" /></a><?php
            }
        }
    }
    
    public static function getCategoryDressTypeID($productID){
         $data = Db_Actions::DbSelectRow("SELECT category_dress_type_id FROM cscart_products WHERE product_id=$productID");
         if(!isset($data->empty_result)){
             return $data->category_dress_type_id;
         }
         else return "";
    } 
    public static function getSubCategoryDressTypeID($productID){
         $data = Db_Actions::DbSelectRow("SELECT subcategory_dress_type_id FROM cscart_products WHERE product_id=$productID");
         if(!isset($data->empty_result)){
             return $data->subcategory_dress_type_id;
         }
         else return "";
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
////////////////////////////////////////////////////////////////////////////////////////////////////
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
//////////////////////////
//Get product variations
if (isset($_POST['get_prd_variations'])) {
    define('AREA', 'C');
    require '../../prepare.php';
    require '../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../lib/db_actions.php');
    require_once("../lib/tools.php");
    $root_url = $config['current_location'];

    Tools::getProductColorVariations($_POST['variations_product_id']);
}
