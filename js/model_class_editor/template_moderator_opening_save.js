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
            object["product_title_"+i] = ModelStage.MS.model.parts[i].product_title;
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
                            price: $(partXML).find("price").text(),
                            product_title:$(partXML).find("product_title").text()
                        }));
            }
            GlobalEventor.GE.dispatch_event(GlobalEventor.ON_TEMPLATE_OPEN_COMPLETE, {});
        });
    }
}
TemplateModerator.prototype = new TemplateModerator();
TemplateModerator.AFTER_OPEN_TEMPLATE = "AFTER_OPEN_TEMPLATE";
TemplateModerator.AFTER_SAVE_TEMPLATE = "AFTER_SAVE_TEMPLATE";