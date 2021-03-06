var clothHelper = {
    model_type: "girl",
    currModelState: "normal",
    back_model_girl_url: "images/models/body_girl_back.png",
    front_model_girl_url: "images/models/body_girl_front.png",
    back_model_boy_url: "images/models/body_boy_back.png",
    front_model_boy_url: "images/models/body_boy_front.png",
    turnModel: function() {
        $("div.loader").show();
        if (clothHelper.currModelState == "normal") {
            var modelType = clothHelper.model_type;
            var urlToLoad = modelType == "girl" ? clothHelper.back_model_girl_url : clothHelper.back_model_boy_url;
            var new_turn_img = $('<img>');
            $(new_turn_img).load(function(event) {
                $("div.loader").hide();
                loaded1 = true;
                $('.model').css('background', 'url(' + urlToLoad + ') center center no-repeat')
            })
            new_turn_img.prop('src', urlToLoad);
            $(new_turn_img).error(function() {
                alert('Error wile loading image.');
            })
            clothHelper.currModelState = "turned"
        }
        else if (clothHelper.currModelState == "turned") {
            var modelType = clothHelper.model_type;
            var urlToLoad = modelType == "girl" ? clothHelper.front_model_girl_url : clothHelper.front_model_boy_url;
            var new_turn_img = $('<img>');
            $(new_turn_img).load(function(event) {
                $("div.loader").hide();
                loaded1 = true;
                $('.model').css('background', 'url(' + urlToLoad + ') center center no-repeat')
            })
            new_turn_img.prop('src', urlToLoad);
            $(new_turn_img).error(function() {
                alert('Error wile loading image.');
            })
            clothHelper.currModelState = "normal";
        }
    },
    clearClothes: function() {
        $('.model').html('');
    },
    ////////////////////////////////////////////////////////
    //Remove cloth by dragging
    removeClothOnDrag: function() {
        $(".draggable").draggable({
            start: function(event, ui) {
            },
            stop: function(event, ui) {
                $(event.target).remove();
            }
        });
    },
    /////////////////////////////////////////////////////////
    //Change model type
    changeModelGender: function() {
        if ($("#model-type option:selected").val() == "girl") {
            $('.model').css('background', 'url(' + clothHelper.front_model_girl_url + ') center center no-repeat')
        }
        else if ($("#model-type  option:selected").val() == "boy") {
            $('.model').css('background', 'url(' + clothHelper.front_model_boy_url + ') center center no-repeat')
        }
    },
    /////////////////////////////////////////////////////////
    //Change model position on cloth type change
    changeModelPosition: function() {
        if ($("#cloth-type").val() == "front") {
            if ($("#model-type").val() == "girl") {
                $('.model').css('background', 'url(' + clothHelper.front_model_girl_url + ') center center no-repeat')
            }
            else if ($("#model-type").val() == "boy") {
                $('.model').css('background', 'url(' + clothHelper.front_model_boy_url + ') center center no-repeat')
            }
        }
        else if ($("#cloth-type").val() == "back") {
            if ($("#model-type").val() == "girl") {
                $('.model').css('background', 'url(' + clothHelper.back_model_girl_url + ') center center no-repeat')
            }
            else if ($("#model-type").val() == "boy") {
                $('.model').css('background', 'url(' + clothHelper.back_model_boy_url + ') center center no-repeat')
            }
        }
    },
    /////////////////////////////////////////////////////////
    //Load product image from thumb
    setClothOnModelFromThumb: function() {
        var clothType = $("#cloth-type").val();
        var colorVariation = $("#color-variation").val() == "" ? "" : "_" + $("#color-variation").val();
        var product_id = $(".prd-thumb").find('img').attr('productid');
        $('.model').find('*[data-productid="' + product_id + '"]').remove();
        //var imageSRC = "http://closse/cloth_system/img/cloth/" + product_id + "_" + clothType + colorVariation + ".png?v" + (Math.random() * 9999);
        var imageSRC = "http://closse.jeniusinc.com/cloth_system/img/cloth/" + product_id + "_" + clothType + ".png?v" + (Math.random() * 9999);


        var thumbnail = $("<img>").addClass('draggable');
        thumbnail.prop({'width': '457', 'height': '576', 'src': imageSRC});
        thumbnail.attr('data-productid', product_id);
        thumbnail.on("load", function() {
            $(".loader").hide();
            $(".model").prepend(thumbnail);
            clothHelper.removeClothOnDrag();
        });
        $(thumbnail).error(function() {
            //alert('Error wile loading image. Image not found');
            $(".loader").hide();
        })
    },
    //////////////////////////////////////////////////////////
    //Change model boy or girl
    changeModelType: function() {
        $('loader').show();
        var img = $("<img>");
        if (clothHelper.model_type == "girl") {
            $(img).load(function() {
                $('loader').hide();
                $('.model').css('background', 'url(images/models/body_girl_front.png) no-repeat');
            });
            $(img).attr('src', 'images/models/body_girl_front.png');

        }
        else if (clothHelper.model_type == "boy") {
            $(img).load(function() {
                $('loader').hide();
                $('.model').css('background', 'url(images/models/body_boy_front.png) no-repeat');
            });
            $(img).attr('src', 'images/models/body_boy_front.png');

        }
        else {
            $(img).load(function() {
                $('loader').hide();
                $('.model').css('background', 'url(images/models/body_girl_front.png) no-repeat');
            });
            $(img).attr('src', 'images/models/body_girl_front.png');
        }
    },
    //////////////////////////////////////////////////////////
    //Save cloth type
    updateClothType: function() {
        $(".update-result").html('working...');
        var parentDressType = $("#dress-type option:selected").parent().attr('label');
        $.ajax({
            url: "library/cloth.php",
            type: "post",
            data: {upd_cloth_type: 1, product_id: $("#product_id").val(), dress_category: $("#dress-type").val(), dress_sub_category: $("#dress_sub_category_type").val()},
            success: function(data) {
                if (data == 1) {
                    $(".update-result").html('Product updated');
                }

            }
        });
    },
    //////////////////////////////////////////////////////////
    //Update cloth type parent
    updateClothTypeParentOnDressTypeChange: function() {
        $("#dress-type").change(function(event) {
            var parentDressType = $("#dress-type option:selected").parent().attr('label');
            $("#dress_sub_category").val(parentDressType);
        });
    },
    //////////////////////////////////////////////////////////
    //Update brand
    updateBrandType: function() {
        $(".update-result2").html('working...');
        $.ajax({
            url: "library/cloth.php",
            type: "post",
            data: {upd_brand_type: 1, product_id: $("#product_id").val(), brand_tpe: $("#brand-type").val()},
            success: function(data) {
                if (data == 1) {
                    $(".update-result2").html('Product updated');
                }

            }
        });
    },
    //////////////////////////////////////////////////////////
    //Set color variation
    setColorVariation: function() {
        $(".msg2").html('working...');
        $.ajax({
            url: "library/cloth.php",
            type: "post",
            data: {color_var: $("#color-variation").val(), var_product_id: $("#variation_product_id").val(), color_product_id: $("#color_product_id").val()},
            success: function(data) {
                if (data == 1) {
                    $(".msg2").html('Color variation set');
                }

            }
        });
    },
    //////////////////////////////////////////////////////////
    //Remove color variation
    removeColorVariation: function() {
        $(".msg3").html('working...');
        $.ajax({
            url: "library/cloth.php",
            type: "post",
            data: {color_variation_to_remove: $("#color-variation-remove").val(), remove_var_product_id: $("#variation_remove_product_id").val(), remove_var: 1},
            success: function(data) {
                if (data == 1) {
                    $(".msg3").html('Color variation removed');
                }
            }
        });
    },
    //Get subcategories
    getSubcategories: function() {
        $('#dress-type').change(function() {
            if ($(this).val() != "") {
                $.ajax({
                    url: "library/cloth.php",
                    type: "post",
                    data: {parent_category_id: $(this).val(), get_sub_cats: 1},
                    success: function(data) {
                        $("#dress_sub_category_type").html(data);
                    }
                });
            }
        })
    },
    //Get subcategories element 2
    getSubcategories2: function() {
        $('#category_type_id').change(function() {
            $("#sub_category_type_id").html('<option value="">Loading please wait...</option>');
            if ($(this).val() != "") {
                $.ajax({
                    url: "library/cloth.php",
                    type: "post",
                    data: {parent_category_id: $(this).val(), get_sub_cats: 1},
                    success: function(data) {
                        $("#sub_category_type_id").html(data);
                    }
                });
            }
        })
    },
    //Set overlaping
    setOverlapCats: function() {
        $("#set-overlap").click(function(event) {
            if ($("#sub_category_type_id").val() != "") {
                $(".overlap-info").html('Working please wait..')
                $.ajax({
                    url: "library/cloth.php",
                    type: "post",
                    data: {subcat_id: $("#sub_category_type_id").val(), overlap_ids: $("#overlap_category_type_id").val(), overlap : 1},
                    success: function(data) {
                        if(data == 1){
                            $(".overlap-info").html('Overlaping set.')
                        }
                    }
                });
            }
        });
    },
    //Get overlap cats
    getOverlapCats : function(){
        $("#sub_category_type_id").change(function(event){
            if($(this).val() != ""){
                $(".sel-sub-ct").html($("#sub_category_type_id option:selected").text());
                $("#overlap_category_type_id").val('');
                $(".catinfo").html('Loading data please wait...');
                $.ajax({
                    url: "library/cloth.php",
                    type: "post",
                    data: {subcat_id: $("#sub_category_type_id").val(), get_cat_overlap : 1},
                    success: function(data) {
                        $(".catinfo").html('');
                        if(data != -1){
                            var ids = data.split(",");
                            $("#overlap_category_type_id").val(ids);
                        }
                    }
                });
            }
        });
    }
}


