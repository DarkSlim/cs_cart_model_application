<div class="cs-model">
    <div id="main-bg"></div>
    <!-- cloth section -->
    <div class="ajax-load2"></div>
    <!-- products added to cart success -->
    <div class="cart-success">Products added to cart.</div>
    <div id="model_holder" class="">
        <!--Templates for the model-->
        <style>
            #dragable_image_temp_temp
            {
                position: fixed;
                z-index: 9999;
            }
        </style>
        <img class="displayNone" id="dragable_image_temp_temp" draggable="false">
        <!--Templates for the model-->
    </div>
    <div id="model_holder_selected_part" class="displayNone"></div>
    <!--<div class="model_holder"></div>-->
    <!-- turn button -->
    <div id="cs-turn-model-button" click="alert(12);">TURN</div>
    <!-- share icons -->
    <div class="cs-share-icons">
        <h3>SHARE</h3>
        <script>function fbs_click() {
                u = location.href;
                t = document.title;
                window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');
                return false;
            }
        </script>
        <a href="http://www.facebook.com/share.php?u=http://closse.jeniusinc.com" onclick="return fbs_click()" target="_blank" class="cs-fb-icon"><img src="img/facebook-icon.png" width="31" height="32" alt="Facebook" /></a>
        <a href="http://twitter.com/share?url=http%3A%2F%2Fclosse.jeniusinc.com" target="_blank"" class="cs-fb-icon"><img src="img/twitter-icon.png" width="31" height="32" alt="Twitter" /></a>
        <a href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;http://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());'><img src='http://closse.jeniusinc.com/cloth_system/img/pinterest-icon.png' class="cs-fb-icon"/></a>
        <a href="https://plus.google.com/share?url=http://closse.jeniusinc.com" class="cs-fb-icon" target="_blank"><img src="img/gplus-icon.png" width="31" height="32" alt="Google plus" /></a>



    </div>
    <!-- shopping cart -->
    <div class="shop-cart-holder">
        <div class="cs-shopping-cart">
            <div class="cs-cart-items"><span class="colapse-control"></span><span class="items-count">0 ITEMS</span></div>

            <div class="cs-cart-total">TOTAL: $00.00</div>
        </div>
        <!-- add to cart button -->
        <div class="add-to-cart-btn"></div>
    </div>
</div>
<div id="cartItemHolder" class="displayNone">
    <div class="cs-selected-product">
        <a href="#" class="cs-remove-product"></a>
        <p class="cs-prd-name"></p>
        <p class="cs-prd-price"><strong></strong></p>
    </div>
</div>