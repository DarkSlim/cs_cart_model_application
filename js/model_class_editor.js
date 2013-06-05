/*
 * 
 * @param {type} x
 * @param {type} y
 * @returns {Point}
 * Sit objecti, oblekite, modelot, stejzot, 
 * i drugite raboti da ti imaa po eden objekt od point ili rect.
 * Za da imas informacii kade ti se.
 */
function Point(x,y)
{
    this.x = parseFloat(x);
    this.y = parseFloat(y);
    this.string_of = function(){return "{x:"+this.x+", y:"+this.y+"}";}
}
function Rectangle(x,y,w,h)
{
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    this.string_of = function(){return "{x:"+this.x+", y:"+this.y+", w:"+this.w+", h:"+this.h+"}";}
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
            if(type!= ModelStage.ON_ENTER_FRAME)
            console.log("events of type["+type+"]["+i+"]id:"+this.id);
            this.events[type][i](data);
        }
    }
    this.set_events_array = function(type)
    {
        if (this.events[type] == null)
            this.events[type] = [];
    }
    Eventor.ID ++;
}
Eventor.ID = 0;

function GlobalEventor(){}
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
GlobalEventor.ON_REMOVE_ITEM_FROM_CART_ITEM_MODEL = "ON_REMOVE_ITEM_FROM_CART_ITEM_MODEL";
GlobalEventor.ON_REMOVE_ITEM_FROM_MODEL = "ON_REMOVE_ITEM_FROM_MODEL";

function ImageModerator(image_details)
{
    this.src = image_details.src;
    this.image = new Image();
    this.image.onload = function()
    {
        console.log("Loaded image with url["+this.src+"]");
        ImageModerator.loaded_images[$(this).attr("src")].after_load();
    }
    this.image.src = this.src;
    this.after_load = function()
    {
        this.dispatch_event(ImageModerator.AFTER_LOAD_THE_IMAGE, this)
    }
    ImageModerator.loaded_images[this.src] = this;
}
ImageModerator.prototype = new Eventor();
ImageModerator.loaded_images = [];
ImageModerator.AFTER_LOAD_THE_IMAGE = "AFTER_LOAD_THE_IMAGE";

