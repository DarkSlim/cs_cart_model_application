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
                    <h2 class="titl">Upload product images</h2>
                    <form class="form-horizontal" action="save-file.php" id="upload-form" method="post" enctype="multipart/form-data" target="upload_handler">
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
                            <label class="control-label" for="product_id">Choose version</label>
                            <div class="controls">
                                <select id="color-variation" name="color_variation" class="input-xlarge">
                                    <option value="">Choose version</option>
                                    <option value="blue">Blue</option>
                                    <option value="green">Green</option>
                                    <option value="red">Red</option>
                                    <option value="white">White</option>
                                    <option value="yellow">Yellow</option>
                                    <option value="brown">Brown</option>
                                    <option value="gray">Gray</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="product_id">Product ID</label>
                            <div class="controls">
                                <select id="product_id" name="product_id" class="input-xlarge">
                                    <?php Cloth::getProductsIds(); ?>
                                </select><br />
                                <div class="prd-info">

                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="dress-type">Choose Brand</label>
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
                            <label class="control-label" for="dress-type">Choose Cloth Type</label>
                            <div class="controls">
                                <select id="dress-type" name="dress_type" class="input-xlarge">
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_JACKETS ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_JACKETS ?>"><?php echo Cloth::$DRESS_TYPE_JACKETS ?></option>
                                        <?php
                                        foreach (Tools::$SUB_JACKETS as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_TOPS ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_TOPS ?>"><?php echo Cloth::$DRESS_TYPE_TOPS ?></option>
                                        <?php
                                        foreach (Tools::$SUB_TOPS as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_BOTTOMS ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_BOTTOMS ?>"><?php echo Cloth::$DRESS_TYPE_BOTTOMS ?></option>
                                        <?php
                                        foreach (Tools::$SUB_BOTTOMS as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_DRESSES ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_DRESSES  ?>"><?php echo Cloth::$DRESS_TYPE_DRESSES ?></option>
                                        <?php
                                        foreach (Tools::$SUB_DRESSES as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_UNDERWEAR ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_UNDERWEAR  ?>"><?php echo Cloth::$DRESS_TYPE_UNDERWEAR ?></option>
                                        <?php
                                        foreach (Tools::$SUB_UNDERWEAR as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_HOSIERY ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_HOSIERY  ?>"><?php echo Cloth::$DRESS_TYPE_HOSIERY ?></option>
                                        <?php
                                        foreach (Tools::$SUB_HOSIERY as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_JEWELLERY ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_JEWELLERY  ?>"><?php echo Cloth::$DRESS_TYPE_JEWELLERY ?></option>
                                        <?php
                                        foreach (Tools::$SUB_JEWELLERY as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_HATS ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_HATS  ?>"><?php echo Cloth::$DRESS_TYPE_HATS ?></option>
                                        <?php
                                        foreach (Tools::$SUB_HATS as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_GLOVES ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_GLOVES  ?>"><?php echo Cloth::$DRESS_TYPE_GLOVES ?></option>
                                        <?php
                                        foreach (Tools::$SUB_GLOVES as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_SCARVES ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_SCARVES  ?>"><?php echo Cloth::$DRESS_TYPE_SCARVES ?></option>
                                        <?php
                                        foreach (Tools::$SUB_SCARVES as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_BAGS ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_BAGS  ?>"><?php echo Cloth::$DRESS_TYPE_BAGS ?></option>
                                        <?php
                                        foreach (Tools::$SUB_BAGS as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <option value="<?php echo Cloth::$DRESS_TYPE_BELTS ?>"><?php echo Cloth::$DRESS_TYPE_BELTS ?></option>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_EYEWEAR ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_EYEWEAR  ?>"><?php echo Cloth::$DRESS_TYPE_EYEWEAR ?></option>
                                        <?php
                                        foreach (Tools::$SUB_EYEWEAR as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="<?php echo Cloth::$DRESS_TYPE_SHOES ?>">
                                        <option value="<?php echo Cloth::$DRESS_TYPE_SHOES  ?>"><?php echo Cloth::$DRESS_TYPE_SHOES ?></option>
                                        <?php
                                        foreach (Tools::$SUB_SHOES as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
                                     <optgroup label="<?php echo Cloth::$DRESS_TYPE_EXTRAS ?>">
                                         <option value="<?php echo Cloth::$DRESS_TYPE_EXTRAS  ?>"><?php echo Cloth::$DRESS_TYPE_EXTRAS ?></option>
                                        <?php
                                        foreach (Tools::$SUB_EXTRAS as $cloth) {
                                            ?><option value="<?php echo $cloth ?>"><?php echo $cloth ?></option><?php
                                        }
                                        ?>
                                    </optgroup>
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
                                <input type="submit" id="submit-btn" class="btn" value="Upload" />
                            </div>
                            <div class="msg"></div>
                        </div>
                    </form>
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