$(window).load(function() {
    //turn model
    $(".turn-model-button").click(function(event) {
        clothHelper.turnModel();
    })
    //Clear model
    $(".cs-new").click(function(event) {
        clothHelper.clearClothes();
    })
    //Change model type
    $('#model-type').change(function(event) {
        clothHelper.changeModelGender();
    })
    //Change model position
    $("#cloth-type").change(function(event) {
        clothHelper.changeModelPosition(event);
    })
    //change boy model type
    $('.cs-men').click(function(event) {
        clothHelper.model_type = "boy";
        $('.cs-men').addClass('cs-active');
        $('.cs-women').removeClass('cs-active');
        clothHelper.changeModelType();
    });
    //change girl model type
    $('.cs-women').click(function(event) {
        $('.cs-men').removeClass('cs-active');
        $('.cs-women').addClass('cs-active');
        clothHelper.model_type = "girl";
        clothHelper.changeModelType();
    });
    //Update product type
    $("#update-btn").click(function(event) {
        if ($("#product_id").val() != "" && $("#dress-type").val() != "") {
            clothHelper.updateClothType();
        }
    });
    //Update brand
    $("#update-brand-btn").click(function(event) {
        if ($("#product_id").val() != "" && $("#brand-type").val() != "") {
            clothHelper.updateBrandType();
        }
    });
    //Set color variation
    $("#submit-btn2").click(function(event) {
        event.preventDefault();
        if ($("#color-variation").val() != "" && $("#variation_product_id").val() != "" && $("#color_product_id").val() != "") {
            clothHelper.setColorVariation();
        }
    })

    //Remove color variation
    $("#submit-btn3").click(function(event) {
        event.preventDefault();
        if ($("#variation_remove_product_id").val() != "" && $("#color-variation-remove").val() != "") {
            clothHelper.removeColorVariation();
        }
    })
    //Subcategories
    clothHelper.getSubcategories();
    clothHelper.getSubcategories2();
    //Overlap
    clothHelper.setOverlapCats();
    clothHelper.getOverlapCats();
})
