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
        this.stage.add(this.layer_background);
        this.layer_model = new Kinetic.Layer();
        this.stage.add(this.layer_model);
        for(var i=0;i<this.array_layers_for_parts.length;i++)
        {
            var layer_cloth_part = 
            this["layer_cloth_part__"+this.array_layers_for_parts[i]] = new Kinetic.Layer();
            this.stage.add( layer_cloth_part );
        }
        
        
        for(var i in this.layer_model)
        {
            //console.log(i+":"+this.layer_model[i])
        }
        this.layer_model_selected_part = new Kinetic.Layer();
        //this.layer_model_objects = new Kinetic.Layer();
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
    /*
     * different type of the objects have different layer positions.
     * Because of that i am using this array.
     */
    this.array_layers_for_parts = ["underwear", "hosiery", "bottoms", "dresses", "belts",
                                "tops", "jewellery", "jackets", "hats", "scarves",
                            "gloves", "bags", "eyewear", "shoes", "extras"];
}
ModelStage.prototype = new Eventor();
ModelStage.MS = new ModelStage();
ModelStage.ON_ENTER_FRAME = "ON_ENTER_FRAME";

/*
 * 
 * 
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
 */