{**
 * Urbit for Pretashop
 *
 * @author    Urb-it
 * @copyright Urb-it
 * @license
 *}

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function () {
        $("#check_zipcode").click(function () {
            var postcode = $('#enter_zipcode').val();

            $.ajax({
                url: "{$base_url|escape:'htmlall':'UTF-8'}",
                type: 'post',
                data: {
                    ajax: true,
                    postcode: postcode,
                    module: 'urbit',
                    fc: 'module',
                    controller: 'ShippingOptions'
                },
                success: function (response) {
                    var responseObj = jQuery.parseJSON(response);

                    if (responseObj.hasOwnProperty("inside_delivery_area") && responseObj.inside_delivery_area === "yes") {
                        jQuery(".urb-it-validator .alert-success").show();
                        jQuery(".urb-it-validator .alert-danger").hide();
                    } else {
                        jQuery(".urb-it-validator .alert-danger").show();
                        jQuery(".urb-it-validator .alert-success").hide();
                    }
                }
            });
        });
    });
</script>

<div class="urb-it-validator">
    <p>
        <img src="{$urbit_img_path|escape:'html':'utf-8'}urbit_post.jpg" style="width: 50px;">Can you purchase with
        urb-it?
    </p>
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Enter Zipcode" name="enter_zipcocde" id="enter_zipcode"
               value="" style="height: 32px;">
        <span class="input-group-btn">
			<button class="btn btn-primary" type="button" id="check_zipcode" name="zipcode">Check</button>
    </div>
    <div>
        <div class="alert alert-success" role="alert" style="display: none;">
            You can shop with Urb-it!
        </div>
        <div class="alert alert-danger" role="alert" style="display: none;">
            Right now, you can not shop with Urb-it on that zip code.
        </div>
    </div>
</div>
