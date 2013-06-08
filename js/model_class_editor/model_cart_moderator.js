function CartItemModel()
{
    //za da izbrises item, kaj linkovite od kartickata,
    //koristis 
    //ModelStage.MS.model.remove_item(ModelClothingPart.ALL_PARTS["__ovde doaga ajdito na produktot__"]);
    //ovde doaga ajdito na produktot, ova treba da bide ajdito na produktot
    this.add_to_bagg = function()
    {
    }
    /*GlobalEventor.GE.addEvent(GlobalEventor.ON_ADD_ITEM_TO_MODEL, function(item_added)
     {
     
     });*/

    /*
     * 
     * @returns {undefined}
     * After drop item from products, it will use all items from Model.
     * Will add new line of text, will change the price.
     * ... and other changes of the cart
     */
    this.cart_refresh = function()
    {
        //ja koristis ovaa niza.Taa terba da ima ceni, ajidja i site gluposti
        //ModelStage.MS.model.parts.length
        if (ModelStage.MS.model.parts.length > 0) {
            $('.cs-shopping-cart').show();
            $('.add-to-cart-btn').show();
        }
        else {
            $('.cs-shopping-cart').hide();
            $('.add-to-cart-btn').hide();
        }
        $('.cs-shopping-cart').find('.cs-selected-product').remove();
       
        for (var i = 0; i < ModelStage.MS.model.parts.length; i++) {
            var itemInCartTemplate = $("#cartItemHolder").find('div');
            $(itemInCartTemplate).attr('productId', ModelStage.MS.model.parts[i].product_id);
            $(itemInCartTemplate).find('.cs-prd-name').html('undefined');
            $(itemInCartTemplate).find('.cs-prd-price').find('strong').html("$"+ModelStage.MS.model.parts[i].price);
            
            $(itemInCartTemplate).clone().insertAfter('.cs-cart-items');
        }
        //update amount
        ModelStage.MS.cart_item_model.updateTotalAmount();
        //Remove product from cart
         $('.cs-remove-product').click(function(event) {
                var productID = $(this).parent().attr('productId');
                
                ModelStage.MS.model.remove_item(ModelClothingPart.ALL_PARTS["__" + productID + "__"]);

            })
        var leftPos = $('.cs-shopping-cart').position().left;
        var topPos = $('.cs-shopping-cart').position().top + $('.cs-shopping-cart').outerHeight(true);
        $('div.add-to-cart-btn').css({'left': leftPos + 11 + 'px', 'top': topPos + 'px'});

    }
    //total price
    this.updateTotalAmount = function() {
        var totalAmount = 0;
        for (var i = 0; i < ModelStage.MS.model.parts.length; i++) {
            totalAmount += ModelStage.MS.model.parts[i].price;
        }
        $('.cs-cart-total').html("TOTAL: $" + totalAmount.toFixed(2));
        $('.items-count').html(ModelStage.MS.model.parts.length + " ITEMS");
    }
}