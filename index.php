<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/jquery.ui.all.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.mCustomScrollbar.css" >
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
        <script type="text/javascript" src="js/model_class_editor.js"></script>
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
                        <a href="#" class="cs-women cs-active">WOMEN</a>
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
                                <!-- product row -->
                                <div class="cs-product-row">
                                    <!-- product -->
                                    <div class="cs-product"
                                         product_id="ajdito_od_cscart_products__1">
                                        <img src="img/product-images/img1.png" width="97" height="126" alt="dress" class="cs-main-product-image" data-prdid="281"
                                             data-prdname="Calvin" data-prdprice="9.95" draggable="false" />
                                        <h3 class="cs-product-title">Calvin</h3>
                                        <h4 class="cs-price">From $5.95 <span class="cs-old-price">$12.95</span></h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                    <!-- product -->
                                    <div class="cs-product cs-prd-middle" 
                                         product_id="ajdito_od_cscart_products__2">
                                        <img src="img/product-images/img2.png" width="97" height="126" alt="dress" class="cs-main-product-image" data-prdid="104" data-prdname="ZARA" data-prdprice="5.95" />
                                        <h3 class="cs-product-title">ZARA</h3>
                                        <h4 class="cs-price">$5.95</h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                    <!-- product -->
                                    <div class="cs-product" 
                                         product_id="ajdito_od_cscart_products__3">
                                        <img src="img/product-images/img3.png" width="97" height="126" alt="dress" class="cs-main-product-image" data-prdid="103" data-prdname="Tommy Hilfieger" data-prdprice="4.95" />
                                        <h3 class="cs-product-title">Tommy Hilfieger</h3>
                                        <h4 class="cs-price">$4.95</h4>
                                        <div class="cs-variations">
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-1.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-2.jpg" width="14" height="13" /></a>
                                            <a href="#" class="cs-varr"><img src="img/product-images/variation-3.jpg" width="14" height="13" /></a>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    $(".cs-product").mousedown(function(e)
                                    {
                                        ModelStage.MS.drag_clot_from_products_thumbs(
                                                {
                                            product_id:$(this).attr("product_id"),
                                            product_thumb_image_url:$(this).find(".cs-main-product-image").attr("src")
                                                });
                                    });
                                </script>

                                <!-- product row -->
                                <div class="cs-product-row">
                                    <!-- product -->
                                    <div class="cs-product">
                                        <img src="img/product-images/img4.png" width="97" height="126" alt="dress" class="cs-main-product-image" data-prdid="97" data-prdname="Calvin Klien" data-prdprice="5.95" />
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
                                        <img src="img/product-images/img5.png" width="97" height="126" alt="dress" class="cs-main-product-image" />
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
                                        <img src="img/product-images/img6.png" width="97" height="126" alt="dress" class="cs-main-product-image" />
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
                                        <img src="img/product-images/img7.png" width="97" height="126" alt="dress" class="cs-main-product-image" />
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
                                        <img src="img/product-images/img8.png" width="97" height="126" alt="dress" class="cs-main-product-image" />
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
                                        <img src="img/product-images/img9.png" width="97" height="126" alt="dress" class="cs-main-product-image" />
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
                                <div class="cs-catt">
                                    <a href="#" class="trigger-link">Recently Used</a>
                                    <ul>
                                        <li><a href="#"></a></li>
                                    </ul>
                                </div>
                                <div class="cs-catt">
                                    <a href="#" class="trigger-link">Type</a>
                                    <ul>
                                        <li><a href="#">Show All</a></li>
                                        <li><a href="#">jackets</a></li>
                                        <li><a href="#">Tops</a></li>
                                        <li><a href="#">Bottoms</a></li>
                                        <li><a href="#">Dresses</a></li>
                                        <li><a href="#">Suits</a></li>
                                        <li><a href="#">Underwear</a></li>
                                        <li><a href="#">Hosiery</a></li>
                                        <li><a href="#">Jewellery</a></li>
                                        <li><a href="#">Hats</a></li>
                                        <li><a href="#">Scarves</a></li>
                                        <li><a href="#">Gloves</a></li>
                                        <li><a href="#">Bags</a></li>
                                        <li><a href="#">Belts</a></li>
                                        <li><a href="#">Eyewear</a></li>
                                        <li><a href="#">Shoes</a></li>
                                        <li><a href="#">Extras</a></li>
                                    </ul>
                                </div>
                                <div class="cs-catt">
                                    <a href="#" class="trigger-link">Designer</a>
                                    <ul>
                                        <li><a href="#"></a></li>
                                    </ul>
                                </div>
                                <div class="cs-catt">
                                    <a href="#" class="trigger-link">Other</a>
                                    <ul>
                                        <li><a href="#"></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>