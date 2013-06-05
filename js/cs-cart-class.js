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
    this.x = x;
    this.y = y;
}
function Rectangle(x, y, w, h)
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

/*
 * 
 * @returns {undefined}Moze vaka? ??
 */
function EventTester()
{
    this.nekoja_funkcija = function()//eve ja be
    {
        //e sega, primer, na klik kopce pravis nekoja_funkcija(), taka? ?????
        //i sakas na toa nekoja druga funkcija nekade nesto da napravi taka?ok
        //togas pravis dispatch event
        //mozes i objektot da go pratis.Jasno e? do nekade.
        //sega ke dodam event na objektot.prvo ke kreiram ok?ok
        this.dispatch_event(EventTester.ON_NEKOJA_FUNKCIJA_NAPRAVI_ALERT, this);

    }
}

function GlobalEventor()
{
    /*
     * 
     * @type EventorZnaci global event, nema funkcii nisto nema.
     * Se koristi za globalni raboti ok?ok
     * primer ON_ADD_ITEM_TO_MODEL.
     * Koga ke stavis irtemce obleka, kade pravis proimerni? 
     * realno kazi mi.kade se menuva nesto? na kuklata se menuva nesto? da, na kartickata? Riknia ?Kuklata, kartickata, pozadinata, proizvodite, kategoriite
     * znaci koga ke dodajs eden item, na 5 mesta se pravi nesto taka? da :)
     * znaci, dispatch pravis na edno mesto so objektot od gloabl event.
     * i dodavas 5 eventi na site mesta :).sekoj funkcija od tie eventi si pravi nesto za sebe.
     * Svakas sega?
     * abe svakam ova e super rabota samo moramd a ja probam za podobro da razberam.
     * Kade planiras finalno da se napravi add item? kako mislis kade?.? posle drag i click na thumbot
     * tala?ta kdaa? da.znaci dispatch treba da napravis ednas
     * vo funkcija add item.ajde napravi go
     */
}
GlobalEventor.prototype = new Eventor();
GlobalEventor.GE = new GlobalEventor();
//ovaj e GlobalEventor.GE??da be abatko jasno e.GlobalEventor.GE =  new uporewbi foupotrebi go
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

function ModelClothingPart()
{
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
    this.random_broj = Math.random() * 100000;
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
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_REMOVE_ITEM_FROM_MODEL, {});
    }
    this.remove_item___without_dispatch_event = function(object_part_clot) {
    }
    this.add_item = function()
    {
        //ti treba dispatch event, za da mu kaze na kartickata da stavi nov object
        //ne batko :)imas objekt globalen taka? e.iskoristi go nego
        //koristi go objektot od gloablniot event batko pa ne bese ovaj 
        GlobalEventor.GE.dispatch_event(GlobalEventor.ON_ADD_ITEM_TO_MODEL, new ModelClothingPart());
    }
    this.add_item___without_dispatch_event = function() {
    }

    GlobalEventor.GE.add_event(GlobalEventor.ON_REMOVE_ITEM_FROM_CART_ITEM_MODEL,
            function(data_object)
            {
                ModelStage.MS.model.remove_item___without_dispatch_event();
            });

    GlobalEventor.GE.add_event(GlobalEventor.ON_ADD_ITEM_TO_MODEL, function(item_added)
    {

        //alert("Ove e listwner od modelot" + item_added.random_broj);
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
    this.draw = function(bgUrl, xpos, ypos) {
       
    }
    this.empty = function() {
    }
}

function CartItemModel()
{

    this.product_ID = null;
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
    GlobalEventor.GE.add_event(GlobalEventor.ON_ADD_ITEM_TO_MODEL, function(item_added)
    {

        //alert("Ove e listwner od kartckata za dodavanje item model" + item_added.random_broj);
    });
    GlobalEventor.GE.add_event(GlobalEventor.ON_REMOVE_ITEM_FROM_MODEL,
            function(item_removed)
            {
                ModelStage.MS.cart_item_model.remove_part____without_dispatch_event();
            });

    CartItemModel.ALL_ITEMS["__" + this.product_ID + "__"] = this;
}
CartItemModel.ALL_ITEMS = [];

function ModelStage()
{
    this.model = new Model();
    this.background = new StageBackground();
    this.cart_item_model = new CartItemModel();

    this.undo_change = function() {
    }
    this.save_model_item = function() {
    }
    this.new_model_item = function() {
    }
}