function ModelClothingPart(details_part)
{
    this.product_id = details_part.product_id;//it is coming from cs cart my sql table "cscart_products"
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
        if(ModelStage.MS.model.is_front_body){return "img/cloth/"+this.product_id+"_front.png";}
        return "img/cloth/"+this.product_id+"_back.png";
    }
                                    
    this.draw_back = function(){}
    this.draw_front = function(){}
    
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
        ModelClothingPart.LAST_CREATE_SPRITE_OBJECT = this;
        if(ImageModerator.loaded_images[this.path_clout()] == null)
        {
            var image = new ImageModerator({src:this.path_clout()});
            image.add_event(ImageModerator.AFTER_LOAD_THE_IMAGE, function(image_moderator)
            {
                console.log("ModelClothingPart::create_sprite_for_this_clot, image is loaded.")
                ModelClothingPart.LAST_CREATE_SPRITE_OBJECT.create_sprite_for_this_clot();
            })
            return;
        }
        this.kinetic_clot_object = new Kinetic.Image({
              image: ImageModerator.loaded_images[this.path_clout()].image,
              x: 0,
              y: 0,
              visible: true,
              draggable: false
            });
        this.kinetic_clot_object_front_of = new Kinetic.Image({
              image: ImageModerator.loaded_images[this.path_clout()].image,
              x: 0,
              y: 0,
              visible: true,
              draggable: true
            });
        this.kinetic_clot_object.reference_clot_item = this;
        this.kinetic_clot_object_front_of.reference_clot_item = this;
        ModelStage.MS.layer_model.add( this.kinetic_clot_object );
        ModelStage.MS.layer_model.add( this.kinetic_clot_object_front_of );
        
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
        this.kinetic_clot_object.on("mousedown", function()
        {
            console.log("object mousedown");
        });
        //for(var i in this.kinetic_clot_object_front_of){console.log(i);}
        this.kinetic_clot_object.on("mouseover", function()
        {
            console.log("object over");
            //console.log(this.reference_clot_item.product_id);
            //this.reference_clot_item.kinetic_clot_object_tween.play();
            //this.reference_clot_item.kinetic_clot_object_front_of.setDraggable("true");
            this.reference_clot_item.kinetic_clot_object_front_of.show();
            this.reference_clot_item.kinetic_clot_object_front_of.setX( 0 );
            this.reference_clot_item.kinetic_clot_object_front_of.setY( 0 );
            ModelStage.MS.layer_model.draw();
            //ModelStage.MS.model.blur_on();
            //document.body.style.cursor = 'pointer';
        });
        this.kinetic_clot_object.on("mouseout", function()
        {
            //document.body.style.cursor = 'default';
            //console.log("object out");
            //this.reference_clot_item.kinetic_clot_object_front_of.hide();
            //ModelStage.MS.layer_model.draw();
            //ModelStage.MS.model.blur_of();
            //this.reference_clot_item.kinetic_clot_object_tween.reverse();
        });
        this.kinetic_clot_object_front_of.on("mouseover", function()
        {
            console.log("object front over");
            document.body.style.cursor = 'pointer';
        });
        this.kinetic_clot_object_front_of.on("mouseout", function()
        {
            console.log("object front out");
            document.body.style.cursor = 'default';
        });
        this.kinetic_clot_object_front_of.on("mouseup", function()
        {
            document.body.style.cursor = 'default';
            this.reference_clot_item.kinetic_clot_object_front_of.hide();
            ModelStage.MS.layer_model.draw();
        });
        this.kinetic_clot_object.createImageHitRegion(function() {
          ModelStage.MS.layer_model.draw();
        });
        this.kinetic_clot_object_front_of.createImageHitRegion(function() {
          ModelStage.MS.layer_model.draw();
        });
        //ModelStage.MS.layer_model.draw();
    }
}
ModelClothingPart.LAST_CREATE_SPRITE_OBJECT = null;
ModelClothingPart.prototype = new Eventor();
ModelClothingPart.ON_DRAG_THUMB = "ON_DRAG_THUMB";
ModelClothingPart.ON_DROP_THUMB = "ON_DRAG_THUMB";

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
    this.draw = function(){}
    this.draw_back = function(){}
    this.draw_front = function(){}

    this.set_empty = function() {
    }
    this.set_front = function() {
    }
    this.set_back = function() {
    }
    
    this.path_to_body_front = function()
    {
        if(!this.is_girl)return "";
        return "img/models/body_girl_front.png";
    }
    this.path_to_body_back = function()
    {
        if(!this.is_girl)return "";
        return "img/models/body_girl_back.png";
    }
    this.path_to_body = function()
    {
        if(this.is_front_body)return this.path_to_body_front();
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
        if(ImageModerator.loaded_images[this.path_to_body()] == null)
        {
            var image_model = new ImageModerator({src:this.path_to_body()});
            image_model.add_event(ImageModerator.AFTER_LOAD_THE_IMAGE, function(data)
            {
                console.log("Model::draw_body, AFTER_LOAD_THE_IMAGE")
                ModelStage.MS.model.draw_body();
            });
            return;
        }
        if(this.kinetic_body_object != null)
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
        console.log("draw body image url:"+ImageModerator.loaded_images[this.path_to_body()].src+
            "[w:"+ImageModerator.loaded_images[this.path_to_body()].image.width+", h:"
            +ImageModerator.loaded_images[this.path_to_body()].image.height+"]");
        //console.log($(ImageModerator.loaded_images[this.path_to_body()].image).width());
        this.kinetic_body_object.setImage(ImageModerator.loaded_images[this.path_to_body()].image);
        //for(var i in this.kinetic_body_object){console.log(i);}
        ModelStage.MS.layer_model.add( this.kinetic_body_object );
        ModelStage.MS.layer_model.draw(  );
    }
    
    this.change_side = function()
    {
        this.is_front_body = !this.is_front_body;
        //this.draw_body();
    }
    
    this.remove_item = function(object_part_clot)
    {
        this.remove_item___without_dispatch_event();
        /*
         * Znaci, brisis od kartickata, i treba da se izbrisi 
         * i kaj modelot, pa go pravis ova.
         * A kaj modelot, prajs addEvent
         */
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_REMOVE_ITEM_FROM_CART_ITEM_MODEL, {});
    }
    this.remove_item___without_dispatch_event = function(object_part_clot){}
    this.add_item = function(model_cloting_part_item)
    {
        //ti treba dispatch event, za da mu kaze na kartickata da stavi nov object
        this.parts.push( model_cloting_part_item );
        this.dispatch_event(Model.ON_ADD_ITEM_TO_MODEL, 
        {
            model_cloting_part_item:model_cloting_part_item
        });
        model_cloting_part_item.create_sprite_for_this_clot();
    }
    this.add_item___without_dispatch_event = function(){}
    
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
            blurRadius: 2,          // In pixels.
            opacity: 1,           // Falls back to a filter for IE.
            cssFilterSupport: true  // Use "-webkit-filter" where available.
          });
        for(var i=0;i<this.parts.length;i++)
        {
            //this.parts[i].kinetic_clot_object_tween.play();
            //console.log(this.parts[i].kinetic_clot_object_tween);
        }
        
        
    }
    this.blur_of = function()
    {
        $("#model_holder").foggy(false);
        for(var i=0;i<this.parts.length;i++)
        {
            //this.parts[i].kinetic_clot_object_tween.reverse();
        }
    }
}
Model.prototype = new Eventor();
Model.ON_SET_BACK = "ON_SET_MODEL_BACK";
Model.ON_SET_BACK = "ON_SET_MODEL_FRONT";
Model.ON_ADD_ITEM_TO_MODEL = "ON_ADD_ITEM_TO_MODEL";

