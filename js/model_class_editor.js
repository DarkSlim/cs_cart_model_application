function RedoUndoAction(details_object_redo_undo)
{
    this.object = details_object_redo_undo.object;
    this.f_string_for_object = details_object_redo_undo.f_string_for_object;
    this.object_for_function = details_object_redo_undo.object_for_function;
    this.do_action = function()
    {
        this.object[this.f_string_for_object](this.object_for_function);
        ModelStage.MS.layer_model.draw();
    }
}
function RedoUndoModerator()
{
    this.undo_actions = [];
    this.undo = function()
    {
        if (this.undo_actions.length == 0)
        {
            console.log("You are trying to make undo, but, there are not objects for undo.");
            return;
        }
        this.undo_actions[this.undo_actions.length - 1].do_action();
        this.undo_actions.splice(this.undo_actions.length - 1, 1);
    }
    this.add_undo_action = function(details_object_redo_undo)
    {
        this.undo_actions.push(new RedoUndoAction(details_object_redo_undo));
    }
}
RedoUndoModerator.RUM = new RedoUndoModerator();

/*
 * 
 * @param {type} x
 * @param {type} y
 * @returns {Point}
 * Sit objecti, oblekite, modelot, stejzot, 
 * i drugite raboti da ti imaa po eden objekt od point ili rect.
 * Za da imas informacii kade ti se.
 */
function Point(x, y)
{
    this.x = parseFloat(x);
    this.y = parseFloat(y);
    this.string_of = function() {
        return "{x:" + this.x + ", y:" + this.y + "}";
    }
}
function Rectangle(x, y, w, h)
{
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    this.string_of = function() {
        return "{x:" + this.x + ", y:" + this.y + ", w:" + this.w + ", h:" + this.h + "}";
    }
}

/*
 Ako ne razbiras kako raboti ova prasuvaj
 */
function Eventor()
{
    this.id = Eventor.ID;
    this.events = [];
    this.add_event = function(type, f)
    {
        this.set_events_array(type);
        this.events[type].push(f);
    }
    this.remove_event = function(type, f)
    {
        var events_for_type = [];
        events_for_type = this.events[type];
        events_for_type.splice(events_for_type.indexOf(f), 1);
    }
    this.dispatch_event = function(type, data)
    {
        this.set_events_array(type);
        /*if(type!= ModelStage.ON_ENTER_FRAME)
         {
         console.log(this.id);
         }*/
        for (var i = 0; i < this.events[type].length; i++)
        {
            if (type != ModelStage.ON_ENTER_FRAME)
                console.log("events of type[" + type + "][" + i + "]id:" + this.id);
            this.events[type][i](data);
        }
    }
    this.set_events_array = function(type)
    {
        if (this.events[type] == null)
            this.events[type] = [];
    }
    Eventor.ID++;
}
Eventor.ID = 0;

function GlobalEventor() {
}
GlobalEventor.prototype = new Eventor();
GlobalEventor.GE = new GlobalEventor();
/*
 * 
 * @type String
 * Koga ke stegnis na iksot od kartickata, treba da se 
 * aktivira ovaj eventor.
 * znaci kaj modelot koga imame remove so funkcijata remove_item
 * i kaj kartickata so funkcijata  remove_part
 */
GlobalEventor.ON_START_LOADING = "ON_START_LOADING";
GlobalEventor.ON_END_LOADING = "ON_END_LOADING";
GlobalEventor.ON_START_SAVING_TEMPLATE = "ON_START_SAVING_TEMPLATE";
GlobalEventor.ON_SAVING_TEMPLATE_COMPLETE = "ON_SAVING_TEMPLATE_COMPLETE";
GlobalEventor.ON_START_TEMPLATE_OPEN = "ON_START_TEMPLATE_OPEN";
GlobalEventor.ON_TEMPLATE_OPEN_COMPLETE = "ON_TEMPLATE_OPEN_COMPLETE";

/*
 * 
 * @type String
 * Events on mouse interaction the clouth
 */
