function ModelPart()
{
    this.sprite__________front = null;
    this.sprite___________back = null;
    this.sprite_front_of_model = null;
    this.isForFront = true;
    
    this.setup = function(isForFront, image, layer_holder)
    {
        this.isForFront = isForFront;
        var actual_sprite = this.create_sprite_and_return(isForFront, layer_holder);
        for(var i in actual_sprite)
        {
            console.log(i+":"+actual_sprite[i]);
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
        var tween_show = new Kinetic.Tween({
        node: sprite_for_show, 
        duration: 0.5,
        opacity: 1
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
    
    this.setup_front_sprite = function( layer_holder )
    {
        if(this.sprite_front_of_model == null)
        {
            this.sprite_front_of_model = new Kinetic.Image({
                image: null,
                x: 0,
                y: 0,
                visible: true,
                draggable: false,
                opacity:0
            });
            layer_holder.add( this.sprite_front_of_model );
        } 
    }
    
    this.moveToBottom = function()
    {
        if(this.sprite___________back != null){this.sprite___________back.moveToBottom();}
        if(this.sprite__________front != null){this.sprite__________front.moveToBottom();}
    }
    
    
    this.destroy = function()
    {
    }
}