(function ($) {
    jQuery( document ).ready(function() {
        jQuery(".list_entities_wrapper").each(function(index){
            wrapper = jQuery(this).children("#list_entities");
            getPagination(wrapper);
            getEvents(wrapper);
        })
        jQuery( "#filters_input" ).submit(function(e) {
            e.preventDefault();
            wrapper = jQuery(this).parent().parent();
            wrapper.data('page',1);
            getEvents(wrapper);
        });
        jQuery( "#filters_input select" ).change(function() {
            wrapper = jQuery(this).parent().parent().parent();
            wrapper.data('page',1);
            getEvents(wrapper);
        });
        jQuery("#list_entities #filters_input .toggle_filters").click(function(e) {
          jQuery("#list_entities #filters_input").toggleClass("open");
      });

    });

    function getPagination(wrapper)
    {
        var pagination = wrapper.data('pagination');
        if (typeof pagination !== 'undefined')
        {
            wrapper.find( ".page-before" ).click(function() {
                wrapper = jQuery(this).parent().parent();
                var page = wrapper.data('page');
                if (typeof page !== 'undefined')
                {
                    if (page > 1)
                    {
                        wrapper.data('page',page-1);
                        getEvents(wrapper);
                    }

                }
            });

            wrapper.find( ".page-after" ).click(function() {
                wrapper = jQuery(this).parent().parent();
                var page = wrapper.data('page');
                var numPages = wrapper.data('numPages');
                if (typeof page !== 'undefined')
                {
                    if (page < numPages)
                    {
                        wrapper.data('page',page+1);
                        getEvents(wrapper);
                    }
                }
                else
                {
                    if (numPages > 1) {
                        wrapper.data('page',2);
                        getEvents(wrapper);
                    }
                }

            });
        }
    }
    function getEvents(wrapper)
    {
        wrapper.children(".list_entities").html('');
        wrapper.children('#loading').show('fast');
        wrapper.children('.pagination').addClass('hidden');
        wrapper.children('#filters_input').addClass('hidden');


        var page = wrapper.data('page');
        if (typeof page !== 'undefined')
        {
            var pagestr = "&@page="+page;
        }
        else
        {
            var pagestr = "";
        }
        var limit = wrapper.data('limit');
        if (typeof limit !== 'undefined')
        {
            var limitstr = "&@limit="+limit;
        }

        var filters = wrapper.data('filters');
        if (typeof filters !== 'undefined')
        {
            var filters_values={};
            wrapper.find("#filters_input").children().each(function(index) { filters_values[jQuery(this).attr("id")] = jQuery(this).val(); })
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
        var url = wrapper.data('url') + limitstr + pagestr + filtersstr;


        jQuery.ajax({
            url: mapas_builder_proxy_ajax_obj.ajaxurl,
            type: 'GET',
            data: {
                'action': 'mapas_builder_proxy_request',
                'url' : url
            },
            success: function(response, status, xhr) {
                var meta = JSON.parse(xhr.getResponseHeader("api-metadata"));
                if (meta != null) {
                    wrapper.find('.pagination .numEntities .outOf').text(meta.count);
                    wrapper.find('.pagination .numEntities .from').text(Math.min((((meta.page-1)*meta.limit)+1),meta.count));
                    if (meta.page == meta.numPages)
                    {
                        wrapper.find('.pagination .numEntities .to').text(((meta.page-1)*meta.limit)+(meta.count%meta.limit));
                    }
                    else
                    {
                        wrapper.find('.pagination .numEntities .to').text(meta.page*meta.limit);
                    }
                    wrapper.data('numPages',meta.numPages);
                }

                showEvents(wrapper,response);
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

function showEvents(wrapper,entities)
{
    wrapper.children('#loading').hide('fast');
    wrapper.children('#filters_input').removeClass('hidden');
    wrapper.children('.pagination').removeClass('hidden');
    baseurl = wrapper.data('baseurl');
    entity  = wrapper.data('entity');
    if (wrapper.parent().children("#mustache-template").length > 0)
    {
        mustache_template = wrapper.parent().children("#mustache-template").html();
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
    wrapper.children('.list_entities').append(html);
}
})(jQuery);
