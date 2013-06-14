///////////////////////////////////////////////////////
//Products
function ProductsManager() {
    this.loadPrds = true;
    //current page number
    this.currPageNumber = 1;
    //total products
    this.getTotalPageCount = 0;
    //current category
    this.currentCat = defaultCat;
    //pagination links
    // Current product type
    this.currentProductType = null;
    this.writePagination = function() {
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {products_count: 1, model_type : modelSelected},
            success: function(data) {
                $("span.total-pagination").html(ProductsManager.PM.currPageNumber + "/" + data);
                ProductsManager.PM.getTotalPageCount = data;
            }
        });
    };
    this.totalPages = -1;
    //Load products
    this.loadProducts = function(event) {
        $('.cs-product-wrap').html("");
        $('.cs-cloth-opts a').each(function() {
            $(this).removeClass('cs-active');
        })
        $('.ajax-load').show();
        $(".cs-clothes").addClass('cs-active');
        ProductsManager.PM.currentCat = defaultCat;
        var currProduct_type = (typeof event != "undefined") ? $(event.target).data('typec')  : "";
        ProductsManager.PM.currentProductType = currProduct_type;
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {load_products: 1, page: ProductsManager.PM.currPageNumber, cat_id: ProductsManager.PM.currentCat, product_type : ProductsManager.PM.currentProductType, model_type : modelSelected},
            success: function(data) {
                $('.ajax-load').hide();
                //Populate data
                $('.cs-product-wrap').html(data);
                $('.cs-product-row:last').addClass('last-cs-row');
                $("span.total-pagination").html(ProductsManager.PM.currPageNumber + "/" + ProductsManager.PM.getTotalPageCount);

                $(".cs-product").mousedown(function(e)
                {
                    ModelStage.MS.drag_clot_from_products_thumbs_set_temp_clout_object(
                            {
                                product_id: $(this).attr("product_id"),
                                price: $(this).attr("product_price"),
                                product_thumb_image_url: $(this).find(".cs-main-product-image").attr("src"),
                                product_title: $(this).find(".cs-main-product-image").attr("product_title"),
                                dress_type: $(this).find(".cs-main-product-image").attr("dress_type")
                            });
                    /*ModelStage.MS.drag_clot_from_products_thumbs(
                     {
                     product_id: $(this).attr("product_id"),
                     product_thumb_image_url: $(this).find(".cs-main-product-image").attr("src")
                     });*/
                });
                $(".cs-product").click(function(e)
                {
                    var model_part = new ModelClothingPart(
                            {
                                product_id: $(this).attr("product_id"),
                                price: $(this).attr("product_price"),
                                product_thumb_image_url: $(this).find(".cs-main-product-image").attr("src"),
                                product_title: $(this).find(".cs-main-product-image").attr("product_title"),
                                dress_type: $(this).find(".cs-main-product-image").attr("dress_type")
                            });
                    ModelStage.MS.model.add_item(model_part);
                    RedoUndoModerator.RUM.add_undo_action(
                            {
                                object: ModelStage.MS.model,
                                f_string_for_object: "remove_item",
                                object_for_function: model_part
                            });
                    clearTimeout(ModelStage.MS.index_interval_after_how_much_start_drag);

                });
            }
        });
    }
    //Load next page
    this.loadNextPage = function() {
        if (ProductsManager.PM.currPageNumber < ProductsManager.PM.getTotalPageCount) {
            ProductsManager.PM.currPageNumber += 1;
            //load products
            if (ProductsManager.PM.loadPrds == true) {
                ProductsManager.PM.loadProducts();
            }
            //load backgrounds
            else if (BackgroundLoader.BL.loadBgs == true) {
                BackgroundLoader.BL.loadBackgrounds()
            }
        }

    }
    //Load prev page
    this.loadPrevPage = function() {
        if (ProductsManager.PM.currPageNumber > 1) {
            ProductsManager.PM.currPageNumber -= 1;
            //load products
            if (ProductsManager.PM.loadPrds == true) {
                ProductsManager.PM.loadProducts();
            }
            //load backgrounds
            else if (BackgroundLoader.BL.loadBgs == true) {
                BackgroundLoader.BL.loadBackgrounds()
            }

        }
    }
    //reset data
    this.resetData = function() {
        ProductsManager.PM.currPageNumber = 1;
        ProductsManager.PM.currentCat = "";
        ProductsManager.PM.writePagination();
    }
}
///////////////////////////////////////////////////
//Recently used products
function recentlyUsedProducts() {
    this.loadRecentProducts = function() {
        $('.cs-product-wrap').html("");
        $('.cs-cloth-opts a').each(function() {
            $(this).removeClass('cs-active');
        })
        $('.ajax-load').show();
        $(".cs-clothes").addClass('cs-active');
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {load_recent_products: 1, page: 1},
            success: function(data) {
                $('.ajax-load').hide();
                //Populate data
                $('.cs-product-wrap').html(data);
                $('.cs-product-row:last').addClass('last-cs-row');
                $("span.total-pagination").html("1/1");

                $(".cs-product").mousedown(function(e)
                {
                    ModelStage.MS.drag_clot_from_products_thumbs_set_temp_clout_object(
                            {
                                product_id: $(this).attr("product_id"),
                                price: $(this).attr("product_price"),
                                product_thumb_image_url: $(this).find(".cs-main-product-image").attr("src"),
                                product_title: $(this).find(".cs-main-product-image").attr("product_title"),
                                dress_type: $(this).find(".cs-main-product-image").attr("dress_type")
                            });
                    /*ModelStage.MS.drag_clot_from_products_thumbs(
                     {
                     product_id: $(this).attr("product_id"),
                     product_thumb_image_url: $(this).find(".cs-main-product-image").attr("src")
                     });*/
                });
                $(".cs-product").click(function(e)
                {
                    $(".ajax-load2").show();
                    var model_part = new ModelClothingPart(
                            {
                                product_id: $(this).attr("product_id"),
                                price: $(this).attr("product_price"),
                                product_thumb_image_url: $(this).find(".cs-main-product-image").attr("src")
                            });
                    ModelStage.MS.model.add_item(model_part);
                    RedoUndoModerator.RUM.add_undo_action(
                            {
                                object: ModelStage.MS.model,
                                f_string_for_object: "remove_item",
                                object_for_function: model_part
                            });
                    clearTimeout(ModelStage.MS.index_interval_after_how_much_start_drag);

                });
            }
        });
    }
}