GlobalEventor.ON_CLICKED_FRONT_PART_CLOUTH = "ON_CLICKED_FRONT_PART_CLOUTH";
GlobalEventor.ON_MOUSE_OVER_FRONT_PART_CLOUTH = "ON_MOUSE_OVER_FRONT_PART_CLOUTH";
GlobalEventor.ON_MOUSE_OUT_FRONT_PART_CLOUTH = "ON_MOUSE_OUT_FRONT_PART_CLOUTH";


function TemplateModerator()
{
    this.template_is_opened = false;
    this.opened_template_id = -1;
    this.save = function()
    {
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_START_SAVING_TEMPLATE, {});
        var object = {};
        object.action = "create_and_save_template";
        if (this.template_is_opened)
        {
            object.action = "save_template";
            object.card_designer_templates_id = this.opened_template_id;
        }
        object.total_parts = ModelStage.MS.model.parts.length;
        for (var i = 0; i < ModelStage.MS.model.parts.length; i++)
        {
            object["price_" + i] = ModelStage.MS.model.parts[i].price;
            object["product_id_" + i] = ModelStage.MS.model.parts[i].product_id;
        }
        $.post("lib/templates_moderator.php", object, function(data)
        {
            console.log("Saved template id:" + data);
            ModelStage.MS.template_moderator.template_is_opened = true;
            ModelStage.MS.template_moderator.opened_template_id = data;
            GlobalEventor.GE.dispatch_event(GlobalEventor.ON_SAVING_TEMPLATE_COMPLETE, {});
        });
    }
    this.open = function(card_designer_templates_id)
    {
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_START_TEMPLATE_OPEN, {});
        var object = {};
        object.action = "open_template";
        object.card_designer_templates_id = card_designer_templates_id;
        $.post("lib/templates_moderator.php", object, function(data)
        {
            console.log("Saved template id:" + data);
            var xml_data = $.parseXML(data);
            ModelStage.MS.template_moderator.template_is_opened = true;
            ModelStage.MS.template_moderator.opened_template_id = $(xml_data).find("template_id").text();
            for (var i = 0; i < $(xml_data).find("part").length; i++)
            {
                var partXML = $(xml_data).find("part").get(i);
                ModelStage.MS.model.add_item(new ModelClothingPart(
                        {
                            product_id: $(partXML).find("product_id").text(),
                            price: $(partXML).find("price").text()
                        }));
            }
            GlobalEventor.GE.dispatch_event(GlobalEventor.ON_TEMPLATE_OPEN_COMPLETE, {});
        });
    }
}
TemplateModerator.prototype = new TemplateModerator();
TemplateModerator.AFTER_OPEN_TEMPLATE = "AFTER_OPEN_TEMPLATE";
TemplateModerator.AFTER_SAVE_TEMPLATE = "AFTER_SAVE_TEMPLATE";

function ImageModerator(image_details)
{
    this.eventor = new Eventor();
    this.src = image_details.src;
    this.image = new Image();
    GlobalEventor.GE.dispatch_event(GlobalEventor.ON_START_LOADING, {});
    this.image.onload = function()
    {
        console.log("Loaded image with url[" + this.src + "]");
        ImageModerator.loaded_images[$(this).attr("src")].after_load();
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_END_LOADING, {});
    }
    this.image.src = this.src;
    this.after_load = function()
    {
        this.eventor.dispatch_event(ImageModerator.AFTER_LOAD_THE_IMAGE, this)
    }
    ImageModerator.loaded_images[this.src] = this;
}
ImageModerator.loaded_images = [];
ImageModerator.AFTER_LOAD_THE_IMAGE = "AFTER_LOAD_THE_IMAGE";

