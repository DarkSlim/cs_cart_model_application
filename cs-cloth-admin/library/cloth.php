<?php

class Cloth {
    //////////////////////////////////////////////////////////////////////////////////
    //DESIGNER
    public static $DESIGNERS = array("Acne", "Adidas", "Alexander Wang", "Burberry Prorsum", "By Marlene Birger", "Cheap Monday", "D&G", "Diane von Furstenb", "Fifth Avenue Shoe",
        "Hugo Boss", "Issey Miyake", "Karren Millen", "Thopshop", "Longchamp", "Lipsy London", "Marc Jacobs", "Matthew Williamson", "Max Mara", "Minimarket", "Mulberry", "Ralph Lauren",
        "Rita Saardi", "Rodebjer", "Chloe", "Zadig&Voltarie", "Zara");
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
    public static function displayProductsData() {
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
                <div class="cs-product" product_id="<?php echo $product_item['product_id'] ?>" product_price="<?php echo $product_item['product_price'] ?>" category_ids="<?php echo $product_item['category_id'] ?>">
                    <img src="<?php echo $product_item['product_image_url'] ?>" width="97" height="126" alt="dress" class="cs-main-product-image" draggable="false" />
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
    ////////////////////////////////////////////////////////////////////////
    //Get products IDS
    public static function getProductsIds() {
        Db_Actions::DbSelect("SELECT product_id FROM cscart_products_categories WHERE category_id=260 OR category_id=261");
        $result = Db_Actions::DbGetResults();
        if (!isset($result->empty_result)) {
            foreach ($result as $id) {
                echo "<option value='" . $id->product_id . "'>" . $id->product_id . "</option>";
            }
        }
    }
    //Display product data by id
    public static function getProductInfo($productID) {
        $html = "<h3 class='prd-name'>" . self::getProductName($productID) . "</h3>";
        $html .= "<h3 class='prd-price'><strong>$" . number_format(self::getProductPrice($productID), 2) . "</strong></h3>";
        $html .= "<div class='prd-thumb'><img src='" . $root_url . self::getProductImage($productID) . "' style='max-width: 120px; height: auto;' productid='" . $productID . "' /></div>";
        echo $html;
    }
    //Update product type
    public static function updateProductType($productID, $productType) {
        $sub_category = $_POST['dress_sub_category'];
        Db_Actions::DbUpdate("UPDATE cscart_products_categories SET category_dress_type_id='" . $productType . "', subcategory_dress_type_id='" . $sub_category . "' WHERE product_id=$productID");
        Db_Actions::DbUpdate("UPDATE cscart_products SET category_dress_type_id='" . $productType . "', subcategory_dress_type_id='" . $sub_category . "' WHERE product_id=$productID");
        echo 1;
    }
    //Update brand type
    public static function updateBrandType($productID, $productType) {

        Db_Actions::DbUpdate("UPDATE cscart_products_categories SET brand_type='" . $productType . "' WHERE product_id=$productID");
        Db_Actions::DbUpdate("UPDATE cscart_products SET brand_type='" . $productType . "' WHERE product_id=$productID");
        echo 1;
    }
    //Set color variation
    public static function SetProductColorVariation() {
        //Color
        $color = $_POST['color_var'];
        //The product id for which the color variation will be set
        $productID = $_POST['var_product_id'];
        //Color product id
        $color_product_id = $_POST['color_product_id'];
        Db_Actions::DbInsert("INSERT INTO cscart_product_color_variations(product_id, color_product_id, color_variation) VALUES($productID, $color_product_id, '" . $color . "')");
        echo 1;
    }
    //Remove color variation
    public static function RemoveProductColorVariation() {
        //Color
        $color = $_POST['color_variation_to_remove'];
        //The product id for which the color variation will be set
        $removeProductID = $_POST['remove_var_product_id'];

        if ($color == "all") {
            Db_Actions::DbDelete("DELETE FROM cscart_product_color_variations WHERE product_id=$removeProductID");
        }
        else {
            Db_Actions::DbDelete("DELETE FROM cscart_product_color_variations WHERE product_id=$removeProductID AND color_variation='" . $color . "'");
        }
        echo 1;
    }
    //Get dress categories
    public static function getDressCategories() {
        Db_Actions::DbSelect("SELECT * FROM cscart_dress_type_category");
        $result = Db_Actions::DbGetResults();
        if (!isset($result->empty_result)) {
            foreach ($result as $cat) {
                ?><option value="<?php echo $cat->id ?>"><?php echo $cat->label ?></option><?php
                }
            }
        }
        //Get dress category sub categories
        public static function getDressSubacategories($parent_cat_id) {
            Db_Actions::DbSelect("SELECT * FROM cscart_dress_type_subcategory WHERE dress_type_category_id=$parent_cat_id");
            $result = Db_Actions::DbGetResults();
            if (!isset($result->empty_result)) {
                foreach ($result as $subcat) {
                    ?><option value="<?php echo $subcat->id ?>"><?php echo $subcat->label ?></option><?php
            }
        }
    }
    public static function getAllCats() {
        Db_Actions::DbSelect("SELECT * FROM cscart_dress_type_category");
        $result = Db_Actions::DbGetResults();
        if (!isset($result->empty_result)) {
            foreach ($result as $cat) {
                ?>
                <optgroup label="<?php echo $cat->label ?>">
                    <?php
                    Db_Actions::DbSelect("SELECT * FROM cscart_dress_type_subcategory WHERE dress_type_category_id=" . $cat->id);
                    $result2 = Db_Actions::DbGetResults();
                    if (!isset($result2->empty_result)) {
                        foreach ($result2 as $subcat) {
                            ?><option value="<?php echo $subcat->id ?>"><?php echo $subcat->label ?></option><?php
                        }
                    }
                    ?>
                </optgroup>
                <?php
            }
        }
    }
    //Set overlapping
    public static function setCatOverlap() {
        $sub_cat = $_POST['subcat_id'];
        $overlap_cat_ids = $_POST['overlap_ids'];
        
        Db_Actions::DbUpdate("UPDATE cscart_dress_type_subcategory SET overlaping_with_subcategories___IDs='-1' WHERE overlaping_with_subcategories___IDs=$sub_cat");
        //Add cat ids to subcategory
        Db_Actions::DbUpdate("UPDATE cscart_dress_type_subcategory SET overlaping_with_subcategories___IDs='" . implode(",", $overlap_cat_ids) . "' WHERE id=$sub_cat");
        //Add subcategory ID to verlapping cats
        for ($i = 0; $i < count($overlap_cat_ids); $i++) {
            Db_Actions::DbUpdate("UPDATE cscart_dress_type_subcategory SET overlaping_with_subcategories___IDs='" . $sub_cat . "' WHERE id=$overlap_cat_ids[$i]");
        }
        echo 1;
    }
    //Get overlap cats
    public static function getOverlapCats() {
        $subcat_id = $_POST['subcat_id'];
        Db_Actions::DbSelect("SELECT overlaping_with_subcategories___IDs FROM cscart_dress_type_subcategory WHERE id=$subcat_id");
        $result = Db_Actions::DbGetResults();
        //print_r($result);
        $ids = "";
        if (!isset($result->empty_result)) {
            foreach ($result as $subcat) {
                $ids = $subcat->overlaping_with_subcategories___ids;
            }
        }
        echo $ids;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Display product info
if (isset($_POST['get_prd_data'])) {
    define('AREA', 'C');
    require '../../../prepare.php';
    require '../../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../../lib/db_actions.php');
    $root_url = $config['current_location'];

    Cloth::getProductInfo($_POST['product_id']);
}
//////////////////////////////////////////
//Update product type
if (isset($_POST['upd_cloth_type'])) {
    define('AREA', 'C');
    require '../../../prepare.php';
    require '../../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../../lib/db_actions.php');
    $root_url = $config['current_location'];

    Cloth::updateProductType($_POST['product_id'], $_POST['dress_category']);
}
//////////////////////////////////////////
//Update brand type
if (isset($_POST['upd_brand_type'])) {
    define('AREA', 'C');
    require '../../../prepare.php';
    require '../../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../../lib/db_actions.php');
    $root_url = $config['current_location'];

