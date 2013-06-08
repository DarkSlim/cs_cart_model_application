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