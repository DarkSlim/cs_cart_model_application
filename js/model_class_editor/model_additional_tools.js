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