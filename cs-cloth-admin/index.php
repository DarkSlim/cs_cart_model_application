<?php
define('AREA', 'C');
//require '../../prepare.php';
//require '../../init.php';
//require(DIR_ROOT . '/config.php');
//require_once('../lib/db_actions.php');
//$root_url = $config['current_location'];
/* if (empty($auth['user_id']) && Registry::get('settings.General.allow_anonymous_shopping') != 'Y') {
  fn_redirect("auth.login_form?return_url=" . urlencode($_SERVER['HTTP_REFERER']));
  }

  $root_url = $config['current_location']; */
require("../lib/db_actions.php");
require("../lib/tools.php");
require('library/cloth.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Admin</title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="admin-style.css">
        <script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.file-input.js"></script>
        <script type="text/javascript" src="../js/jquery.ui.core.js"></script>
        <script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="../js/jquery.ui.mouse.js"></script>
        <script type="text/javascript" src="../js/jquery.ui.draggable.js"></script>
        <script type="text/javascript" src="../js/jquery.ui.droppable.js"></script>
        <script type="text/javascript" src="../js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="js/cloth-helper.js"></script>
        <!--[if lt IE 9]>
            <script src="../js/html5.js"></script>
        <![endif]-->
        <script type="text/javascript">
            function preloadImages() {
                var cache = [];
                var args_len = arguments.length;
                for (var i = args_len; i--; ) {
                    var cacheImage = document.createElement('img');
                    cacheImage.src = arguments[i];
                    cache.push(cacheImage);
                }
            }
            preloadImages("images/models/body_girl_front.png", "images/models/body_girl_back.png");
            $(document).ready(function(e) {
                //////////////////////////////////////////////////////////////////
                //Upload image
                var currProduct = null;
                $("#submit-btn").click(function(event) {
                    event.preventDefault();
                    if ($("#product_id").val() == "") {
                        alert('Please enter product ID');
                        return;
                    }
                    if ($("#productimg").val() == "") {
                        alert('Please choose image for upload');
                        return;
                    }

                    $(".loader").show();
                    $(".msg").html('Uploading image please wait...');
                    var colorVariation = $("#color-variation").val() == "" ? "" : "_" + $("#color-variation").val();
                    $('#new-name').val($("#product_id").val() + "_" + $("#cloth-type option:selected").val() + colorVariation);
                    document.getElementById("upload-form").submit();
                });
                ///////////////////////////////////////////////////////////////
                //load product info
                $("#product_id").change(function() {
                    if ($(this).val() != "") {
                        $(".prd-info").html('<img src="images/ajax-load2.gif" />');
                        $.ajax({
                            url: "library/cloth.php",
                            type: "post",
                            data: {get_prd_data: 1, product_id: $(this).val()},
                            success: function(data) {
                                $(".prd-info").html(data);
                                //Load image from thumb
                                $(".loader").show();
                                clothHelper.setClothOnModelFromThumb();
                            }
                        });
                    }
                })
                ///////////////////////////////////////////////////////////////
                //load product info for color variation
                $("#variation_product_id").change(function() {
                    if ($(this).val() != "") {
                        $(".prd-info2").html('<img src="images/ajax-load2.gif" />');
                        $.ajax({
                            url: "library/cloth.php",
                            type: "post",
                            data: {get_prd_data: 1, product_id: $(this).val()},
                            success: function(data) {
                                $(".prd-info2").html(data);
                                //Load image from thumb
                                $(".loader").show();
                                clothHelper.setClothOnModelFromThumb();
                            }
                        });
                    }
                })
                ///////////////////////////////////////////////////////////////
                //load product info for the color product
                $("#color_product_id").change(function() {
                    if ($(this).val() != "") {
                        $(".prd-info3").html('<img src="images/ajax-load2.gif" />');
                        $.ajax({
                            url: "library/cloth.php",
                            type: "post",
                            data: {get_prd_data: 1, product_id: $(this).val()},
                            success: function(data) {
                                $(".prd-info3").html(data);
                                //Load image from thumb
                                $(".loader").show();
                                clothHelper.setClothOnModelFromThumb();
                            }
                        });
                    }
                })
                ///////////////////////////////////////////////////////////////
                //load product info for the color remove product
                $("#variation_remove_product_id").change(function() {
                    if ($(this).val() != "") {
                        $(".prd-info4").html('<img src="images/ajax-load2.gif" />');
                        $.ajax({
                            url: "library/cloth.php",
                            type: "post",
                            data: {get_prd_data: 1, product_id: $(this).val()},
                            success: function(data) {
                                $(".prd-info4").html(data);
                                //Load image from thumb
                                $(".loader").show();
                                clothHelper.setClothOnModelFromThumb();
                            }
                        });
                    }
                })
            });
            function uploadFinished(data) {

                //Set image to placeholder
                if (data != "0") {
                    $('.model').find('*[data-imgurl="' + data + '"]').remove();

                    //var imageSRC = "http://closse/cloth_system/img/cloth/" + data + "?v" + (Math.random() * 9999);
                    var imageSRC = "http://closse.jeniusinc.com/cloth_system/img/cloth/" + data + "?v" + (Math.random() * 9999);
                    $(".loader").hide();
                    var thumbnail = $("<img>").addClass('draggable');
                    thumbnail.prop({'width': '457', 'height': '576', 'src': imageSRC});
                    thumbnail.attr('data-imgurl', data);
                    thumbnail.on("load", function() {
                        $(".model").prepend(thumbnail);
                        clothHelper.removeClothOnDrag();
                    });
                    $(".file-input-name").each(function() {
                        $(this).remove()
                    })
                    $(".msg").html('Image uploaded');
                    return true;
                }
                alert("Error occured while uploading");
            }
        </script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="left-col span5">
                    <ul class="nav nav-tabs">
                        <li><a href="#tab1-content" data-toggle="tab">Cloth Upload</a></li>
                        <li><a href="#tab2-content" data-toggle="tab">Color Variations</a></li>
                        <li><a href="#tab3-content" data-toggle="tab">Cloth Overlaping</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1-content">
                            <form class="form-horizontal" action="save-file.php" id="upload-form" method="post" enctype="multipart/form-data" target="upload_handler">
                                <fieldset><legend>Upload new Cloth</legend>
                                    <div class="control-group">
                                        <label class="control-label" for="model-type">Choose Model type</label>
                                        <div class="controls">
                                            <select id="model-type" name="model-type" class="input-xlarge">
                                                <option value="girl">Girl</option>
                                                <option value="boy">Boy</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="cloth-type">Choose Cloth Position</label>
                                        <div class="controls">
                                            <select id="cloth-type" name="cloth_type" class="input-xlarge">
                                                <option value="front">Front</option>
                                                <option value="back">Back</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="product_id">Product ID</label>
                                        <div class="controls">
                                            <select id="product_id" name="product_id" class="input-xlarge">
                                                <option value="">Select product</option>
                                                <?php Cloth::getProductsIds(); ?>
                                            </select><br />
                                            <div class="prd-info">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="brand-type">Choose Brand</label>
                                        <div class="controls">
                                            <select id="brand-type" name="brand_type" class="input-xlarge">
                                                <?php
                                                foreach (Cloth::$DESIGNERS as $designer) {
                                                    ?><option value="<?php echo $designer ?>"><?php echo $designer ?><?php
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                        <input type="button" id="update-brand-btn" class="btn" value="Update Brand" /><span class="update-result2"></span>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="dress-type">Choose Dress Category</label>
                                        <div class="controls">
                                            <select id="dress-type" name="category_dress_type_id" class="input-xlarge">
                                                <?php Cloth::getDressCategories() ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="dress_sub_category_type">Choose Dress Sub Category</label>
                                        <div class="controls">
                                            <select id="dress_sub_category_type" name="dress_sub_category_type" class="input-xlarge">
                                                <option value="">Choose category first</option>
                                            </select>
                                        </div>
                                        <input type="button" id="update-btn" class="btn" value="Update Cloth Type" /><span class="update-result"></span>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="inputUsername">Choose Image</label>
                                        <div class="controls">
                                            <input type="file" name="productimg" id="productimg" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="hidden" name="new_name" id="new-name" value="">
                                            <input type="hidden" name="dress_type_parent" id="dress_type_parent" value="">
                                            <input type="submit" id="submit-btn" class="btn" value="Upload" />
                                        </div>
                                        <div class="msg"></div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab2-content">
                            <!-- Color variation options -->
                            <form class="form-horizontal" action="#" method="post">

                                <fieldset><legend>Choose color variation for product</legend>
                                    <div class="control-group">
                                        <label class="control-label" for="color-variation">Choose color version</label>
                                        <div class="controls">
                                            <select id="color-variation" name="color_variation" class="input-xlarge">
                                                <option value="">Choose color</option>
                                                <option value="blue">Blue</option>
                                                <option value="green">Green</option>
                                                <option value="red">Red</option>
                                                <option value="white">White</option>
                                                <option value="black">Black</option>
                                                <option value="yellow">Yellow</option>
                                                <option value="brown">Brown</option>
                                                <option value="gray">Gray</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="variation_product_id">Select product for which color variation will be set.</label>
                                        <div class="controls">
                                            <select id="variation_product_id" name="variation_product_id" class="input-xlarge">
                                                <option value="">Select product</option>
                                                <?php Cloth::getProductsIds(); ?>
                                            </select><br />
                                            <div class="prd-info2">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="color_product_id">Set the color product .</label>
                                        <div class="controls">
                                            <select id="color_product_id" name="color_product_id" class="input-xlarge">
                                                <option value="">Select product</option>
                                                <?php Cloth::getProductsIds(); ?>
                                            </select><br />
                                            <div class="prd-info3">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="submit" id="submit-btn2" class="btn" value="Set Color Variation" />
                                        </div>
                                        <div class="msg2"></div>
                                    </div>
                                </fieldset>
                                <!-- Remove color variations  -->
                                <fieldset><legend>Remove color variation for product</legend>
                                    <div class="control-group">
                                        <label class="control-label" for="variation_remove_product_id">Select product to remove color variations.</label>
                                        <div class="controls">
                                            <select id="variation_remove_product_id" name="variation_remove_product_id" class="input-xlarge">
                                                <option value="">Select product</option>
                                                <?php Cloth::getProductsIds(); ?>
                                            </select><br />
                                            <div class="prd-info4">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="color-variation">Choose color to remove</label>
                                        <div class="controls">
                                            <select id="color-variation-remove" name="color-variation-remove" class="input-xlarge">
                                                <option value="">Choose color</option>
                                                <option value="blue">Blue</option>
                                                <option value="green">Green</option>
                                                <option value="red">Red</option>
                                                <option value="white">White</option>
                                                <option value="black">Black</option>
                                                <option value="yellow">Yellow</option>
                                                <option value="brown">Brown</option>
                                                <option value="gray">Gray</option>
                                                <option value="all">Remove All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="submit" id="submit-btn3" class="btn" value="Remove Color Variation" />
                                        </div>
                                        <div class="msg3"></div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>

                        <div class="tab-pane" id="tab3-content">
                            <!-- Color variation options -->
                            <form class="form-horizontal" action="#" method="post">

                                <fieldset><legend>Cloth overlapping</legend>
                                    <div class="control-group">
                                        <label class="control-label" for="category_type_id">Choose Dress Category</label>
                                        <div class="controls">
                                            <select id="category_type_id" name="category_type_id" class="input-xlarge">
                                                <option value="">Choose Category</option>
                                                <?php Cloth::getDressCategories() ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="sub_category_type_id">Choose Dress Sub Category</label>
                                        <div class="controls">
                                            <select id="sub_category_type_id" name="sub_category_type_id" class="input-xlarge">
                                                <option value="">Choose category first</option>
                                            </select>
                                            <div class="catinfo"></div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="overlap_category_type_id">Choose overlapping categories</label>
                                        <div class="controls">
                                            <select id="overlap_category_type_id" name="overlap_category_type_id" class="input-xlarge" multiple="" size="20">
                                                <?php Cloth::getAllCats() ?>
                                            </select>
                                        </div>
                                        <input type="button" id="set-overlap" class="btn" value="Set overlaping" /><span class="update-result"></span>
                                    </div>
                                </fieldset>
                                <div class="overlap-info"></div>
                            </form>
                        </div>
                    </div>



                    <iframe id="upload_handler" name="upload_handler" src="#" width="0" height="0" frameborder="0"></iframe>
                </div>
                <div class="right-col span6">
                    <div class="turn-model-button"></div>
                    <div class="clear-cloth"></div>
                    <div class="cs-gender-menu">
                        <a href="#" class="cs-men">MEN</a>
                        <a href="#" class="cs-women cs-active">WOMEN</a>
                    </div>
                    <div class="loader"></div>
                    <div class="model"></div>
                    <div class="cs-controls">
                        <a href="#" class="cs-new">CLEAR</a>
                    </div>
                </div> 
            </div>
        </div>
    </body>
</html>