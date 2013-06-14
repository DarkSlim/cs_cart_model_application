function ModelClothingPart(details_part)
{
    this.is_destroited = false;
    this.setData = function(__details_part___)
    {
        if(__details_part___ == null)
        {
            this.details = {};
            return;
        }
        this.details = __details_part___;
        this.product_id = __details_part___.product_id;//it is coming from cs cart my sql table "cscart_products"
        this.price = parseFloat(__details_part___.price);
        this.product_title = __details_part___.product_title;
        this.dress_type = __details_part___.dress_type;
        //this.product_thumb_image_url, it is coming from url right parts thumbs,html src attribute
        this.product_thumb_image_url = __details_part___.product_thumb_image_url;
    }
    this.setData( details_part );
    this.toStringClothObject = function()
    {
        var info = "ModelClothingPart(";
        for(var i in this.details)
        {
            info += i+":"+this.details[i]+" | "
        }
        info += ")";
        return info;
    }
    this.set_layer_position = function()
    {
        var layer_position = 1;
        switch(this.dress_type)
        {
            case ModelClothingPart.DRESS_TYPE_UNDERWEAR:{layer_position = 1;}break;
            case ModelClothingPart.DRESS_TYPE_HOSIERY:{layer_position = 2;}break;
            case ModelClothingPart.DRESS_TYPE_BOTTOMS:{layer_position = 3;}break;
            case ModelClothingPart.DRESS_TYPE_DRESSES:{layer_position = 4;}break;
            case ModelClothingPart.DRESS_TYPE_BELTS:{layer_position = 5;}break;
            case ModelClothingPart.DRESS_TYPE_TOPS:{layer_position = 6;}break;
            case ModelClothingPart.DRESS_TYPE_JEWELLERY:{layer_position = 7;}break;
            case ModelClothingPart.DRESS_TYPE_JACKETS:{layer_position = 8;}break;
            case ModelClothingPart.DRESS_TYPE_HATS:{layer_position = 9;}break;
            case ModelClothingPart.DRESS_TYPE_SCARVES:{layer_position = 10;}break;
            case ModelClothingPart.DRESS_TYPE_GLOVES:{layer_position = 10;}break;
            case ModelClothingPart.DRESS_TYPE_BAGS:{layer_position = 10;}break;
            case ModelClothingPart.DRESS_TYPE_EYEWEAR:{layer_position = 10;}break;
            case ModelClothingPart.DRESS_TYPE_SHOES:{layer_position = 10;}break;
        }
        this.kinetic_clot_object_front.setZIndex( layer_position );
    }
    /*product_id:$(this).attr("product_id"),
     product_thumb_image_url:$(this).find(".cs-main-product-image").attr("src")
     ModelClothingPart*/
    this.part_sprite = new ModelPart( this );
    /*this.kinetic_clot_object_front_of = null;
    this.kinetic_clot_object_front = null;
    this.kinetic_clot_object_tween = null;*/
    this.path_clout = function()
    {
        if (ModelStage.MS.model.is_front_body) {
            return "img/cloth/" + this.product_id + "_front.png";
        }
        return "img/cloth/" + this.product_id + "_back.png";
    }

    this.create_sprite_for_this_clot = function()
    {
        if (ImageModerator.loaded_images[this.path_clout()] == null)
        {
            var image = new ImageModerator({src: this.path_clout()});
            image.reference_to_model_part = this;
            image.eventor.add_event(ImageModerator.AFTER_LOAD_THE_IMAGE, function(image_moderator)
            {
                console.log("ModelClothingPart::create_sprite_for_this_clot, image is loaded.")
                image_moderator.reference_to_model_part.create_sprite_for_this_clot();
            })
            return;
        }
        if (this.kinetic_clot_object_front == null || this.is_destroited)
        {/*
            this.kinetic_clot_object_front
            this.kinetic_clot_object_front_of
            this.kinetic_clot_object_front_of.
            this.kinetic_clot_object_front_of.on("mouseup", function()
            {
                document.body.style.cursor = 'default';
                if(this.reference_clot_item.do_on_mouse_up_click)
                {
                    this.reference_clot_item.click();
                    return;
                }
                this.reference_clot_item.kinetic_clot_object_front_of.hide();
                ModelStage.MS.layer_model_selected_part.draw();
                $("#model_holder_selected_part").addClass("displayNone");
                ModelStage.MS.layer_model.draw();
                if (Math.abs(this.getX()) > 50
                        || Math.abs(this.getY()) > 50)
                {
                    console.log('this.kinetic_clot_object_front_of.on("mouseup"), it is ready for remove clode');
                    ModelStage.MS.model.remove_item(this.reference_clot_item);
                    RedoUndoModerator.RUM.add_undo_action(
                            {
                                object: ModelStage.MS.model,
                                f_string_for_object: "add_item",
                                object_for_function: this.reference_clot_item
                            });
                }
            });
            this.kinetic_clot_object_front_of.on("mousedown", function()
            {
                this.reference_clot_item.set_mouse_up_to_be_click_after_interval();
            });
            this.kinetic_clot_object_front_of.on("click", function()
            {
                //console.log("Front object CLICK event !!!");
            });*/
        }
        this.part_sprite.setup( ModelStage.MS.model.is_front_body, ImageModerator.loaded_images[this.path_clout()].image, 
                                ModelStage.MS.layer_model );
        this.part_sprite.setup_front_sprite( ModelStage.MS.layer_model_selected_part, 
                            ImageModerator.loaded_images[this.path_clout()].image );
        //this.set_layer_position();
        //this.kinetic_clot_object_tween.play();
        
        //this.kinetic_clot_object_front.reference_clot_item = this;
        //this.kinetic_clot_object_front_of.reference_clot_item = this;
        /*this.kinetic_clot_object_front.on("dragstart", function()
         {
         ModelStage.MS.layer_model.draw();
         });*/
        this.setup_front_and_back_sprite_for_draging( this.part_sprite.sprite__________front );
        this.setup_front_and_back_sprite_for_draging( this.part_sprite.sprite___________back );
        
        this.setup_front_sprite_when_over_the_part();
        //ModelStage.MS.layer_model.draw();
    }
    
    this.setup_front_and_back_sprite_for_draging = function(sprite_front_or_back)
    {
        if(sprite_front_or_back == null){return;}
        sprite_front_or_back.createImageHitRegion(function() {
            ModelStage.MS.layer_model.draw();
        });
        if(sprite_front_or_back.i_have_events == true){return;}
        sprite_front_or_back.i_have_events = true;
        sprite_front_or_back.reference_clot_item = this;
        sprite_front_or_back.on("mouseover", function()
        {
            console.log("object over");
            this.reference_clot_item.part_sprite.show_front();
        });
    }
    this.setup_front_sprite_when_over_the_part = function()
    {
        this.part_sprite.sprite_front_of_model.createImageHitRegion(function() {
            ModelStage.MS.layer_model_selected_part.draw();
        });
        if(this.part_sprite.sprite_front_of_model.i_have_events == true){return;}
        this.part_sprite.sprite_front_of_model.i_have_events = true;
        this.part_sprite.sprite_front_of_model.reference_clot_item = this;
        this.part_sprite.sprite_front_of_model.on("mouseover", function()
        {
            console.log("object front over");
            document.body.style.cursor = 'pointer';
            if(this.reference_clot_item.do_on_mouse_up_click)
            {
                return;
            }
            GlobalEventor.GE.dispatch_event(GlobalEventor.ON_MOUSE_OVER_FRONT_PART_CLOUTH, this.reference_clot_item);
            ModelStage.MS.model.blur_on();
        });
        this.part_sprite.sprite_front_of_model.on("mouseout", function()
        {
            document.body.style.cursor = 'default';
            if(this.reference_clot_item.do_on_mouse_up_click)
            {
                return;
            }
            console.log("object front out");
            /*this.reference_clot_item.part_sprite.sprite_front_of_model.hide();
            ModelStage.MS.layer_model_selected_part.draw();
            ModelStage.MS.layer_model.draw();
            $("#model_holder_selected_part").addClass("displayNone");*/
            this.reference_clot_item.part_sprite.hide_front();
            GlobalEventor.GE.dispatch_event(GlobalEventor.ON_MOUSE_OUT_FRONT_PART_CLOUTH, this.reference_clot_item);
            ModelStage.MS.model.blur_of();
        });
        this.part_sprite.sprite_front_of_model.on("mouseup", function()
        {
            document.body.style.cursor = 'default';
            if(this.reference_clot_item.do_on_mouse_up_click)
            {
                this.reference_clot_item.click();
                return;
            }
            this.hide();
            ModelStage.MS.layer_model_selected_part.draw();
            $("#model_holder_selected_part").addClass("displayNone");
            ModelStage.MS.layer_model.draw();
            if (Math.abs(this.getX()) > 50
                    || Math.abs(this.getY()) > 50)
            {
                console.log('this.kinetic_clot_object_front_of.on("mouseup"), it is ready for remove clode');
                ModelStage.MS.model.remove_item(this.reference_clot_item);
                RedoUndoModerator.RUM.add_undo_action(
                        {
                            object: ModelStage.MS.model,
                            f_string_for_object: "add_item",
                            object_for_function: this.reference_clot_item
                        });
            }
        });
        this.part_sprite.sprite_front_of_model.on("mousedown", function()
        {
            this.reference_clot_item.set_mouse_up_to_be_click_after_interval();
        });
        this.part_sprite.sprite_front_of_model.on("click", function()
        {
            //console.log("Front object CLICK event !!!");
        });
    }
    this.do_on_mouse_up_click = false;
    this.do_on_mouse_up_click_interval_timeout = -1;
    this.set_mouse_up_to_be_click_after_interval = function()
    {
        this.do_on_mouse_up_click = true;
        clearTimeout(this.do_on_mouse_up_click_interval_timeout);
        this.do_on_mouse_up_click_interval_timeout = 
                setTimeout("ModelClothingPart.ALL_PARTS['__"+this.product_id+"__'].stop_click_event();", 100);
    }
    this.stop_click_event = function()
    {
        this.do_on_mouse_up_click = false;
    }
    this.click = function()
    {
        clearTimeout(this.do_on_mouse_up_click_interval_timeout);
        console.log("Clouth part item on click.");
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_CLICKED_FRONT_PART_CLOUTH, this);
    }

    /*
     * 
     * @returns {undefined}
     * This is function for removing the elements kinetics
     */
    this.destroy = function()
    {
        console.log(this.toStringClothObject()+" destroing started.");
        /*this.kinetic_clot_object_front.remove();
        this.kinetic_clot_object_front_of.remove();*/
        /*var tween_hide_kinetic_clot_object = new Kinetic.Tween
        ({
            node: this.kinetic_clot_object_front, 
            duration: 0.5,
            opacity: 0,
            onFinish:function()
            {
                this.node.reference_clot_item.kinetic_clot_object_front.remove();
                this.node.reference_clot_item.kinetic_clot_object_front_of.remove();
                console.log(this.node.reference_clot_item.toStringClothObject()+" destroy complete.");
            }
        });*/
        //tween_hide_kinetic_clot_object.play();
        this.part_sprite.destroy();
        this.is_destroited = true;
        ModelStage.MS.layer_model.draw();
        ModelClothingPart.ALL_PARTS["__" + this.product_id + "__"] = null;
        ModelStage.MS.model.parts["__" + this.product_id + "__"] = null;
    }
    
    ModelClothingPart.ALL_PARTS["__" + this.product_id + "__"] = this;
}
ModelClothingPart.LAST_CREATE_SPRITE_OBJECT = null;
ModelClothingPart.prototype = new Eventor();
ModelClothingPart.ON_DRAG_THUMB = "ON_DRAG_THUMB";
ModelClothingPart.ON_DROP_THUMB = "ON_DRAG_THUMB";
ModelClothingPart.ALL_PARTS = [];

