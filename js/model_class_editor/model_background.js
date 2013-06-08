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