function ModelClothingPart(details_part)
{
    this.is_destroited = false;
    this.product_id = details_part.product_id;//it is coming from cs cart my sql table "cscart_products"
    this.price = parseFloat(details_part.price);
    this.product_title = details_part.product_title;
    //this.product_thumb_image_url, it is coming from url right parts thumbs,html src attribute
    this.product_thumb_image_url = details_part.product_thumb_image_url;
    /*product_id:$(this).attr("product_id"),
     product_thumb_image_url:$(this).find(".cs-main-product-image").attr("src")
     ModelClothingPart*/
    this.kinetic_clot_object_front_of = null;
    this.kinetic_clot_object = null;
    this.kinetic_clot_object_tween = null
    this.path_clout = function()
    {
        if (ModelStage.MS.model.is_front_body) {
            return "img/cloth/" + this.product_id + "_front.png";
        }
        return "img/cloth/" + this.product_id + "_back.png";
    }

    this.draw_back = function() {
    }
    this.draw_front = function() {
    }

    /*
     * 
     * @returns {undefined}
     * Funkcija za dragiranje na oblekata od modelot
     */
    this.drag = function()
    {
        //this.dispatch_event(ModelClothingPart.ON_DRAG_THUMB, {draged_object:this});
    }
    this.drop = function()
    {
        //slicno i ovde treba da napravis dispatc event
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
        if (this.kinetic_clot_object == null || this.is_destroited)
        {
            this.kinetic_clot_object = new Kinetic.Image({
                image: null,
                x: 0,
                y: 0,
                visible: true,
                draggable: false/*,
                filter: Kinetic.Filters.Blur,
                filterRadius: 5*/
            });
            
            this.kinetic_clot_object_front_of = new Kinetic.Image({
                image: null,
                x: 0,
                y: 0,
                visible: false,
                draggable: true
            });

            //for(var i in this.kinetic_clot_object_front_of){console.log(i);}
            this.kinetic_clot_object.on("mouseover", function()
            {
                console.log("object over");
                $("#model_holder_selected_part").removeClass("displayNone");
                this.reference_clot_item.kinetic_clot_object_front_of.show();
                this.reference_clot_item.kinetic_clot_object_front_of.setX(0);
                this.reference_clot_item.kinetic_clot_object_front_of.setY(0);
                this.reference_clot_item.kinetic_clot_object_front_of.moveToTop();
                ModelStage.MS.layer_model_selected_part.draw();
            });
            this.kinetic_clot_object_front_of.on("mouseover", function()
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
            this.kinetic_clot_object_front_of.on("mouseout", function()
            {
                document.body.style.cursor = 'default';
                if(this.reference_clot_item.do_on_mouse_up_click)
                {
                    return;
                }
                console.log("object front out");
                this.reference_clot_item.kinetic_clot_object_front_of.hide();
                ModelStage.MS.layer_model_selected_part.draw();
                ModelStage.MS.layer_model.draw();
                $("#model_holder_selected_part").addClass("displayNone");
                GlobalEventor.GE.dispatch_event(GlobalEventor.ON_MOUSE_OUT_FRONT_PART_CLOUTH, this.reference_clot_item);
                ModelStage.MS.model.blur_of();
            });
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
            });
            ModelStage.MS.layer_model.add(this.kinetic_clot_object);
            ModelStage.MS.layer_model_selected_part.add(this.kinetic_clot_object_front_of);
            
            

            this.kinetic_clot_object_tween = new Kinetic.Tween({
             node: this.kinetic_clot_object, 
             duration: 0.6,
             filterRadius: 5,
             easing: Kinetic.Easings.EaseInOut
             });
        }
        //ImageModerator.loaded_images[this.path_clout()].image
        this.kinetic_clot_object.setImage(ImageModerator.loaded_images[this.path_clout()].image);
        this.kinetic_clot_object_front_of.setImage(ImageModerator.loaded_images[this.path_clout()].image);
        this.kinetic_clot_object.reference_clot_item = this;
        this.kinetic_clot_object_front_of.reference_clot_item = this;
        /*this.kinetic_clot_object.on("dragstart", function()
         {
         ModelStage.MS.layer_model.draw();
         });*/
        this.kinetic_clot_object.createImageHitRegion(function() {
            ModelStage.MS.layer_model.draw();
        });
        this.kinetic_clot_object_front_of.createImageHitRegion(function() {
            ModelStage.MS.layer_model.draw();
        });
        //ModelStage.MS.layer_model.draw();
    }

    /*
     * 
     * @returns {undefined}
     * This is function for removing the elements kinetics
     */
    this.destroy = function()
    {
        this.kinetic_clot_object.remove();
        this.kinetic_clot_object_front_of.remove();
        this.is_destroited = true;
        ModelClothingPart.ALL_PARTS["__" + this.product_id + "__"] = null;
        ModelStage.MS.model.parts["__" + this.product_id + "__"] = null;
        ModelStage.MS.layer_model.draw();
    }
    this.do_on_mouse_up_click = false;
    this.do_on_mouse_up_click_interval_timeout = -1;
    this.set_mouse_up_to_be_click_after_interval = function()
    {
        this.do_on_mouse_up_click = true;
        clearTimeout(this.do_on_mouse_up_click_interval_timeout);
        this.do_on_mouse_up_click_interval_timeout = setTimeout("ModelClothingPart.ALL_PARTS['__"+this.product_id+"__'].stop_click_event();", 100);
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
    
    ModelClothingPart.ALL_PARTS["__" + this.product_id + "__"] = this;
}
ModelClothingPart.LAST_CREATE_SPRITE_OBJECT = null;
ModelClothingPart.prototype = new Eventor();
ModelClothingPart.ON_DRAG_THUMB = "ON_DRAG_THUMB";
ModelClothingPart.ON_DROP_THUMB = "ON_DRAG_THUMB";
ModelClothingPart.ALL_PARTS = [];