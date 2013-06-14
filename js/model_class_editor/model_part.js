function ModelPart()
{
    this.sprite__________front = null;
    this.sprite___________back = null;
    this.sprite_front_of_model = null;
    this.isForFront = true;
    this.z__index = 0;
    
    this.setup = function(isForFront, image, layer_holder)
    {
        this.isForFront = isForFront;
        var actual_sprite = this.create_sprite_and_return(isForFront, layer_holder);
        for(var i in actual_sprite)
        {
            //console.log(i+":"+actual_sprite[i]);
        }
        actual_sprite.setImage( image );
        this.animate_on_change();
    }
    
    this.animate_on_change = function()
    {
        var sprite_for_show, sprite_for_hide;
        if(this.isForFront)
        {
            sprite_for_show = this.sprite__________front;
            sprite_for_hide = this.sprite___________back;
        }
        else
        {
            sprite_for_hide = this.sprite__________front;
            sprite_for_show = this.sprite___________back;
        }
        sprite_for_show.holder = this;
        var tween_show = new Kinetic.Tween({
        node: sprite_for_show, 
        duration: 0.5,
        opacity: 1,
        onFinish:function()
        {
            //this.node.setZIndex( this.node.holder.z__index );
        }
        });
        sprite_for_show.show();
        tween_show.play();
        if(sprite_for_hide != null)
        {
            var tween_hide = new Kinetic.Tween({
            node: sprite_for_hide, 
            duration: 0.5,
            opacity: 0,
            onFinish:function()
            {
                this.node.hide();
            }
            }); 
            tween_hide.play();
        }
    }
    
    this.create_sprite_and_return = function(isForFront, layer_holder)
    {
        if(isForFront && this.sprite__________front == null)
        {
            this.sprite__________front = new Kinetic.Image({
                image: null,
                x: 0,
                y: 0,
                visible: true,
                draggable: false,
                opacity:0
            });
            layer_holder.add( this.sprite__________front );
        }
        else if(!isForFront && this.sprite___________back == null)
        {
            this.sprite___________back = new Kinetic.Image({
                image: null,
                x: 0,
                y: 0,
                visible: true,
                draggable: false,
                opacity:0
            });
            layer_holder.add( this.sprite___________back ); 
        }
        if(isForFront)
        {
            return this.sprite__________front;
        }
        else
        {
            return this.sprite___________back;
        }
    }
    
    this.setup_front_sprite = function( layer_holder, image )
    {
        if(this.sprite_front_of_model == null)
        {
            this.sprite_front_of_model = new Kinetic.Image({
                image: null,
                x: 0,
                y: 0,
                visible: false,
                draggable: true,
                opacity:1
            });
            layer_holder.add( this.sprite_front_of_model );
        } 
        this.sprite_front_of_model.setImage( image );
    }
    
    this.show_front = function()
    {
        $("#model_holder_selected_part").removeClass("displayNone");
        this.sprite_front_of_model.show();
        this.sprite_front_of_model.setX(0);
        this.sprite_front_of_model.setY(0);
        this.sprite_front_of_model.moveToTop();
        ModelStage.MS.layer_model_selected_part.draw();
    }
    this.hide_front = function()
    {
        $("#model_holder_selected_part").addClass("displayNone"); 
    }
    
    this.moveToBottom = function()
    {
        if(this.sprite___________back != null){this.sprite___________back.moveToBottom();}
        if(this.sprite__________front != null){this.sprite__________front.moveToBottom();}
    }
    
    this.setZIndex = function(__z__index__)
    {
        this.z__index = __z__index__;
    }
    
    
    this.destroy = function()
    {
        var sprite_for_hide;
        if(this.isForFront)
        {
            sprite_for_hide = this.sprite__________front;
        }
        else
        {
            sprite_for_hide = this.sprite___________back;
        }
        sprite_for_hide.holder = this;
        var tween_hide = new Kinetic.Tween({
        node: sprite_for_hide, 
        duration: 0.5,
        opacity: 0,
        onFinish:function()
        {
            if(this.node.holder.sprite__________front != null)
            {
                console.log("this.node.sprite__________front.remove()");
                this.node.holder.sprite__________front.remove();
            }
            if(this.node.holder.sprite___________back != null)
            {
                console.log("this.node.sprite___________back.remove()");
                this.node.holder.sprite___________back.remove();
            }
            if(this.node.holder.sprite_front_of_model != null)
            {
                console.log("this.node.sprite_front_of_model.remove()");
                this.node.holder.sprite_front_of_model.remove();
            }
            console.log("==========================================================");
        }
        }); 
        tween_hide.play();
    }
}