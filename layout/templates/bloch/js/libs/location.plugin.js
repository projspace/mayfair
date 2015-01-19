/* --------------------------------------------------------------------------------------- ----
	
	Blog Entry:
	Binding Events To Non-DOM Objects With jQuery
	
	Author:
	Ben Nadel / Kinky Solutions
	
	Link:
	http://www.bennadel.com/index.cfm?event=blog.view&id=1520
	
	Date Posted:
	Mar 6, 2009 at 9:45 AM
	
---- --------------------------------------------------------------------------------------- */

// Our plugin will be defined within an immediately
// executed method.
(
	function( $ ){
		// Default to the current location.
		var strLocation = window.location.href;
		var strHash = window.location.hash;
		var strPrevLocation = "";
		var strPrevHash = "";

		// This is how often we will be checkint for
		// changes on the location.
		var intIntervalTime = 10;

		// This method removes the pound from the hash.
		var fnCleanHash = function( strHash ){
			return(
				strHash.substring( 1, strHash.length )
				);
		}

		// This will be the method that we use to check
		// changes in the window location.
		var fnCheckLocation = function(){
		console.log('testing');
			// Check to see if the location has changed.
			if (strLocation != window.location.href){

				// Store the new and previous locations.
				strPrevLocation = strLocation;
				strPrevHash = strHash;
				strLocation = window.location.href;
				strHash = window.location.hash;

				// The location has changed. Trigger a
				// change event on the location object,
				// passing in the current and previous
				// location values.
				$( window.location ).trigger(
					"change",
					{
						currentHref: strLocation,
						currentHash: fnCleanHash( strHash ),
						previousHref: strPrevLocation,
						previousHash: fnCleanHash( strPrevHash )
					}
				);

			}
		}

		// Set an interval to check the location changes.
		setInterval( fnCheckLocation, intIntervalTime );
	}
)( jQuery );