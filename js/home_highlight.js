

	jQuery(document).ready(function(){    
        
        jQuery(".facetwp-facet.facetwp-facet-search_route.facetwp-type-fselect").click(function() { 
            var $filter = jQuery(".fs-search input"),
                $answer = jQuery(".fs-option-label");

                $filter.keyup(function(){
                // Retrieve the input field text and reset the count to zero
                var filter = jQuery(this).val(), count = 0;

                var regex = new RegExp(filter, "i");
                // Loop through the comment list
                $answer.each(function(){

                            var pageText = jQuery(this).text().replace("<span>","").replace("</span>"),
                            searchedText = $filter.val(),
                            theRegEx = new RegExp("("+searchedText+")", "igm"),
                            newHtml = pageText.replace(theRegEx ,"<span class='highlight'>$1</span>");

                    // If the list item does not contain the text phrase fade it out
                    if (jQuery(this).text().search(regex) < 0) {
                        jQuery(this)
                            .fadeOut('slow')
                            .attr("data-open", false);

                    // Show the list item if the phrase matches and increase the count by 1
                    } else {
                        jQuery(this)
                            .fadeIn('slow')
                            .attr("data-open", true);

                        jQuery(this).html(newHtml);

                        count++;
                    }
                });
				if(!filter){
         			pageText = jQuery(this).text().replace("<span>","").replace("</span>","")
    			}
                // Update the count
                var numberItems = count;
                jQuery("#filter-count").text("Number of Comments = "+count);
                });

                // on fselect click go to the route page
                jQuery('.fs-option').click(function(event){
                    var selectedId = jQuery(this).data('value')
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    window.open(
                        '/cerca/?_dove_vuoi_andare='+selectedId,
                        '_self'
                    );
                });
            });
	});