function StageBackground()
{
    this.change = function() {
    }
    this.draw = function(){}
    this.empty = function(){}
}

function CartItemModel()
{
    this.remove_part = function(object_part_clot)
    {
        /*
         * Znaci, brisis od kartickata, i treba da se izbrisi 
         * i kaj modelot, pa go pravis ova.
         * A kaj modelot, prajs addEvent
         */
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_REMOVE_ITEM_FROM_CART_ITEM_MODEL, {});
    }
    this.remove_part____without_dispatch_event = function()
    {
        /*
         * Pokazuvas totalna cena i menuvas gore levo itemot 
         * toa e.
         */
    }
    this.add_to_bagg = function()
    {   
    }
    /*GlobalEventor.GE.addEvent(GlobalEventor.ON_ADD_ITEM_TO_MODEL, function(item_added)
    {
        
    });*/
    GlobalEventor.GE.add_event(GlobalEventor.ON_REMOVE_ITEM_FROM_MODEL,
    function(item_removed)
    {
        ModelStage.MS.cart_item_model.remove_part____without_dispatch_event();
    });
    
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
    }
}

function ModelStage()
{
    this.rect_cs_model_holder = function()
    {
        return new Rectangle($("#model_holder").offset().left,$("#model_holder").offset().top,
                        $("#model_holder").width(), $("#model_holder").height());
    }
    this.position_mouse_on_window = new Point(0,0);
    this.FRAMES_PER_SECOND = 24;
    
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
        var left_position = ModelStage.MS.position_mouse_on_window.x-$("#dragable_image_temp_temp").width()/2-
                $(window).scrollLeft();
        var top_position = ModelStage.MS.position_mouse_on_window.y-$("#dragable_image_temp_temp").height()/2-
                $(window).scrollTop();
        $("#dragable_image_temp_temp").css("left", left_position+"px");
        $("#dragable_image_temp_temp").css("top", top_position+"px"); 
    }
    this.drop_thumb_draged_from_right_products = function()
    {
        $("#dragable_image_temp_temp").addClass("displayNone");
        this.remove_event(ModelStage.ON_ENTER_FRAME, this.ON_ENTER_FRAME_FOR_DRAGED_THUMB_FROM_PRODUCTS_PANEL);
        if(this.position_mouse_on_window.x < this.rect_cs_model_holder().x ||
                this.position_mouse_on_window.x > this.rect_cs_model_holder().x+this.rect_cs_model_holder().w)
        {
            console.log("ModelStage:: drop_thumb_draged_from_right_products, not succsess, position mouse x out of range.");
            return;
        }
        if(this.position_mouse_on_window.y < this.rect_cs_model_holder().y ||
                this.position_mouse_on_window.y > this.rect_cs_model_holder().y+this.rect_cs_model_holder().h)
        {
            console.log("ModelStage:: drop_thumb_draged_from_right_products, not succsess, position mouse y out of range.");
            return;
        }
        console.log("ModelStage:: drop_thumb_draged_from_right_products, mouse_position:"+this.position_mouse_on_window.string_of());
        console.log("ModelStage:: drop_thumb_draged_from_right_products, model_holder_positions:"+this.rect_cs_model_holder().string_of());
        console.log("ModelStage:: drop_thumb_draged_from_right_products, sucsess");
        this.model.add_item(this.dragged_part_from_products_thumbs);
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
    setInterval("ModelStage.MS.enter_frame();", 1000/this.FRAMES_PER_SECOND);
    
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
        this.layer_background = new Kinetic.Layer();
        this.layer_model = new Kinetic.Layer();
        //this.layer_model_objects = new Kinetic.Layer();
        
        this.stage.add( this.layer_background );
        this.stage.add( this.layer_model );
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
    }
    this.stage = null;
    this.layer_background = null;
    this.layer_model = null;
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
        ModelStage.MS.position_mouse_on_window = new Point(e.pageX,e.pageY);
        //console.log(ModelStage.MS.position_mouse_on_window.x)
    });
    $("#dragable_image_temp_temp").mouseup(function(e)
    {
        ModelStage.MS.drop_thumb_draged_from_right_products();
    });
    $("#cs-turn-model-button").click(function(e)
    {
        ModelStage.MS.layer_model.draw();
        //ModelStage.MS.model.change_side();
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
    
    
});
