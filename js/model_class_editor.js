var array_scripts = ["model_redo_undo", "model_additional_tools", 
    "template_moderator_opening_save", "model_clothing_part","model",
            "model_background","model_cart_moderator","model_stage"];
for(var i=0;i<array_scripts.length;i++)
{
    document.write('<script src="js/model_class_editor/'+array_scripts[i]+'.js"></script>');
}


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
    //ModelStage.MS.template_moderator.open(4);
    //$("#model_holder").css("-webkit-filter", "blur(5px)");
    //$("#model_holder").css("display", "none");

});