    Cloth::updateBrandType($_POST['product_id'], $_POST['brand_tpe']);
}
///////////////////////////////////////////
//Set color variation
if (isset($_POST['var_product_id'])) {
    define('AREA', 'C');
    require '../../../prepare.php';
    require '../../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../../lib/db_actions.php');
    $root_url = $config['current_location'];
    Cloth::SetProductColorVariation();
}
////////////////////////////////////////
// Remove color variation
if (isset($_POST['remove_var'])) {
    define('AREA', 'C');
    require '../../../prepare.php';
    require '../../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../../lib/db_actions.php');
    $root_url = $config['current_location'];
    Cloth::RemoveProductColorVariation();
}
//Get sub categories
if (isset($_POST['get_sub_cats'])) {
    define('AREA', 'C');
    require '../../../prepare.php';
    require '../../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../../lib/db_actions.php');
    $root_url = $config['current_location'];

    Cloth::getDressSubacategories($_POST['parent_category_id']);
}
//Overlap
if (isset($_POST['overlap'])) {
    define('AREA', 'C');
    require '../../../prepare.php';
    require '../../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../../lib/db_actions.php');
    $root_url = $config['current_location'];

    Cloth::setCatOverlap();
}
//Get category overlap cat ids
if (isset($_POST['get_cat_overlap'])) {
    define('AREA', 'C');
    require '../../../prepare.php';
    require '../../../init.php';
    require(DIR_ROOT . '/config.php');
    require_once('../../lib/db_actions.php');
    $root_url = $config['current_location'];
    
    Cloth::getOverlapCats();
}
    