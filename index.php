<?php
define('AREA', 'C');
require '../prepare.php';
require '../init.php';
require(DIR_ROOT . '/config.php');
require_once('./lib/db_actions.php');
require_once("./lib/tools.php");
$root_url = $config['current_location'];
if (empty($auth['user_id']) && Registry::get('settings.General.allow_anonymous_shopping') != 'Y') {
    fn_redirect("auth.login_form?return_url=" . urlencode($_SERVER['HTTP_REFERER']));
}

$root_url = $config['current_location'];
$categories = Tools::getCategories();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/jquery.ui.all.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.mCustomScrollbar.css" >
        <link rel="stylesheet" type="text/css" href="model.css" >
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/skeleton.css">
        <link rel="stylesheet" href="css/layout.css">
        <link type="text/css" href="style.css" rel="stylesheet">
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/kinetic-v4.5.3.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.core.js"></script>
        <script type="text/javascript" src="js/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
        <script type="text/javascript" src="js/jquery.ui.draggable.js"></script>
        <script type="text/javascript" src="js/jquery.ui.droppable.js"></script>
        <script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>
        <script type="text/javascript" src="js/product-data-class.js"></script>
        <script type="text/javascript" src="js/model_class_editor.js"></script>
        <!--<script type="text/javascript" src="js/modernizr.min.js"></script>
        <script type="text/javascript" src="js/pixastic.core.js"></script>
        <script type="text/javascript" src="js/pixastic.jquery.js"></script>
        <script type="text/javascript" src="js/pixastic.custom.js"></script>
        <script type="text/javascript" src="js/actions/blur.js"></script>-->
        <!--<script type="text/javascript" src="js/blur.js"></script>-->
        <!--<script type="text/javascript" src="js/blur.js"></script>-->
        <script src="js/jquery.foggy.js"></script>

        <!--
        <script type="text/javascript">
            
        </script>
        <script type="text/javascript">
            // JavaScript Document
            /*function preloadImages() {
                var cache = [];
                var args_len = arguments.length;
                for (var i = args_len; i--; ) {
                    var cacheImage = document.createElement('img');
                    cacheImage.src = arguments[i];
                    cache.push(cacheImage);
                }
            }
            preloadImages("img/models/turned/model_blured.png", "img/models/model_blured.png", "img/ajax-load2.gif", "img/expand-icon.png", "img/add_cart_btn.png");*/
        </script>
        -->
        <!--[if lt IE 9]>
            <script src="js/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <a href="#" class="go-to-cart" target="_blank">Go to cart</a>
        <div id="cs-cloth-system" class="container">
            <!-- left column -->
            <div class="cs-left-col eight columns omega">
                <div class="cs-left-border">
                    <div class="cs-gender-menu">
                        <a href="#" class="cs-men">MEN</a>
                        <a href="#" class="cs-women">WOMEN</a>
                        <a href="#" class="cs-back-link">BACK</a>
                    </div>
                    <?php require_once 'model_holder.php'; ?>
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

                            </div>

                            <div class="cs-pagination">
                                <a href="#" class="cs-prev prev-cloth"></a>&nbsp;&nbsp;
                                <span class="total-pagination">1/20</span>&nbsp;&nbsp;
                                <a href="#" class="cs-next next-cloth"></a>
                            </div>
                        </div>
                        <div class="cs-cat-dropd">
                            <!-- search form -->
                            <form action="#" method="get" class="search-form">
                                <input type="text" name="product_search" id="product_search" placeholder="Search Products" />
                            </form>
                            <div class="cs-categories">
                                <div class="">
                                    <a href="#" class="recently-viewed">Recently Used</a>
                                </div>
                                <?php
                                foreach ($categories as $top_cat) {
                                    ?>
                                    <div class="cs-catt">
                                        <a href="#" class="trigger-link" data-catid="<?php echo $top_cat->category_id ?>"><?php echo $top_cat->category ?></a>
                                        <ul>
                                            <?php
                                            $sub_cats = Tools::getSubCategories($top_cat->category_id);
                                            foreach ($sub_cats as $scat) {
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
        <div id="prd-popup" class="displayNone follower">
            <a href="#" class="quick-look">Quick Look</a>
            <p class="prd-name">Top</p>
            <p class="prd-price">$12.49</p>
            <div class="extra-info">
                <div class="sepp"></div>
                <div class="prd-color">Color: Dark Purple</div>
                <div class="sepp"></div>
                <div class="rem-item"><a href="#">Remove item</a></div>
            </div>
        </div>
        <div class="transparent-overlay"></div>
        <div class="product-popup">
            <div class="close-popup"></div>
            <div class="popup-content">
                <h3 class="prd-title">Top $12.49</h3>
                <div class="p-img">
                    <img src="img/cloth-sample-popup.jpg" >
                </div>
                <div class="mini-gallery">
                    <a href="#"><img src="img/mini-img1.jpg" ></a>
                    <a href="#"><img src="img/mini-img2.jpg" ></a>
                    <a href="#"><img src="img/mini-img3.jpg" ></a>
                </div>
                <div class="pp-inf">
                    <div class="pp-details">
                        9% spandex, 91% polyester.<br />
                        Machine wash warm.<br />
                        <a href="#" class="details-link">View Details</a>
                    </div>
                    <div class="pp-color">
                        COLOR:<br />
                        <select id="color-selector">
                            <option value="">Dark Purple</option>
                        </select>
                        <div class="sizes">Size: 4-12</div>
                    </div>
                </div>
            </div>
            <div class="black-row"><a href="#">TRY ON</a></div>
        </div>
    </body>
</html>