function ModelClothingPart(details_part)
{
    this.is_destroited = false;
    this.product_id = details_part.product_id;//it is coming from cs cart my sql table "cscart_products"
    this.price = parseFloat(details_part.price);
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
                filterRadius: 10,
                opacity:0.8*/
                
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
                $("#model_holder").addClass("blurFilter");
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
                GlobalEventor.GE.dispatch_event(GlobalEventor.ON_MOUSE_OVER_FRONT_PART_CLOUTH, this.reference_clot_item)
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
                $("#model_holder").removeClass("blurFilter");
                GlobalEventor.GE.dispatch_event(GlobalEventor.ON_MOUSE_OUT_FRONT_PART_CLOUTH, this.reference_clot_item);
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
                $("#model_holder").removeClass("blurFilter");
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
        }
        //ImageModerator.loaded_images[this.path_clout()].image
        this.kinetic_clot_object.setImage(ImageModerator.loaded_images[this.path_clout()].image);
        this.kinetic_clot_object_front_of.setImage(ImageModerator.loaded_images[this.path_clout()].image);
        this.kinetic_clot_object.reference_clot_item = this;
        this.kinetic_clot_object_front_of.reference_clot_item = this;

        /*this.kinetic_clot_object_tween = new Kinetic.Tween({
         node: this.kinetic_clot_object, 
         duration: 0.6,
         filterRadius: 5,
         easing: Kinetic.Easings.EaseInOut
         });*/
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
        return "img/models/body_girl_front.png";
    }
    this.path_to_body_back = function()
    {
        if (!this.is_girl)
            return "";
        return "img/models/body_girl_back.png";
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
        //ti treba dispatch event, za da mu kaze na kartickata da stavi nov object
        this.parts.push(model_cloting_part_item);
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
        /**/$("#model_holder").foggy({
            blurRadius: 2, // In pixels.
            opacity: 1, // Falls back to a filter for IE.
            cssFilterSupport: true  // Use "-webkit-filter" where available.
        });
        for (var i = 0; i < this.parts.length; i++)
        {
            //this.parts[i].kinetic_clot_object_tween.play();
            //console.log(this.parts[i].kinetic_clot_object_tween);
        }


    }
    this.blur_of = function()
    {
        $("#model_holder").foggy(false);
        for (var i = 0; i < this.parts.length; i++)
        {
            //this.parts[i].kinetic_clot_object_tween.reverse();
        }
    }
}
Model.prototype = new Eventor();
Model.ON_SET_BACK = "ON_SET_MODEL_BACK";
Model.ON_SET_BACK = "ON_SET_MODEL_FRONT";
Model.ON_ADD_ITEM_TO_MODEL = "ON_ADD_ITEM_TO_MODEL";
Model.ON_REMOVE_ITEM_FROM_MODEL = "ON_REMOVE_ITEM_FROM_MODEL";

