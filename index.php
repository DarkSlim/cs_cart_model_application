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
if (!isset($_GET['model_type'])) {
    $_GET['model_type'] = "girl";
}
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
        <script type="text/javascript">
<?php
if (isset($_GET['model_type']) && $_GET['model_type'] == "girl") {
    $categories = Tools::getSubCategories(260);
    ?>var defaultCat = 260;<?php
}
else if (isset($_GET['model_type']) && $_GET['model_type'] == "boy") {
    $categories = Tools::getSubCategories(261);
    ?>var defaultCat = 261;<?php
}
else {
    $categories = Tools::getSubCategories(260);
    ?>var defaultCat = 260;<?php
}
?>
        var site_url = "<?php echo  $root_url; ?>";
        </script>
        <script type="text/javascript" src="js/product-data-class.js"></script>
        <script type="text/javascript" src="js/model_class_editor.js"></script>
        <script>
            Model.MODEL_TYPE_SELECTED = "<?php print $_GET["model_type"]; ?>";
            var modelSelected = "<?php print $_GET["model_type"]; ?>";
        </script>
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
                        <!-- links -->
                        <a href="index.php?model_type=boy" class="cs-men">MEN</a>
                        <a href="index.php?model_type=girl" class="cs-women">WOMEN</a>
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
                                <span class="total-pagination">-/-</span>&nbsp;&nbsp;
                                <a href="#" class="cs-next next-cloth"></a>
                            </div>
                        </div>
                        <div class="cs-cat-dropd">
                            <!-- search form -->
                            <form action="#" method="post" class="search-form">
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
                                        <a href="#" class="trigger-link" data-catid="type">Designer</a>
                                        <ul>
                                            <?php
                                            foreach (Tools::$DESIGNERS as $designer) {
                                                ?><li><a href="#" class="designer-link" data-typedesigner="<?php echo $designer ?>"><?php echo $designer ?></a></li><?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="cs-catt">
                                    <a href="#" class="trigger-link" data-catid="type">Type</a>
                                    <ul>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_JACKETS ?>"><?php echo Tools::$DRESS_TYPE_JACKETS ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_JACKETS as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_TOPS ?>"><?php echo Tools::$DRESS_TYPE_TOPS ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_TOPS as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_BOTTOMS ?>"><?php echo Tools::$DRESS_TYPE_BOTTOMS ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_BOTTOMS as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_DRESSES ?>"><?php echo Tools::$DRESS_TYPE_DRESSES ?></a>
                                             <ul>
                                                <?php
                                                foreach (Tools::$SUB_TOPS as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_UNDERWEAR ?>"><?php echo Tools::$DRESS_TYPE_UNDERWEAR ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_UNDERWEAR as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_HOSIERY ?>"><?php echo Tools::$DRESS_TYPE_HOSIERY ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_HOSIERY as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_JEWELLERY ?>"><?php echo Tools::$DRESS_TYPE_JEWELLERY ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_JEWELLERY as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_HATS ?>"><?php echo Tools::$DRESS_TYPE_HATS ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_HATS as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_SCARVES ?>"><?php echo Tools::$DRESS_TYPE_SCARVES ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_SCARVES as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_GLOVES ?>"><?php echo Tools::$DRESS_TYPE_GLOVES ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_GLOVES as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_BAGS ?>"><?php echo Tools::$DRESS_TYPE_BAGS ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_BAGS as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_BELTS ?>"><?php echo Tools::$DRESS_TYPE_BELTS ?></a>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_EYEWEAR ?>"><?php echo Tools::$DRESS_TYPE_EYEWEAR ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_EYEWEAR as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_SHOES ?>"><?php echo Tools::$DRESS_TYPE_SHOES ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_SHOES as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a href="#" data-typec="<?php echo Tools::$DRESS_TYPE_EXTRAS ?>"><?php echo Tools::$DRESS_TYPE_EXTRAS ?></a>
                                            <ul>
                                                <?php
                                                foreach (Tools::$SUB_EXTRAS as $cloth) {
                                                    ?><li><a href="#" data-typec="<?php echo $cloth ?>"><?php echo $cloth ?></a><?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="prd-popup" class="follower">
            <a href="#" class="quick-look">Quick Look</a>
            <p class="prd-name"></p>
            <!-- <p class="prd-price">$12.49</p> -->
            <div class="extra-info">
                <div class="sepp"></div>
                <div class="prd-color">Item Info</div>

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