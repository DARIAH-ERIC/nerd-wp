function hoverEntity() {
    jQuery(".nerd_tags").each(function(index, value) {
        var wiki_ids = jQuery(this).attr('id');
        var wiki_array = wiki_ids.split(";");
        var wiki_final = [];
        for(var i = 0; i < wiki_array.length; i++) {
            if(wiki_array[i].indexOf(":") > -1) {
                var array = wiki_array[i].split( ':' );
                wiki_final[ array[0] ] = array[1];
            }
        }

        var wikipedia_id = jQuery(this).attr('id').split(";")[0];
        jQuery(this).hover(function() {
            if(jQuery(this).find(document.getElementById('info-' + wikipedia_id)).length > 0) {
            } else if(!window.ajaxRunning) {
                window.ajaxRunning = true;
                var this_element = jQuery(this);
                jQuery.ajax({
                    type: 'GET',
                    url: '/nerd_kb_service/?url=' + encodeURIComponent('service/kb/concept/' + wikipedia_id.split(":")[1] + '?lang=' + wikipedia_id.split(":")[2]),
                    success: function(result) {
                        var entityStr = viewEntity(jQuery.parseJSON(result), wikipedia_id);
                        jQuery(this_element).find('.waiting').remove();
                        jQuery(this_element).append(entityStr);
                    },
                    complete: function() {
                        window.ajaxRunning = false;
                    },
                    dataType: 'json'
                });
            }
        }, function(){});
    });
}

function viewEntity(entity, wikipedia_id) {
    var lang ='en';
    if(wikipedia_id.split(":")[2] !== false) {
        lang = wikipedia_id.split(":")[2];
    }

    var wikipedia = entity.wikipediaExternalRef;
    var wikidataId = entity.wikidataId;
    var domains = entity.domains;
    var type = entity.type;

    var colorLabel = null;
    if (type)
        colorLabel = type;
    else if (domains && domains.length>0) {
        colorLabel = domains[0].toLowerCase();
    }
    else
        colorLabel = entity.rawName;

    var subType = entity.subtype;
    var conf = entity.nerd_score;
    var definitions = entity.definitions;
    // var definitions = getDefinitions(wikipedia);

    var content = entity.rawName;
    var normalized = entity.preferredTerm;
    // var normalized = getPreferredTerm(wikipedia);

    var sense = null;
    if (entity.sense)
        sense = entity.sense.fineSense;

    var string = "<div id='info-" + wikipedia_id + "' class='info-sense-box "+colorLabel+
        "'><h3 style='color:#FFF;padding-left:10px;'>"+content.toUpperCase()+
        "</h3>";
    string += "<div class='container-fluid' style='background-color:#F9F9F9;color:#70695C;border:padding:5px;margin-top:5px;'>";

    if (type)
        string += "<p>Type: <b>"+type+"</b></p>";

    if (sense) {
        // to do: cut the sense string to avoid a string too large
        if (sense.length <= 20)
            string += "<p>Sense: <b>"+sense+"</b></p>";
        else {
            var ind = sense.indexOf('_');
            if (ind != -1) {
                string += "<p>Sense: <b>"+sense.substring(0, ind+1)+"<br/>"+
                    sense.substring(ind+1, sense.length)+"</b></p>";
            }
            else
                string += "<p>Sense: <b>"+sense+"</b></p>";
        }
    }
    if (normalized)
        string += "<p>Normalized: <b>"+normalized+"</b></p>";

    if (domains && domains.length>0) {
        string += "<p>Domains: <b>";
        for(var i=0; i<domains.length; i++) {
            if (i != 0)
                string += ", ";
            string += domains[i];
        }
        string += "</b></p>";
    }

    string += "<p>conf: <i>"+conf+ "</i></p>";

    if ((definitions != null) && (definitions.length > 0)) {
        var localHtml = wiki2html(definitions[0]['definition'], lang);
        string += "<p><div class='wiky_preview_area2'>"+localHtml+"</div></p>";
    }

    if (wikipedia != null) {
        string += '<p>References: ';
        if (wikipedia != null) {
            string += '<a href="http://'+lang+'.wikipedia.org/wiki?curid=' +
                wikipedia +
                '" target="_blank">Wikipedia</a> ';
        }
        if (wikidataId != null) {
            string += '<a href="https://www.wikidata.org/wiki/' +
                wikidataId +
                '" target="_blank">Wikidata</a>';
        }
        string += '</p>';
    }

    string += "</div></div>";


    return string;
}