function StageBackground()
{
    this.kinetic_object = null;
    this.index_background = 1;
    this.url_bg = function() {
        return "img/bgs/bg" + this.index_background + ".jpg";
    }

    this.change = function(__index_background__)
    {
        if (__index_background__ != null)
        {
            this.index_background = __index_background__;
        }
        if (this.kinetic_object == null)
        {
            this.kinetic_object = new Kinetic.Image({
                image: null,
                x: 0,
                y: 0,
                visible: true,
                draggable: false
            });
            ModelStage.MS.layer_background.add(this.kinetic_object);
        }
        if (ImageModerator.loaded_images[this.url_bg()] == null)
        {
            var image = new ImageModerator({src: this.url_bg()});
            image.reference_to_bg = this;
            image.eventor.add_event(ImageModerator.AFTER_LOAD_THE_IMAGE, function(image_moderator)
            {
                image_moderator.reference_to_bg.change(image_moderator.reference_to_bg.index_background);
            });
        }
        this.kinetic_object.setImage(ImageModerator.loaded_images[this.url_bg()].image);
        ModelStage.MS.layer_background.draw();
    }
    this.change_one_more_index_up = function()
    {
        this.index_background++;
        this.change();
    }
}
function CartItemModel()
{
    //za da izbrises item, kaj linkovite od kartickata,
    //koristis 
    //ModelStage.MS.model.remove_item(ModelClothingPart.ALL_PARTS["__ovde doaga ajdito na produktot__"]);
    //ovde doaga ajdito na produktot, ova treba da bide ajdito na produktot
    this.add_to_bagg = function()
    {
    }
    /*GlobalEventor.GE.addEvent(GlobalEventor.ON_ADD_ITEM_TO_MODEL, function(item_added)
     {
     
     });*/

    /*
     * 
     * @returns {undefined}
     * After drop item from products, it will use all items from Model.
     * Will add new line of text, will change the price.
     * ... and other changes of the cart
     */
    this.cart_refresh = function()
    {
        //ja koristis ovaa niza.Taa terba da ima ceni, ajidja i site gluposti
        //ModelStage.MS.model.parts.length
        if (ModelStage.MS.model.parts.length > 0) {
            $('.cs-shopping-cart').show();
            $('.add-to-cart-btn').show();
        }
        else {
            $('.cs-shopping-cart').hide();
            $('.add-to-cart-btn').hide();
        }
        $('.cs-shopping-cart').find('.cs-selected-product').remove();
       
        for (var i = 0; i < ModelStage.MS.model.parts.length; i++) {
            var itemInCartTemplate = $("#cartItemHolder").find('div');
            $(itemInCartTemplate).attr('productId', ModelStage.MS.model.parts[i].product_id);
            $(itemInCartTemplate).find('.cs-prd-name').html('undefined');
            $(itemInCartTemplate).find('.cs-prd-price').find('strong').html("$"+ModelStage.MS.model.parts[i].price);
            
            $(itemInCartTemplate).clone().insertAfter('.cs-cart-items');
        }
        //update amount
        ModelStage.MS.cart_item_model.updateTotalAmount();
        //Remove product from cart
         $('.cs-remove-product').click(function(event) {
                var productID = $(this).parent().attr('productId');
                
                ModelStage.MS.model.remove_item(ModelClothingPart.ALL_PARTS["__" + productID + "__"]);

            })
        var leftPos = $('.cs-shopping-cart').position().left;
        var topPos = $('.cs-shopping-cart').position().top + $('.cs-shopping-cart').outerHeight(true);
        $('div.add-to-cart-btn').css({'left': leftPos + 11 + 'px', 'top': topPos + 'px'});

    }
    //total price
    this.updateTotalAmount = function() {
        var totalAmount = 0;
        for (var i = 0; i < ModelStage.MS.model.parts.length; i++) {
            totalAmount += ModelStage.MS.model.parts[i].price;
        }
        $('.cs-cart-total').html("TOTAL: $" + totalAmount.toFixed(2));
        $('.items-count').html(ModelStage.MS.model.parts.length + " ITEMS");
    }
}

