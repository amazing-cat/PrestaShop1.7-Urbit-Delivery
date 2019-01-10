(function () {
    $().ready(function () {
        var $tabs = $('#tabAddresses');
        if (!$tabs) {
            return;
        }

        // Add click handler for dynamically created tabs.
        $tabs.on('click', 'a', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Add Urbit tab.
        $tabs.append(
            '<li>'
          + '  <a href="#addressUrbit">'
          + '    <i class="icon-file-text"></i>'
          + '    Urb-it time and address'
          + '  </a>'
          + '</li>'
        );

        // Add Urbit tab content.
        var $tabContent = $('.tab-content', $tabs.parent());
        $tabContent.append(
            '<div class="tab-pane" id="addressUrbit">'
          + '  <h4 class="visible-print">Urb-it time and address</h4>'
          + '  <div class="well">'
          + '    <div class="row">'
          + '      <div class="col-sm-6" id="addressUrbitContent">'
          + '      </div>'
          + '      <div class="col-sm-6 hidden-print">'
          + '        <div id="map-delivery-canvas" style="height: 190px"></div>'
          + '      </div>'
          + '    </div>'
          + '  </div>'
          + '</div>'
        );

        // Parse query params.
        var match,
            pl     = /\+/g,
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
            query  = window.location.search.substring(1);

        var urlParams = {};
        while (match = search.exec(query)) {
            urlParams[decode(match[1])] = decode(match[2]);
        }

        // Fetch Urbit order data.
        $.ajax({
            url: [location.protocol, '//', location.host, location.pathname].join(''),
            data: {
                controller: 'AdminUrbit',
                action: 'getorderinfo',
                ajax: true,
                token: urbitToken,
                id_order: urlParams['id_order'],
            },
            type: 'POST',
            success: function (response) {
                $('#addressUrbitContent', $tabContent).append(response);
            },
        });
    });
})();
