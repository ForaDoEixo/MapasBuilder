(function ($) {
    jQuery( document ).ready(function() {
        getEvents();
        jQuery( "#filters_input" ).submit(function(e) {
            e.preventDefault();
            jQuery('#list_entities').data('page',1);
            getEvents();
        });
        jQuery( "#filters_input select" ).change(function() {
            jQuery('#list_entities').data('page',1);
            getEvents();
        });
        jQuery("#list_entities #filters_input .toggle_filters").click(function(e) {
          jQuery("#list_entities #filters_input").toggleClass("open");
      });
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
                var numPages = jQuery('#list_entities').data('numPages');
                if (typeof page !== 'undefined')
                {
                    if (page < numPages)
                    {
                        jQuery('#list_entities').data('page',page+1);
                        getEvents();
                    }
                }
                else
                {
                    if (numPages > 1) {
                        jQuery('#list_entities').data('page',2);
                        getEvents();
                    }
                }

            });
        }
    }
    );

function getEvents(){
    jQuery('.list_entities').html('');
    jQuery('#loading').show('fast');
    jQuery('.pagination').addClass('hidden');
    jQuery('#filters_input').addClass('hidden');

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

    var filters = jQuery('#list_entities').data('filters');
    if (typeof filters !== 'undefined')
    {
        var filters_values={};
        jQuery("#filters_input").children().each(function(index) { filters_values[jQuery(this).attr("id")] = jQuery(this).val(); })

        var filtersstr = Mustache.render(filters,filters_values);
        if ((filtersstr != "") && (filtersstr.charAt(0) != "&"))
        {
            filtersstr = "&"+filtersstr;
        }

    }
    else
    {
        var filtersstr = "";
    }
    var url = jQuery('#list_entities').data('url') + limitstr + pagestr + filtersstr;


    jQuery.ajax({
        url: url,
        type: 'GET',
        data: {},
        success: function(response, status, xhr) {
            var meta = JSON.parse(xhr.getResponseHeader("api-metadata"));
            jQuery('.pagination .numEntities .outOf').text(meta.count);
            jQuery('.pagination .numEntities .from').text(Math.min((((meta.page-1)*meta.limit)+1),meta.count));
            if (meta.page == meta.numPages)
            {
                jQuery('.pagination .numEntities .to').text(((meta.page-1)*meta.limit)+(meta.count%meta.limit));
            }
            else
            {
                jQuery('.pagination .numEntities .to').text(meta.page*meta.limit);
            }
            jQuery('#list_entities').data('numPages',meta.numPages);
            showEvents(response);
        },
        error: function(xhr,status,response) {
            throwError(status,response);
        }
    });
}

function throwError(status,response)
{
    jQuery('#loading').hide('fast');
    jQuery('.list_entities').append("<span class='error'>"+status.toUpperCase()+"! The API returned an error: "+response+"</span>");
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

function loadTemplate()
{
    return`
    <div class="row list_entities_item">
    {{#avatarBig.url}}<div class="col-md-3"><img src="{{avatarBig.url}}"></div>{{/avatarBig.url}}
    <div class="col-md-9">
    <h3><a href="{{singleUrl}}" target="_blank">{{name}}</a></h3>
    <p>{{shortDescription}}</p><br>
    </div>
    </div>
    `;
}

function showEvents(entities){
    jQuery('#loading').hide('fast');
    jQuery('#filters_input').removeClass('hidden');
    jQuery('.pagination').removeClass('hidden');
    baseurl = jQuery('#list_entities').data('baseurl');
    entity  = jQuery('#list_entities').data('entity');
    if (jQuery("#mustache-template").length > 0)
    {
        mustache_template = jQuery("#mustache-template").html();
    }
    else
    {
        mustache_template = loadTemplate();
    }
    html = '';
    for (var i = 0; i < entities.length; i++) {
        thumb = '';
        processKeynames(entities[i]);

        entities[i].FormatDate = function() {
            return function(rawDate, render) {
                return new Date(render(rawDate)).toLocaleDateString("pt-BR");
            }
        };

        html += Mustache.render(mustache_template,entities[i]);
    }

    jQuery('.list_entities').append(html);
}
})(jQuery);