///////////////////////////////////////////////////
//Categories 
function categoryManager() {
    //load all categories
    this.loadProductsFromCategory = function(catID, event) {
        ProductsManager.PM.currentCat = catID;
        ProductsManager.PM.currPageNumber = 1;
        ProductsManager.PM.loadProducts(event);
    }
    //get products count in category
    this.getCategoryProductCount = function(catID) {
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {cat_products_count: 1, catt_id: catID, model_type : modelSelected, product_type : ProductsManager.PM.currentProductType},
            success: function(data) {
                $("span.total-pagination").html(ProductsManager.PM.currPageNumber + "/" + data);
                ProductsManager.PM.getTotalPageCount = data;
            }
        });
    }
}
////////////////////////////////////////////////////
//Backgrounds
function BackgroundLoader() {
    this.loadBgs = false;
    this.totalBgPageCount = 1;
    //Load the backgrounds
    this.loadBackgrounds = function() {

        $('.cs-product-wrap').html("");
        $('.ajax-load').show();
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {load_bgs: 1, curr_page: ProductsManager.PM.currPageNumber},
            success: function(data) {
                $('.ajax-load').hide();
                //Populate data
                $('.cs-product-wrap').html(data);
                $('.cs-product-row:last').addClass('last-cs-row');
                ProductsManager.PM.getTotalPageCount = BackgroundLoader.BL.totalBgPageCount;
                $("span.total-pagination").html(ProductsManager.PM.currPageNumber + "/" + ProductsManager.PM.getTotalPageCount);
                //ebati kodot glup, probaj sega
                $(".thumb_za_pozadini").click(function(e)
                {
                    ModelStage.MS.background.change($(this).attr("index_za_pozadina_e"));
                });
            }
        });
    }
    //Get total number of background pges
    this.getBgPageCount = function() {
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {getBgNumPages: 1},
            success: function(data) {
                BackgroundLoader.BL.totalBgPageCount = data;
            }
        });
    }
}
////////////////////////////////////////////////////////////////
//Men Model
function BodyModel() {
    this.switchMenModel = function() {
        $('.cs-gender-menu a').each(function() {
            $(this).removeClass('cs-active');
        })
        $(".cs-men").addClass('cs-active');
        var currLocation = window.location.href;
        var queryString = currLocation.lastIndexOf('?');
        if (queryString > -1) {
            currLocation = currLocation.substr(0, queryString);
            //window.location = currLocation + "?model_type=boy";
        }
        else {
            //window.location = currLocation + "?model_type=boy";
        }
    }
    this.switchWomenModel = function() {
        $('.cs-gender-menu a').each(function() {
            $(this).removeClass('cs-active');
        })
        $(".cs-women").addClass('cs-active');
        var currLocation = window.location.href;
        var queryString = currLocation.lastIndexOf('?');
        if (queryString > -1) {
            currLocation = currLocation.substr(0, queryString);
            //window.location = currLocation + "?model_type=girl";
        }
        else {
            //window.location = currLocation + "?model_type=girl";
        }
    }
    this.getParameterByName = function(name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    this.switchModelmage = function() {

    }
}
///////////////////////////////////////////////////////////////
//popup windows
function ProductPopups() {
    //Show popup with product name and price
    this.showPopup = function(event) {
        $("#prd-popup").fadeIn(100);
        $(".follower").css({'left': ModelStage.MS.position_mouse_on_window.x + 16, 'top': ModelStage.MS.position_mouse_on_window.y - 15})
    }
    this.followPopup = function() {
        $(".follower").css({'left': ModelStage.MS.position_mouse_on_window.x + 16, 'top': ModelStage.MS.position_mouse_on_window.y - 15})
    }
    this.showOverlay = function() {
        $("div.transparent-overlay").css('height', $(window).height()).show();
        $('div.product-popup').show();
    }
    this.hideOverlay = function() {
        $("div.transparent-overlay").hide();
        $('div.product-popup').hide();
    }
}


function CartHelper() {
    this.colapseItems = function() {
        $('.cs-shopping-cart').css('height', 'auto');
        var currCartHeight = $('.cs-shopping-cart').outerHeight();
        var originalheight = 0;
        $('.colapse-control').on('click', function(event) {
            originalheight = 0;
            $('.cs-shopping-cart div').each(function() {
                originalheight += $(this).outerHeight(true);
            });
            if (currCartHeight > 35) {
                $('.colapse-control').css('background', 'url(img/expand-icon.png) left center no-repeat');
                $('.cs-shopping-cart').stop().animate({
                    height: 35
                }, 200, function() {
                    currCartHeight = $('.cs-shopping-cart').outerHeight();

                });
            }
            else if (currCartHeight <= 35) {
                $('.colapse-control').css('background', 'url(img/colapse-icon.png) left center no-repeat');
                $('.cs-shopping-cart').stop().animate({
                    height: originalheight
                }, 200, function() {
                    currCartHeight = $('.cs-shopping-cart').outerHeight();

                });
            }
        })
    }

    this.positionAddTocartButton = function() {
        var leftPos = $('.cs-shopping-cart').position().left;
        var topPos = $('.cs-shopping-cart').position().top + $('.cs-shopping-cart').outerHeight(true);
        $('div.add-to-cart-btn').css({'left': leftPos + 11 + 'px', 'top': topPos + 'px'});
    }

    this.NewCart = function() {

        /*for (var i = 0; i < ModelStage.MS.model.parts.length; i++) {

            ModelStage.MS.model.remove_item(ModelClothingPart.ALL_PARTS["__" + ModelStage.MS.model.parts[i].product_id + "__"]);
            ModelStage.MS.cart_item_model.cart_refresh();
        }
        $(".cs-shopping-cart .cs-selected-product").each(function() {
            $(this).remove();
        })
        
        ModelStage.MS.model.parts.length = 0;
        ModelClothingPart.ALL_PARTS.length = 0;
        ModelStage.MS.cart_item_model.updateTotalAmount();*/
        window.location.reload();
        

    }
}


ProductsManager.PM = new ProductsManager();
categoryManager.CM = new categoryManager();
BackgroundLoader.BL = new BackgroundLoader();
CartHelper.CH = new CartHelper();
BodyModel.MM = new BodyModel();
recentlyUsedProducts.RUP = new recentlyUsedProducts();
ProductPopups.PP = new ProductPopups();

$(window).load(function() {
//Get total products count
    ProductsManager.PM.writePagination();
    //Get total bg pages count
    BackgroundLoader.BL.getBgPageCount();
    //Load products
    ProductsManager.PM.loadProducts();
    $(".cs-clothes").click(function(event) {
        ProductsManager.PM.loadPrds = true;
        BackgroundLoader.BL.loadBgs = false;
        ProductsManager.PM.resetData();
        ProductsManager.PM.loadProducts();
    });
    //Next page
    $(".cs-next").on('click', function() {
        ProductsManager.PM.loadNextPage();
    })
    //Prev page
    $(".cs-prev").on('click', function() {
        ProductsManager.PM.loadPrevPage();
    })

    //categories
    $(".cs-categories").mCustomScrollbar({
        scrollButtons: {
            enable: false,
            scrollInertia: 300
        },
        advanced: {
            updateOnContentResize: true
        }
    });
    //Categories menu
    $('.trigger-link').each(function() {
        $(this).on('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            var currUl = $(this).parent().find('ul');
            var currTriggerLink = $(this);
            if ($(this).parent().find('ul').find('li').length) {
                $(this).parent().find('ul').toggle(function() {
                    if (currUl.css('display') == 'block') {
                        currTriggerLink.css('background', 'url(img/cat-arrow-open.png) left 30% no-repeat');
                    }
                    else if (currUl.css('display') == 'none') {
                        currTriggerLink.css('background', 'url(img/cat-arrow.png) left 30% no-repeat');
                    }
                });
            }

        })
    })
    //Load category products
    $(".cs-cat-dropd a:not(.recently-viewed, .trigger-link)").each(function() {
        if ($(this).data('catid') != "") {
            $(this).on('click', function(event) {
                var currCatID = $(this).data('catid');
                categoryManager.CM.loadProductsFromCategory(currCatID, event);
                categoryManager.CM.getCategoryProductCount(currCatID);
            });
        }
    });
    //Load the backgrounds
    $(".cs-backgrounds").click(function(e) {
        e.preventDefault();
        $('.cs-cloth-opts a').each(function() {
            $(this).removeClass('cs-active');
        })
        $(".cs-backgrounds").addClass('cs-active');
        ProductsManager.PM.currPageNumber = 1;
        ProductsManager.PM.loadPrds = false;
        BackgroundLoader.BL.loadBgs = true;
        BackgroundLoader.BL.loadBackgrounds();
    });
    //Load the effects
    $(".cs-effects").click(function(e) {
        e.preventDefault();
        $('.cs-cloth-opts a').each(function() {
            $(this).removeClass('cs-active');
        })
        $(".cs-effects").addClass('cs-active');
        ProductsManager.PM.currPageNumber = 1;
        ProductsManager.PM.loadPrds = false;
        BackgroundLoader.BL.loadBgs = true;
        BackgroundLoader.BL.loadBackgrounds();
    });
    CartHelper.CH.colapseItems();
    ModelStage.MS.model.add_event(Model.ON_REMOVE_ITEM_FROM_MODEL, function(new_item_clot)
    {
        CartHelper.CH.colapseItems();
    });
    //Men model
    $('.cs-men').click(function(e) {
        BodyModel.MM.switchMenModel();
    })
    //Women model
    $('.cs-women').click(function(e) {
        BodyModel.MM.switchWomenModel();
    })
    //Clear cart
    $('.cs-new').click(function(e) {
        e.preventDefault();
        CartHelper.CH.NewCart();
    })
    //Recent products
    $('.recently-viewed').click(function(e) {
        recentlyUsedProducts.RUP.loadRecentProducts();
    })

    ModelStage.MS.model.add_event(Model.ON_ADD_ITEM_TO_MODEL, function(item_added)
    {
        CartHelper.CH.colapseItems();
    });
    GlobalEventor.GE.add_event(GlobalEventor.ON_MOUSE_OVER_FRONT_PART_CLOUTH,
            function(data) {
                $("#prd-popup").find('.prd-name').html(data.product_title);
                $("#prd-popup").find('.prd-price').html('$' + data.price);
                $("#prd-popup .rem-item a:first").attr('product_id', data.product_id);
                $("#prd-popup").stop().fadeIn();
                $("#prd-popup").css({'left': ModelStage.MS.position_mouse_on_window.x, 'top': ModelStage.MS.position_mouse_on_window.y})


            });
    GlobalEventor.GE.add_event(GlobalEventor.ON_MOUSE_OUT_FRONT_PART_CLOUTH,
            function(data) {
                $("#prd-popup").hide();
                $('div.extra-info').slideUp('fast');
                //$('a.quick-look').hide();
                $("#prd-popup").addClass('follower');
            });
    GlobalEventor.GE.add_event(GlobalEventor.ON_CLICKED_FRONT_PART_CLOUTH,
            function(data) {
                $("#prd-popup").removeClass('follower');
                //$('a.quick-look').show();
                $('div.extra-info').slideDown('fast');
            });


    GlobalEventor.GE.add_event(GlobalEventor.ON_START_LOADING,
            function() {
                $(".ajax-load2").show();
            });
    GlobalEventor.GE.add_event(GlobalEventor.ON_END_LOADING,
            function() {
                $(".ajax-load2").hide();
            });
    ModelStage.MS.add_event(ModelStage.ON_ENTER_FRAME, ProductPopups.PP.followPopup)

    $("a.quick-look").click(function(event) {
        event.preventDefault();
        $('div.extra-info').hide();
        $("#prd-popup").hide();
        $('a.quick-look').hide();
        $("#prd-popup").addClass('follower');
        var bg = $("<img>");
        $(bg).load(function() {
            ProductPopups.PP.showOverlay();
        })
        $(bg).attr('src', 'img/transprent-bg.png');
    })
    $("div.close-popup").click(function(event) {
        ProductPopups.PP.hideOverlay();
    })
    $("#prd-popup").mouseout(function() {
        /*$('div.extra-info').slideUp('fast');
         $("#prd-popup").hide();
         $('a.quick-look').hide();
         $("#prd-popup").addClass('follower');*/
    })

    //Model type
    if (BodyModel.MM.getParameterByName('model_type') == 'boy') {
        $('.cs-men').addClass('cs-active');
        $('.cs-women').removeClass('cs-active');
    }
    else if (BodyModel.MM.getParameterByName('model_type') == 'girl') {
        $('.cs-men').removeClass('cs-active');
        $('.cs-women').addClass('cs-active');
    }
    else {
        $('.cs-women').addClass('cs-active');
    }
    //Remove item on popup button click
    $('.rem-item a').click(function(event){
        event.preventDefault();
        $('div.extra-info').hide();
        $("#prd-popup").hide();
        $('a.quick-look').hide();
        $("#prd-popup").addClass('follower');
        var productID = $(this).attr('product_id');
       
       var object_part_cloth_for_removing = ModelClothingPart.ALL_PARTS["__" + productID + "__"];
        ModelStage.MS.model.remove_item( object_part_cloth_for_removing );
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_CLICK_BUTTON_FROM_POPUPFORM_FOR_REMOVING_PART, object_part_cloth_for_removing);
    })
})