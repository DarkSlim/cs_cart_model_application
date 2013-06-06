///////////////////////////////////////////////////////
//Products
function ProductsManager() {
    this.loadPrds = true;
    //current page number
    this.currPageNumber = 1;
    //total products
    this.getTotalPageCount = 0;
    //current category
    this.currentCat = "";
    //pagination links
    this.writePagination = function() {
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {products_count: 1},
            success: function(data) {
                $("span.total-pagination").html(ProductsManager.PM.currPageNumber + "/" + data);
                ProductsManager.PM.getTotalPageCount = data;
            }
        });
    };
    this.totalPages = -1;
    //Load products
    this.loadProducts = function() {
        $('.cs-product-wrap').html("");
        $('.ajax-load').show();
        $(this).addClass('cs-active');
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {load_products: 1, page: ProductsManager.PM.currPageNumber, cat_id: ProductsManager.PM.currentCat},
            success: function(data) {
                $('.ajax-load').hide();
                //Populate data
                $('.cs-product-wrap').html(data);
                $('.cs-product-row:last').addClass('last-cs-row');
                $("span.total-pagination").html(ProductsManager.PM.currPageNumber + "/" + ProductsManager.PM.getTotalPageCount);

                $(".cs-product").mousedown(function(e)
                {
                    ModelStage.MS.drag_clot_from_products_thumbs(
                            {
                                product_id: $(this).attr("product_id"),
                                product_thumb_image_url: $(this).find(".cs-main-product-image").attr("src")
                            });
                });
                $(".cs-product").click(function(e)
                {
                    ModelStage.MS.model.add_item(new ModelClothingPart(
                            {
                                product_id: $(this).attr("product_id"),
                                product_thumb_image_url: $(this).find(".cs-main-product-image").attr("src")
                            }));

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
//Categories 
function categoryManager() {
    //load all categories
    this.loadProductsFromCategory = function(catID) {
        ProductsManager.PM.currentCat = catID;
        ProductsManager.PM.currPageNumber = 1;
        ProductsManager.PM.loadProducts();
    }
    //get products count in category
    this.getCategoryProductCount = function(catID) {
        $.ajax({
            url: "lib/tools.php",
            type: "post",
            data: {cat_products_count: 1, catt_id: catID},
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
        $('.cs-cloth-opts a').each(function() {
            $(this).removeClass('cs-active');
        })
        $(this).addClass('cs-active');
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

                //Change bg
                $(".cs-main-bg").each(function() {
                    $(this).on('click', function() {
                        ModelStage.MS.background.change($(this).data('indexnum'));
                    })
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


ProductsManager.PM = new ProductsManager();
categoryManager.CM = new categoryManager();
BackgroundLoader.BL = new BackgroundLoader();

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
            $(this).parent().find('ul').toggle(function() {
                if (currUl.css('display') == 'block') {
                    currTriggerLink.css('background', 'url(img/cat-arrow-open.png) left 30% no-repeat');
                }
                else if (currUl.css('display') == 'none') {
                    currTriggerLink.css('background', 'url(img/cat-arrow.png) left 30% no-repeat');
                }
            });
        })
    })
    //Load category products
    $(".cs-cat-dropd a").each(function() {
        if ($(this).data('catid') != "") {
            $(this).on('click', function(event) {
                var currCatID = $(this).data('catid');
                categoryManager.CM.loadProductsFromCategory(currCatID);
                categoryManager.CM.getCategoryProductCount(currCatID);
            });
        }
    });
    //Load the backgrounds
    $(".cs-backgrounds").click(function(e) {
        ProductsManager.PM.currPageNumber = 1;
        ProductsManager.PM.loadPrds = false;
        BackgroundLoader.BL.loadBgs = true;
        BackgroundLoader.BL.loadBackgrounds();
    });
})

