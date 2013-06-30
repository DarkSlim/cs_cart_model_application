function ModelClothingPart(details_part)
{
    this.is_destroited = false;
    /*
     * 
     * @type type
     * On creating new model, into model.js is adding this 
     * reference:this.model_reference
     */
    this.model_reference = null;
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
        this.category_dress_type_id = __details_part___.category_dress_type_id;
        this.subcategory_dress_type_id = __details_part___.subcategory_dress_type_id;
        this.subcategory_overlpaing_subcategories_IDs = __details_part___.subcategory_dress_type_id;
        //this.product_thumb_image_url, it is coming from url right parts thumbs,html src attribute
        this.product_thumb_image_url = __details_part___.product_thumb_image_url;
    }
    this.setData( details_part );
    
    /*
     * 
     * @returns {Array}
     * It returning array of all subcategories from 
     * attached model clothes objects into 
     * model::parts array
     * by this category id
     */
    this.array_subcategories_attached_to_model = function()
    {
        var subcategorijata_e_pod_actuelniot_del_obleka = true;
        var array_subcategories = [];
        var this_subcategoriesIDs = "" + 
                ModelStage.MS.array_layers_for_parts["__"+this.subcategory_dress_type_id+"__"].overlaping_with_subcategories___IDs;
        for(var i=0;i<this.model_reference.parts.length;i++)
        {
            var part_reference = this.model_reference.parts[i];
            if(
          //part_reference.category_dress_type_id == this.category_dress_type_id
          this_subcategoriesIDs.indexOf(part_reference.subcategory_dress_type_id+"") != -1
          &&
          array_subcategories["__"+part_reference.subcategory_dress_type_id+"__"] == null
            )
            {
                array_subcategories["__"+part_reference.subcategory_dress_type_id+"__"] = 
                        ModelStage.MS.array_layers_for_parts["__"+part_reference.subcategory_dress_type_id+"__"];
                var layer_index = array_subcategories["__"+part_reference.subcategory_dress_type_id+"__"].layer.getZIndex();
                var object_selected_object_layer = 
                        ModelStage.MS.array_layers_for_parts["__"+this.subcategory_dress_type_id+"__"];
                if(layer_index < object_selected_object_layer.layer.getZIndex())
                {
                    subcategorijata_e_pod_actuelniot_del_obleka = false;
                }
                if(array_subcategories["__"+part_reference.subcategory_dress_type_id+"__"].id == this.subcategory_dress_type_id)
                {
                }
                else
                {
                    array_subcategories.push( array_subcategories["__"+part_reference.subcategory_dress_type_id+"__"] );
                }
                array_subcategories["__"+part_reference.subcategory_dress_type_id+"__"].e_pod = subcategorijata_e_pod_actuelniot_del_obleka;
                //console.log("modelClothing, array_subcategories_attached_to_model::, layer_index:"+layer_index);
                array_subcategories["__"+part_reference.subcategory_dress_type_id+"__"].layer_index = layer_index;
            }
        }
        array_subcategories.sort(function(a, b){
                                    if(a.layer_index < b.layer_index) return -1;
                                    if(a.layer_index > b.layer_index) return 1;
                                    return 0;
                                });
        return array_subcategories;
    }
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
    this.part_sprite = new ModelPart( this );
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
                console.log("ModelClothingPart::create_sprite_for_this_clot:"+image_moderator.reference_to_model_part.toStringClothObject()+", image is loaded.")
                image_moderator.reference_to_model_part.create_sprite_for_this_clot();
            })
            return;
        }
        console.log("ModelClothingPart::create_sprite_for_this_clot:"+this.toStringClothObject()+", creating sprites started...")
        this.part_sprite.setup( ModelStage.MS.model.is_front_body, ImageModerator.loaded_images[this.path_clout()].image, 
                                ModelStage.MS["__"+this.subcategory_dress_type_id+"__"] );
        this.part_sprite.setup_front_sprite( ModelStage.MS.layer_model_selected_part, 
                            ImageModerator.loaded_images[this.path_clout()].image );
                            
        this.setup_front_and_back_sprite_for_draging( this.part_sprite.sprite__________front );
        this.setup_front_and_back_sprite_for_draging( this.part_sprite.sprite___________back );
        
        this.setup_front_sprite_when_over_the_part();
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
            console.log("sprite_front_or_back object over");
            this.reference_clot_item.part_sprite.show_front();
        });
    }
    
    this.do_actions_on__mouse_up__front_part = function()
    {
            document.body.style.cursor = 'default';
            if(this.do_on_mouse_up_click)
            {
                this.click();
                return;
            }
            this.part_sprite.sprite_front_of_model.hide();
            ModelStage.MS.layer_model_selected_part.draw();
            $("#model_holder_selected_part").addClass("displayNone");
            var difference_for_removing_part = 20;
            ModelStage.MS.layer_model.draw();
            if (Math.abs(this.part_sprite.sprite_front_of_model.getX()) > difference_for_removing_part
                    || Math.abs(this.part_sprite.sprite_front_of_model.getY()) > difference_for_removing_part)
            {
                console.log('this.kinetic_clot_object_front_of.on("mouseup"), it is ready for remove clode');
                ModelStage.MS.model.remove_item(this);
                RedoUndoModerator.RUM.add_undo_action(
                        {
                            object: ModelStage.MS.model,
                            f_string_for_object: "add_item",
                            object_for_function: this
                        });
            }
            ModelStage.MS.model.blur_of();
            GlobalEventor.GE.dispatch_event(GlobalEventor.ON_MOUSE_OUT_FRONT_PART_CLOUTH, this);
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
            console.log("sprite_front_of_model object front over");
            document.body.style.cursor = 'pointer';
            if(this.reference_clot_item.do_on_mouse_up_click)
            {
                console.log("ModelCLothingPart::setup_front_sprite_when_over_the_part, this.reference_clot_item.do_on_mouse_up_click==true");
                this.reference_clot_item.do_on_mouse_up_click = false;
                return;
            }
            GlobalEventor.GE.dispatch_event(GlobalEventor.ON_MOUSE_OVER_FRONT_PART_CLOUTH, this.reference_clot_item);
            ModelStage.MS.model.blur_on();
            console.log("sprite_front_of_model object front over end");
        });
        this.part_sprite.sprite_front_of_model.on("mouseout", function()
        {
            document.body.style.cursor = 'default';
            if(this.reference_clot_item.do_on_mouse_up_click)
            {
                return;
            }
            console.log("object front out");
            this.reference_clot_item.part_sprite.hide_front();
            GlobalEventor.GE.dispatch_event(GlobalEventor.ON_MOUSE_OUT_FRONT_PART_CLOUTH, this.reference_clot_item);
            ModelStage.MS.model.blur_of();
        });
        this.part_sprite.sprite_front_of_model.on("mouseup", function()
        {
            this.reference_clot_item.do_actions_on__mouse_up__front_part();
        });
        this.part_sprite.sprite_front_of_model.on("mousedown", function()
        {
            this.reference_clot_item.set_mouse_up_to_be_click_after_interval();
        });
        this.part_sprite.sprite_front_of_model.on("click", function()
        {
            //console.log("Front object CLICK event !!!");
            //this.reference_clot_item.click();
        });
    }
    this.do_on_mouse_up_click = false;
    this.do_on_mouse_up_click_interval_timeout = -1;
    this.set_mouse_up_to_be_click_after_interval = function()
    {
        this.do_on_mouse_up_click = true;
        clearTimeout(this.do_on_mouse_up_click_interval_timeout);
        this.do_on_mouse_up_click_interval_timeout = 
                setTimeout("ModelClothingPart.ALL_PARTS['__"+this.product_id+"__'].stop_click_event();", 300);
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
     * When popup will be closed 
     * then this should do to work again normaly everything.
     */
    this.do_actions_after_closing_popup = function()
    {
        this.do_on_mouse_up_click = false;
        console.log(this.toStringClothObject()+", after removing additional event[GlobalEventor.ON_CLICK_BUTTON_FROM_POPUPFORM_FOR_REMOVING_PART]");
        this.part_sprite.hide_front();
        ModelStage.MS.model.blur_of();
    }

    /*
     * 
     * @returns {undefined}
     * This is function for removing the elements kinetics
     */
    this.destroy = function()
    {
        console.log(this.toStringClothObject()+" destroing started.");
        
        this.remove_events_from_objects(
                [
            {object:this.part_sprite.sprite__________front, event_string:"mouseover"},
            {object:this.part_sprite.sprite___________back, event_string:"mouseover"},
            {object:this.part_sprite.sprite_front_of_model, event_string:"mouseover"},
            {object:this.part_sprite.sprite_front_of_model, event_string:"mouseout"},
            {object:this.part_sprite.sprite_front_of_model, event_string:"mouseup"},
            {object:this.part_sprite.sprite_front_of_model, event_string:"mousedown"},
            {object:this.part_sprite.sprite_front_of_model, event_string:"click"}
                ]);
        
        this.part_sprite.destroy();
        this.is_destroited = true;
        ModelStage.MS.layer_model.draw();
        ModelClothingPart.ALL_PARTS["__" + this.product_id + "__"] = null;
        ModelStage.MS.model.parts["__" + this.product_id + "__"] = null;
    }
    this.remove_events_from_objects = function(array_objects)
    {
        for(var i=0;i<array_objects.length;i++)
        {
            if(array_objects[i].object != null)
            {
                array_objects[i].object.off( array_objects[i].event_string );
            }
        }
    }
    
    ModelClothingPart.ALL_PARTS["__" + this.product_id + "__"] = this;
    
    console.log( "created object: "+this.toStringClothObject() );
}
ModelClothingPart.LAST_CREATE_SPRITE_OBJECT = null;
ModelClothingPart.prototype = new Eventor();
ModelClothingPart.ON_DRAG_THUMB = "ON_DRAG_THUMB";
ModelClothingPart.ON_DROP_THUMB = "ON_DRAG_THUMB";
ModelClothingPart.ALL_PARTS = [];

ModelClothingPart.I_AM_OVER_POPUP_FORM = false;

/*
 * Function that will close the front of part after click remove 
 * part from pop up form.
 */
GlobalEventor.GE.add_event(GlobalEventor.ON_CLICK_BUTTON_FROM_POPUPFORM_FOR_REMOVING_PART, function(part_cloth)
{
    part_cloth.do_actions_after_closing_popup();
});