function ModelStage()
{
    this.rect_cs_model_holder = function()
    {
        return new Rectangle($("#model_holder").offset().left, $("#model_holder").offset().top,
                $("#model_holder").width(), $("#model_holder").height());
    }
    this.position_mouse_on_window = new Point(0, 0);
    this.FRAMES_PER_SECOND = 24;

    /*
     * 
     * @type TemplateModerator
     * Object that will be using for saving or opening, tempalte.
     */
    this.template_moderator = new TemplateModerator();

    /*
     * 
     * @type Model
     * Variables for the model
     */
    this.model = new Model();
    this.model.add_event(Model.ON_ADD_ITEM_TO_MODEL, function(new_item_clot)
    {
        ModelStage.MS.cart_item_model.cart_refresh(  );
    });
    this.model.add_event(Model.ON_REMOVE_ITEM_FROM_MODEL, function(new_item_clot)
    {
        ModelStage.MS.cart_item_model.cart_refresh(  );
    });

    this.background = new StageBackground();
    this.cart_item_model = new CartItemModel();

    /*
     *  On mouse down over thumbs, it is creating new 
     *  object of  ModelClothingPart and it is referencing to 
     *  this.dragged_part_from_products_thumbs
     *  after that it is creating new image,
     *  mouse centared, and it is moving with mouse.
     *  Mouse up over model holder, referenced item of ModelClothingPart 
     *  it is attaching to model and to cart
     */
    this.dragged_part_from_products_thumbs = null;

    this.undo_change = function() {
    }
    this.save_model_item = function() {
    }
    this.new_model_item = function() {
    }
    /*
     * 
     * @type type
     * On click the thumbs for clout it should add item, but they can be draged, so, after
     * mouse down, if interval spend 200 miliseconds then drag it, in antoerh case clear
     * interval and stop, and do click event
     */
    this.index_interval_after_how_much_start_drag = null;
    this.____temp___details_product_for_eding____;
    this.drag_clot_from_products_thumbs_set_temp_clout_object = function(details_product)
    {
        this.____temp___details_product_for_eding____ = details_product;
        clearTimeout(this.index_interval_after_how_much_start_drag);
        this.index_interval_after_how_much_start_drag =
                setTimeout("ModelStage.MS.drag_clot_from_products_thumbs____temp___details_product_for_eding____();", 200);
    }
    this.drag_clot_from_products_thumbs____temp___details_product_for_eding____ = function()
    {
        this.drag_clot_from_products_thumbs(this.____temp___details_product_for_eding____);
    }
    this.drag_clot_from_products_thumbs = function(details_product)
    {
        this.dragged_part_from_products_thumbs = new ModelClothingPart(details_product);
        $("#dragable_image_temp_temp").removeClass("displayNone");
        $("#dragable_image_temp_temp").attr("src", this.dragged_part_from_products_thumbs.product_thumb_image_url);
        this.set_position_drag_clot_from_products_thumbs();
        this.add_event(ModelStage.ON_ENTER_FRAME, this.ON_ENTER_FRAME_FOR_DRAGED_THUMB_FROM_PRODUCTS_PANEL);
    }
    this.set_position_drag_clot_from_products_thumbs = function()
    {
        var left_position = ModelStage.MS.position_mouse_on_window.x - $("#dragable_image_temp_temp").width() / 2 -
                $(window).scrollLeft();
        var top_position = ModelStage.MS.position_mouse_on_window.y - $("#dragable_image_temp_temp").height() / 2 -
                $(window).scrollTop();
        $("#dragable_image_temp_temp").css("left", left_position + "px");
        $("#dragable_image_temp_temp").css("top", top_position + "px");
    }
    this.drop_thumb_draged_from_right_products = function()
    {
        clearTimeout(this.index_interval_after_how_much_start_drag);
        $("#dragable_image_temp_temp").addClass("displayNone");
        this.remove_event(ModelStage.ON_ENTER_FRAME, this.ON_ENTER_FRAME_FOR_DRAGED_THUMB_FROM_PRODUCTS_PANEL);
        if (this.position_mouse_on_window.x < this.rect_cs_model_holder().x ||
                this.position_mouse_on_window.x > this.rect_cs_model_holder().x + this.rect_cs_model_holder().w)
        {
            console.log("ModelStage:: drop_thumb_draged_from_right_products, not succsess, position mouse x out of range.");
            return;
        }
        if (this.position_mouse_on_window.y < this.rect_cs_model_holder().y ||
                this.position_mouse_on_window.y > this.rect_cs_model_holder().y + this.rect_cs_model_holder().h)
        {
            console.log("ModelStage:: drop_thumb_draged_from_right_products, not succsess, position mouse y out of range.");
            return;
        }
        console.log("ModelStage:: drop_thumb_draged_from_right_products, mouse_position:" + this.position_mouse_on_window.string_of());
        console.log("ModelStage:: drop_thumb_draged_from_right_products, model_holder_positions:" + this.rect_cs_model_holder().string_of());
        console.log("ModelStage:: drop_thumb_draged_from_right_products, sucsess");
        $(".ajax-load2").show();
        this.model.add_item(this.dragged_part_from_products_thumbs);
        RedoUndoModerator.RUM.add_undo_action(
                {
                    object: this.model,
                    f_string_for_object: "remove_item",
                    object_for_function: this.dragged_part_from_products_thumbs
                });
    }
    this.ON_ENTER_FRAME_FOR_DRAGED_THUMB_FROM_PRODUCTS_PANEL = function()
    {
        ModelStage.MS.set_position_drag_clot_from_products_thumbs();
    }

    /*
     * Functions for on enter frame
     */
    this.enter_frame = function()
    {
        this.dispatch_event(ModelStage.ON_ENTER_FRAME, {});
        //console.log(this.rect_cs_model_holder().string_of());
    }
    setInterval("ModelStage.MS.enter_frame();", 1000 / this.FRAMES_PER_SECOND);

    /*
     * Stage variables.
     * this.create_the_stage will create kinetic, canvas holder
     * this.stage will pointing to object of Kinetic.Stage
     */
    this.create_the_stage = function()
    {
        this.stage = new Kinetic.Stage({
            container: "model_holder",
            width: 457,
            height: 576
        });
        this.stage_for_roll_over_parts = new Kinetic.Stage({
            container: "model_holder_selected_part",
            width: 457,
            height: 576
        }); 
        this.layer_background = new Kinetic.Layer();
        this.layer_model = new Kinetic.Layer();
        this.layer_model_selected_part = new Kinetic.Layer(); 
        //this.layer_model_objects = new Kinetic.Layer();

        this.stage.add(this.layer_background);
        this.stage.add(this.layer_model);
        this.stage_for_roll_over_parts.add(this.layer_model_selected_part);
        //this.stage.add( this.layer_model_objects );


        /*var tween = new Kinetic.Tween({
         node: this.stage, 
         duration: 0.6,
         filterRadius: 0,
         easing: Kinetic.Easings.EaseInOut
         });
         
         this.layer_model.on('mouseover', function() {
         tween.play();
         });
         
         this.layer_model.on('mouseout', function() {
         tween.reverse();
         });*/

        this.background.change();
    }
    this.stage = null;
    this.stage_for_roll_over_parts = null;
    this.layer_background = null;
    this.layer_model = null;
    this.layer_model_selected_part = null;
    //this.layer_model_objects = null;
}
ModelStage.prototype = new Eventor();
ModelStage.MS = new ModelStage();
ModelStage.ON_ENTER_FRAME = "ON_ENTER_FRAME";
$(document).ready(function(e)
{
    ModelStage.MS.create_the_stage();
    ModelStage.MS.model.set_girl();
    $(window).mousemove(function(e)
    {
        ModelStage.MS.position_mouse_on_window = new Point(e.pageX, e.pageY);
        //console.log(ModelStage.MS.position_mouse_on_window.x)
    });
    $("#dragable_image_temp_temp").mouseup(function(e)
    {
        ModelStage.MS.drop_thumb_draged_from_right_products();
    });
    $("#cs-turn-model-button").click(function(e)
    {
        ModelStage.MS.model.change_side();
    });

    $("#model_holder").mouseover(function(e)
    {
        /*$(this).foggy({
         blurRadius: 2,          // In pixels.
         opacity: 1,           // Falls back to a filter for IE.
         cssFilterSupport: true  // Use "-webkit-filter" where available.
         });*/
    });
    $("#model_holder").mouseout(function(e)
    {
        //$(this).foggy(false);
    });

    $(".cs-backgrounds").click(function(e)
    {
        //ModelStage.MS.background.change_one_more_index_up();
    });

    $(".cs-undo").click(function(e)
    {
        RedoUndoModerator.RUM.undo();
        return false;
    });

    $(".cs-save").click(function(e)
    {
        ModelStage.MS.template_moderator.save();
        return false;
    });

    console.log("search into model_class_editor.js, this[ModelStage.MS.template_moderator.open(4);].");
    console.log("it is function for opening project");
    ModelStage.MS.template_moderator.open(4);

});
