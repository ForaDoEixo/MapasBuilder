(function ($) {
    jQuery( document ).ready(function() {
        getEvents();

        var pagination = jQuery('#list_entities').data('pagination');
        if (typeof pagination !== 'undefined')
        {
            jQuery( "#page-before" ).click(function() {
                var page = jQuery('#list_entities').data('page');
                if (typeof page !== 'undefined')
                {
                    if (page > 1)
                    {
                        jQuery('#list_entities').data('page',page-1);
                        getEvents();
                    }

                }
            });

            jQuery( "#page-after" ).click(function() {
                var page = jQuery('#list_entities').data('page');
                if (typeof page !== 'undefined')
                {
                    jQuery('#list_entities').data('page',page+1);
                }
                else
                {
                    jQuery('#list_entities').data('page',2);
                }
                getEvents();
            });
        }
    }
    );

function getEvents(){
    jQuery('.list_entities').html('');
    jQuery('#loading').show('fast');

    var page = jQuery('#list_entities').data('page');
    if (typeof page !== 'undefined')
    {
        var pagestr = "&@page="+page;
    }
    else
    {
        var pagestr = "";
    }
    var limit = jQuery('#list_entities').data('limit');
    if (typeof limit !== 'undefined')
    {
        var limitstr = "&@limit="+limit;
    }
    var url = jQuery('#list_entities').data('url') + limitstr + pagestr;


    jQuery.ajax({
        url: url,
        type: 'GET',
        data: {},
        success: function(response) {
            showEvents(response);
        }
    });
}

function processKeynames(entity)
{
  const propNames = Object.getOwnPropertyNames(entity);

  propNames.forEach(function(name) {
    if (name.includes("."))
    {
        const desc = Object.getOwnPropertyDescriptor(entity, name);
        Object.defineProperty(entity, name.split(".").slice(-1), desc);
        delete entity[name];
    }
});


}

function showEvents(entities){
    jQuery('#loading').hide('fast');
    baseurl = jQuery('#list_entities').data('baseurl');
    entity  = jQuery('#list_entities').data('entity');

    html = '';
    for (var i = 0; i < entities.length; i++) {
        thumb = '';
        processKeynames(entities[i]);

        if (mustache_template === "")
        {
            mustache_template = `
            <div class="row list_entities_item">
            <div class="col-md-3"><img src="{{avatarBig.url}}"></div>
            <div class="col-md-9">
            <h3><a href="{{singleUrl}}" target="_blank">{{name}}</a></h3>
            <p>{{shortDescription}}</p><br>
            </div></div>
            `;
        }
        html += Mustache.render(mustache_template,entities[i]);
    }

    jQuery('.list_entities').append(html);
}
})(jQuery);
