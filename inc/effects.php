<?php
define('AREA', 'C');
require '../prepare.php';
require '../init.php';
require(DIR_ROOT . '/config.php');
require_once('./lib/db_actions.php');
require_once("./lib/tools.php");
$root_url = $config['current_location'];
$categories = Tools::getCategories();
//print_r(Tools::getCategoryData(243));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" type="text/css" href="css/jquery.mCustomScrollbar.css" >
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/skeleton.css">
        <link rel="stylesheet" href="css/layout.css">
        <link type="text/css" href="style.css" rel="stylesheet">
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>
        <script type="text/javascript" src="js/cloth_tool.js"></script>
        <!--[if lt IE 9]>
            <script src="js/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="cs-cloth-system" class="container">
            <!-- left column -->
            <div class="cs-left-col eight columns omega">
                <div class="cs-left-border">
                    <div class="cs-gender-menu">
                        <a href="#" class="cs-men cs-active">MEN</a>
                        <a href="#" class="cs-women">WOMEN</a>
                        <a href="#" class="cs-back-link">BACK</a>
                    </div>
                    <div class="cs-model">
                        <!-- undo button -->
                        <a href="#" class="cs-turn-button">TURN</a>
                        <!-- share icons -->
                        <div class="cs-share-icons">
                            <h3>SHARE</h3>
                            <a href="#" class="cs-fb-icon"><img src="img/facebook-icon.png" width="31" height="32" alt="Facebook" /></a>
                            <a href="#" class="cs-fb-icon"><img src="img/twitter-icon.png" width="31" height="32" alt="Twitter" /></a>
                            <a href="#" class="cs-fb-icon"><img src="img/pinterest-icon.png" width="31" height="32" alt="Pinterest" /></a>
                            <a href="#" class="cs-fb-icon"><img src="img/gplus-icon.png" width="31" height="32" alt="Google plus" /></a>
                        </div>
                        <!-- shopping cart -->
                        <div class="cs-shopping-cart">
                            <div class="cs-cart-items">5 items</div>
                            <div class="cs-selected-product">
                                <a href="#" class="cs-remove-product"></a>
                                <p class="cs-prd-name">Super Skinny Super low jeans</p>
                                <p class="cs-prd-price"><strong>$20</strong></p>
                            </div>
                            <div class="cs-selected-product">
                                <a href="#" class="cs-remove-product"></a>
                                <p class="cs-prd-name">Super Skinny Super low jeans</p>
                                <p class="cs-prd-price"><strong>$20</strong></p>
                            </div>
                            <div class="cs-selected-product">
                                <a href="#" class="cs-remove-product"></a>
                                <p class="cs-prd-name">Super Skinny Super low jeans</p>
                                <p class="cs-prd-price"><strong>$20</strong></p>
                            </div>
                            <div class="cs-cart-total">TOTAL: $55.00</div>
                        </div>
                    </div>
                    <div class="cs-controls">
                        <a href="#" class="cs-new">NEW</a>
                        <a href="#" class="cs-save">SAVE</a>
                        <a href="#" class="cs-undo">UNDO</a>
                    </div>
                </div>

            </div>

            <!-- right column -->
            <div class="cs-right-col nine columns alpha">
                <div class="cs-right-border">
                    <div class="cs-cloth-opts">
                        <a href="#" class="cs-clothes cs-active">CLOTHES</a>
                        <a href="#" class="cs-backgrounds">BACKGROUND</a>
                        <a href="#" class="cs-effects">EFFECTS</a>
                    </div>
                    <div class="cs-product-container">
                        <div class="cs-product-chooser">
                            <div class="ajax-load"></div>
                            <div class="cs-product-wrap">
                                <!-- product row -->
                                <div class="cs-product-row">
                                    <!-- product -->
                                    <div class="cs-product">
                                        <img src="img/product-images/img1.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">Calvin Klien</h3>
                                        <h4 class="cs-price">From $5.95 <span class="cs-old-price">$12.95</span></h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                    <!-- product -->
                                    <div class="cs-product cs-prd-middle">
                                        <img src="img/product-images/img2.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">ZARA</h3>
                                        <h4 class="cs-price">$5.95</h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                    <!-- product -->
                                    <div class="cs-product">
                                        <img src="img/product-images/img3.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">Tommy Hilfieger</h3>
                                        <h4 class="cs-price">$4.95</h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- product row -->
                                <div class="cs-product-row">
                                    <!-- product -->
                                    <div class="cs-product">
                                        <img src="img/product-images/img4.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">Calvin Klien</h3>
                                        <h4 class="cs-price">From $5.95 <span class="cs-old-price">$12.95</span></h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                    <!-- product -->
                                    <div class="cs-product cs-prd-middle">
                                        <img src="img/product-images/img5.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">ZARA</h3>
                                        <h4 class="cs-price">$5.95</h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                    <!-- product -->
                                    <div class="cs-product">
                                        <img src="img/product-images/img6.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">Tommy Hilfieger</h3>
                                        <h4 class="cs-price">$4.95</h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- product row -->
                                <div class="cs-product-row last-cs-row">
                                    <!-- product -->
                                    <div class="cs-product">
                                        <img src="img/product-images/img7.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">Calvin Klien</h3>
                                        <h4 class="cs-price">From $5.95 <span class="cs-old-price">$12.95</span></h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                    <!-- product -->
                                    <div class="cs-product cs-prd-middle">
                                        <img src="img/product-images/img8.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">ZARA</h3>
                                        <h4 class="cs-price">$5.95</h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                    <!-- product -->
                                    <div class="cs-product">
                                        <img src="img/product-images/img9.jpg" width="97" height="126" alt="dress" class="cs-main-product-image" />
                                        <h3 class="cs-product-title">Tommy Hilfieger</h3>
                                        <h4 class="cs-price">$4.95</h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cs-pagination">
                                <a href="#" class="cs-prev"></a>&nbsp;&nbsp;
                                <span class="total-pagination">1/20</span>&nbsp;&nbsp;
                                <a href="#" class="cs-next"></a>
                            </div>
                        </div>
                        <div class="cs-cat-dropd">
                            <!-- search form -->
                            <form action="#" method="get" class="search-form">
                                <input type="text" name="product_search" id="product_search" placeholder="Search Products" />
                            </form>
                            <div class="cs-categories">
                                <?php
                                foreach ($categories as $top_cat) {
                                    ?>
                                    <div class="cs-catt">
                                        <a href="#" class="trigger-link" data-catid="<?php echo $top_cat->category_id ?>"><?php echo $top_cat->category ?></a>
                                        <ul>
                                            <?php
                                              $sub_cats = Tools::getSubCategories($top_cat->category_id);
                                              foreach($sub_cats as $scat){
                                                  ?> <li><a href="#" data-catid="<?php echo $scat->category_id ?>"><?php echo $scat->category ?></a></li><?php
                                              }
                                            ?>
                                        </ul>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>