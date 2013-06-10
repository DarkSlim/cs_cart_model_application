function Model()
{
    /*
     * Ke drzi site itemi za Modelot, i tie ke bidat od klasa ModelClothingPart.
     */
    this.parts = [];

    /*
     * 
     * @returns {undefined}
     * Funkcii za crtanje na modelot, znaci 
     * ako treba refresh na itemite, i toa go pravis so for loop
     */
    /*
     * 
     * @type Boolean
     * Ako model_is_front togas pravi so itemite nesto ako treba za front, 
     * a ako !model_is_front za back
     */
    this.model_is_front = true;
    this.draw = function() {
    }
    this.draw_back = function() {
    }
    this.draw_front = function() {
    }

    this.set_empty = function() {
    }
    this.set_front = function() {
    }
    this.set_back = function() {
    }

    this.path_to_body_front = function()
    {
        if (!this.is_girl)
            return "";
        return "img/models/body_"+Model.MODEL_TYPE_SELECTED+"_front.png";
    }
    this.path_to_body_back = function()
    {
        if (!this.is_girl)
            return "";
        return "img/models/body_"+Model.MODEL_TYPE_SELECTED+"_back.png";
    }
    this.path_to_body = function()
    {
        if (this.is_front_body)
            return this.path_to_body_front();
        return this.path_to_body_back();
    }
    this.is_front_body = true;
    this.is_girl = true;
    this.kinetic_body_object = null;
    this.image_front = null;
    this.image_back = null;
    this.set_boy = function() {
    }
    this.set_girl = function()
    {
        this.is_girl = true;
        this.draw_body();
    }
    this.draw_body = function()
    {
        if (ImageModerator.loaded_images[this.path_to_body()] == null)
        {
            var image_model = new ImageModerator({src: this.path_to_body()});
            image_model.eventor.add_event(ImageModerator.AFTER_LOAD_THE_IMAGE, function(data)
            {
                console.log("Model::draw_body, AFTER_LOAD_THE_IMAGE")
                ModelStage.MS.model.draw_body();
            });
            return;
        }
        if (this.kinetic_body_object != null)
        {
            this.kinetic_body_object.remove();
        }
        else
        {
            this.kinetic_body_object = new Kinetic.Image({
                image: null,
                x: 0,
                y: 0
            });
        }
        /*var image_image = new Image();
         image_image.onload = function()
         {
         console.log("==>>"+$(this).width()+", "+this.width);
         }
         image_image.src = "http://www.google.com/intl/en_ALL/images/logo.gif";*/
        console.log("draw body image url:" + ImageModerator.loaded_images[this.path_to_body()].src +
                "[w:" + ImageModerator.loaded_images[this.path_to_body()].image.width + ", h:"
                + ImageModerator.loaded_images[this.path_to_body()].image.height + "]");
        //console.log($(ImageModerator.loaded_images[this.path_to_body()].image).width());
        this.kinetic_body_object.setImage(ImageModerator.loaded_images[this.path_to_body()].image);
        //for(var i in this.kinetic_body_object){console.log(i);}
        ModelStage.MS.layer_model.add(this.kinetic_body_object);
        this.kinetic_body_object.moveToBottom();
        ModelStage.MS.layer_model.draw(  );

    }

    this.change_side = function()
    {
        this.is_front_body = !this.is_front_body;
        for (var i = 0; i < this.parts.length; i++)
        {
            this.parts[i].create_sprite_for_this_clot();
        }
        this.draw_body();
    }

    this.remove_item = function(object_part_clot)
    {
        this.remove_item___without_dispatch_event(object_part_clot);
        /*
         * Znaci, brisis od kartickata, i treba da se izbrisi 
         * i kaj modelot, pa go pravis ova.
         * A kaj modelot, prajs addEvent
         */
        this.dispatch_event(Model.ON_REMOVE_ITEM_FROM_MODEL, {});
    }
    this.remove_item___without_dispatch_event = function(object_part_clot)
    {
        this.parts.splice(this.parts.indexOf(object_part_clot), 1);
        object_part_clot.destroy();
        //this.parts
    }
    this.add_item = function(model_cloting_part_item)
    {
        if(this.parts["__"+model_cloting_part_item.product_id+"__"] != null)
        {
            this.remove_item( this.parts["__"+model_cloting_part_item.product_id+"__"] );
            return;
        }
        //ti treba dispatch event, za da mu kaze na kartickata da stavi nov object
        this.parts.push(model_cloting_part_item);
        this.parts["__"+model_cloting_part_item.product_id+"__"] = model_cloting_part_item;
        this.dispatch_event(Model.ON_ADD_ITEM_TO_MODEL,
                {
                    model_cloting_part_item: model_cloting_part_item
                });
        model_cloting_part_item.create_sprite_for_this_clot();
        /*
         * this.object = details;
         this.f_string_for_object = details.f_string_for_object;
         this.object_for_function = details.object_for_function;
         */
    }
    this.add_item___without_dispatch_event = function() {
    }

    GlobalEventor.GE.add_event(GlobalEventor.ON_REMOVE_ITEM_FROM_CART_ITEM_MODEL,
            function(data_object)
            {
                ModelStage.MS.model.remove_item___without_dispatch_event();
            });

    /*
     * 
     * @type Eventor
     * Po so modelot so itemite diktira se, 
     * za da snimis ke ti treba modelot tipot, i site itemi.
     * vo baza za modeli, snimas red so tipot na modelot, i so site AJDIJA na
     * oblekite, primer imas 5 obleki so Ajdija 1 4 55 12
     * gi snimas kako string 1,4,55,12
     */
    this.save = function()
    {
    }

    /*
     * 
     * @param {type} model_id
     * @returns {undefined}
     * Loadiras podatocite, potoa pravis niza od stringot so ajdijata na oblekite
     * i potoa gi loadiras site.
     * Posle loadiranjeto na informaciite go pokazuvas modelot
     */
    this.load_model = function(model_id)
    {
    }

    /*
     * 
     * @returns {undefined}
     * Blur effects on roll over clout part of model
     */
    this.blur_on = function()
    {
        /*for(var i=0;i<this.parts.length;i++)
        {
            this.parts[i].kinetic_clot_object_tween.play();
        }*/
        var blur_value = 2;
        $("#model_holder").css("filter", "blur("+blur_value+"px)");
        $("#model_holder").css("-webkit-filter", "blur("+blur_value+"px)");
        $("#model_holder").css("-moz-filter", "blur("+blur_value+"px)");
        $("#model_holder").css("-o-filter", "blur("+blur_value+"px)");
        $("#model_holder").css("-ms-filter", "blur("+blur_value+"px)");
        
    }
    this.blur_of = function()
    {
        var blur_value = 0;
        $("#model_holder").css("filter", "blur("+blur_value+"px)");
        $("#model_holder").css("-webkit-filter", "blur("+blur_value+"px)");
        $("#model_holder").css("-moz-filter", "blur("+blur_value+"px)");
        $("#model_holder").css("-o-filter", "blur("+blur_value+"px)");
        $("#model_holder").css("-ms-filter", "blur("+blur_value+"px)");
    }
}
Model.prototype = new Eventor();
Model.ON_SET_BACK = "ON_SET_MODEL_BACK";
Model.ON_SET_BACK = "ON_SET_MODEL_FRONT";
Model.ON_ADD_ITEM_TO_MODEL = "ON_ADD_ITEM_TO_MODEL";
Model.ON_REMOVE_ITEM_FROM_MODEL = "ON_REMOVE_ITEM_FROM_MODEL";


Model.MODEL_TYPE__BOY = "boy";
Model.MODEL_TYPE_GIRL = "girl";
//Model.MODEL_TYPE_SELECTED = Model.MODEL_TYPE_GIRL;
Model.MODEL_TYPE_SELECTED = Model.MODEL_TYPE__BOY;