//Јакни
ModelClothingPart.DRESS_TYPE_JACKETS="jackets";
//Блузи
ModelClothingPart.DRESS_TYPE_TOPS="tops";
//Пантолони, кратки пантолони, Фармерки, сукњи, 
ModelClothingPart.DRESS_TYPE_BOTTOMS="bottoms";
//фустани
ModelClothingPart.DRESS_TYPE_DRESSES="dresses";
//ModelClothingPart.DRESS_TYPE_SUITS="suits";
//долна облека
ModelClothingPart.DRESS_TYPE_UNDERWEAR="underwear";
//хулахопки, трикотажа
ModelClothingPart.DRESS_TYPE_HOSIERY="hosiery";
//накит
ModelClothingPart.DRESS_TYPE_JEWELLERY="jewellery";
//капи
ModelClothingPart.DRESS_TYPE_HATS="hats";
//марами
ModelClothingPart.DRESS_TYPE_SCARVES="scarves";
//ракавици
ModelClothingPart.DRESS_TYPE_GLOVES="gloves";
//торби
ModelClothingPart.DRESS_TYPE_BAGS="bags";
//појаси
ModelClothingPart.DRESS_TYPE_BELTS="belts";
//цвикери
ModelClothingPart.DRESS_TYPE_EYEWEAR="eyewear";
//обувки
ModelClothingPart.DRESS_TYPE_SHOES="shoes";
//додатоци, миленици, маски, украси, и други додатоци.
ModelClothingPart.DRESS_TYPE_EXTRAS="extras";