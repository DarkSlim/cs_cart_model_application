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
}
function Rectangle(x,y,w,h)
{
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
}

/*
 Ako ne razbiras kako raboti ova prasuvaj
 */
function Eventor()
{
    this.events = [];
    this.add_event = function(type, f)
    {
        this.set_events_array(type);
        this.events[type].push(f);
    }
    this.remove_event = function(type, f)
    {
    }
    this.dispatch_event = function(type, data)
    {
        this.set_events_array(type);
        for (var i = 0; i < this.events[type].length; i++)
        {
            this.events[type][i](data);
        }
    }
    this.set_events_array = function(type)
    {
        if (this.events[type] == null)
            this.events[type] = [];
    }
}

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
GlobalEventor.ON_ADD_ITEM_TO_MODEL = "ON_ADD_ITEM_TO_MODEL";
GlobalEventor.ON_REMOVE_ITEM_FROM_MODEL = "ON_REMOVE_ITEM_FROM_MODEL";

function ModelClothingPart(details_part)
{
    this.product_id = details_part.product_id;//it is coming from cs cart my sql table "cscart_products"
    //this.product_thumb_image_url, it is coming from url right parts thumbs,html src attribute
    this.product_thumb_image_url = details_part.product_thumb_image_url;
    /*product_id:$(this).attr("product_id"),
                                            product_thumb_image_url:$(this).find(".cs-main-product-image").attr("src")
                                    ModelClothingPart*/
                                    
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
}
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
    this.set_boy = function() {
    }
    this.set_girl = function() {
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
    this.add_item = function()
    {
        //ti treba dispatch event, za da mu kaze na kartickata da stavi nov object
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
}
Model.prototype = new Eventor();
Model.ON_SET_BACK = "ON_SET_MODEL_BACK";
Model.ON_SET_BACK = "ON_SET_MODEL_FRONT";

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
}

function ModelStage()
{
    this.mouse_window_position = new Point(0,0);
    this.FRAMES_PER_SECOND = 24;
    this.model = new Model();
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
        this.add_event(ModelStage.ON_ENTER_FRAME, function(data)
        {
            $("#dragable_image_temp_temp").css("left", "100px");
            $("#dragable_image_temp_temp").css("top", "100px");
        });
    }
    this.enter_frame = function()
    {
        this.dispatch_event(ModelStage.ON_ENTER_FRAME, {});
    }
    setInterval("ModelStage.MS.enter_frame();", 1000/this.FRAMES_PER_SECOND);
}
ModelStage.prototype = new Eventor();
ModelStage.MS = new ModelStage();
ModelStage.ON_ENTER_FRAME = "ON_ENTER_FRAME";
$(document).ready(function(e)
{
    $(window).mousemove(function(e)
    {
        ModelStage.MS.this.mouse_window_position = new Point(0,0);
    });
});
