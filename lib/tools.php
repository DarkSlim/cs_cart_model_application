<?php

class Tools {
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
                'product_count' => $category_data['product_count']);
        }
        return $product_data;
    }
    /////////////////////////////////////////////////////////////////////////
    // Get total products count
    public static function getTotalproductsCount($cat_id) {
        //First get the products ids from the selected category
        $products_ids = self::getCategoryProducts($cat_id, "");
        $product_count = 0;
        foreach ($products_ids as $product) {
            $product_count++;
        }
        echo ceil($product_count / 9);
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
                'category_id' => $product['category_ids']);
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
                <div class="cs-product" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>">
                    <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" product_title="<?php echo $product_item['product_name'] ?>" class="cs-main-product-image" draggable="false" />
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
                    'product_price' => self::getProductPrice($product)
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
                    <div class="cs-product" product_id="<?php echo $product_item['product_id'] ?>" product_title="<?php echo $product_item['product_name'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>">
                        <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" product_title="<?php echo $product_item['product_name'] ?>" alt="dress" class="cs-main-product-image" draggable="false" />
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
    $cat_id = (isset($_POST['cat_id']) && !empty($_POST['cat_id']) && is_numeric($_POST['cat_id'])) ? $_POST['cat_id'] : "";
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

    Tools::displayProductsData($cat_id, $_POST['page']);
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

    Tools::getTotalCategoryPageCount($_POST['catt_id']);
}