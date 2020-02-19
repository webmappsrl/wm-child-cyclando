jQuery(document).ready(function($){


	const $postRouteBody = $('.post-php.post-type-route');
	if ( $postRouteBody.length > 0 )
	{
		let removed = false;
		setInterval( () => {
			if ( ! removed )
			{
				console.log('test');
				let activitiesButton = $('.components-panel__body').find('button:contains(Activities)');
				if ( activitiesButton !== null && activitiesButton.length > 0 )
				{
					activitiesButton.parents('.components-panel__body').remove();
					removed=true;
				}
			}

		}, 500);
	}
});
