/*! Swipebox v1.2.6 | Constantin Saguin csag.co | MIT License | github.com/brutaldesign/swipebox */

;( function ( window, document, $, undefined ) {

	$.swipebox = function( elem, options ) {

		// Default options
		var defaults = {
			useCSS : true,
			initialIndexOnArray : 0,
			hideBarsDelay : 3000,
			videoMaxWidth : 1140,
			vimeoColor : 'CCCCCC',
			beforeOpen: null,
			afterClose: null
		},
		
		plugin = this,
		elements = [], // slides array [ { href:'...', title:'...' }, ...],
		selector = elem.selector,
		$selector = $( selector ),
		isMobile = navigator.userAgent.match( /(iPad)|(iPhone)|(iPod)|(Android)|(PlayBook)|(BB10)|(BlackBerry)|(Opera Mini)|(IEMobile)|(webOS)|(MeeGo)/i ),
		supportSVG = !! document.createElementNS && !! document.createElementNS( 'http://www.w3.org/2000/svg', "svg").createSVGRect,
		winWidth = window.innerWidth ? window.innerWidth : $( window ).width(),
		winHeight = window.innerHeight ? window.innerHeight : $( window ).height(),
		/* jshint multistr: true */
		html = '<div id="swipebox-overlay">\
				<div id="swipebox-slider"></div>\
				<div id="swipebox-caption"></div>\
				<div id="swipebox-action">\
					<a id="swipebox-close"></a>\
					<a id="swipebox-prev"></a>\
					<a id="swipebox-next"></a>\
				</div>\
		</div>';

		plugin.settings = {};

		plugin.init = function() {

			plugin.settings = $.extend( {}, defaults, options );

			if ( $.isArray( elem ) ) {

				elements = elem;
				ui.target = $( window );
				ui.init( plugin.settings.initialIndexOnArray );

			} else {

				$( document ).on( 'click', selector, function( event ) {

					if ( event.target.parentNode.className === 'slide current' ) {

						return false;

					}
					
					if ( ! $.isArray( elem ) ) {
						ui.destroy();
						$elem = $( selector );
						ui.actions();
					}
					
					elements = [];
					var index , relType, relVal;

					if ( ! relVal ) {
						relType = 'rel';
						relVal  = $( this ).attr( relType );
					}

					if ( relVal && relVal !== '' && relVal !== 'nofollow' ) {
						$elem = $selector.filter( '[' + relType + '="' + relVal + '"]' );
					} else {
						$elem = $( selector );
					}

					$elem.each( function() {

						var title = null, 
							href = null;
						
						if ( $( this ).attr( 'title' ) ) {
							title = $( this ).attr( 'title' );
						}
							

						if ( $( this ).attr( 'href' ) ) {
							href = $( this ).attr( 'href' );
						}
							
						elements.push( {
							href: href,
							title: title
						} );
					} );

					index = $elem.index( $( this ) );
					event.preventDefault();
					event.stopPropagation();
					ui.target = $( event.target );
					ui.init( index );
				} );
			}
		};

		/**
		 * Refresh method
		 */
		plugin.refresh = function() {
			if ( ! $.isArray( elem ) ) {
				ui.destroy();
				$elem = $( selector );
				ui.actions();
			}
		};

		var ui = {

			/**
			 * Initiate Swipebox
			 */
			init : function( index ) {
				if ( plugin.settings.beforeOpen ) 
					plugin.settings.beforeOpen();
				$.swipebox.isOpen = true;
				this.build();
				this.openSlide( index );
				this.openMedia( index );
				this.preloadMedia( index+1 );
				this.preloadMedia( index-1 );
				this.target.trigger( 'swipebox-start' );
			},

			/**
			 * Built HTML containers and fire main functions
			 */
			build : function () {
				var $this = this;

				$( 'body' ).append( html );
								
				if ( $this.doCssTrans() ) {
					$( '#swipebox-slider' ).css( {
						'-webkit-transition' : 'left 0.4s ease',
						'-moz-transition' : 'left 0.4s ease',
						'-o-transition' : 'left 0.4s ease',
						'-khtml-transition' : 'left 0.4s ease',
						'transition' : 'left 0.4s ease'
					} );
					$( '#swipebox-overlay' ).css( {
						'-webkit-transition' : 'opacity 1s ease',
						'-moz-transition' : 'opacity 1s ease',
						'-o-transition' : 'opacity 1s ease',
						'-khtml-transition' : 'opacity 1s ease',
						'transition' : 'opacity 1s ease'
					} );
					$( '#swipebox-action, #swipebox-caption' ).css( {
						'-webkit-transition' : '0.5s',
						'-moz-transition' : '0.5s',
						'-o-transition' : '0.5s',
						'-khtml-transition' : '0.5s',
						'transition' : '0.5s'
					} );
				}


				if ( supportSVG ) {
					var bg = $( '#swipebox-action #swipebox-close' ).css( 'background-image' );
					bg = bg/*.replace( 'png', 'svg' )*/;
					$( '#swipebox-action #swipebox-prev,#swipebox-action #swipebox-next,#swipebox-action #swipebox-close' ).css( {
						'background-image' : bg
					} );
				}
				
				$.each( elements,  function() {
					$( '#swipebox-slider' ).append( '<div class="slide"></div>' );
				} );

				$this.setDim();
				$this.actions();
				
				if ( isMobile ) {
					$this.gesture();
				} else {
					$this.keyboard();
				}
				
				$this.animBars();
				$this.resize();
				
			},

			/**
			 * Set dimensions depending on windows width and height
			 */
			setDim : function () {

				var width, height, sliderCss = {};
				
				// Reset dimensions on mobile orientation change
				if ( "onorientationchange" in window ) {

					window.addEventListener( "orientationchange", function() {
						if ( window.orientation === 0 ) {
							width = winWidth;
							height = winHeight;
						} else if ( window.orientation === 90 || window.orientation === -90 ) {
							width = winHeight;
							height = winWidth;
						}
					}, false );
					
				
				} else {

					width = window.innerWidth ? window.innerWidth : $( window ).width();
					height = window.innerHeight ? window.innerHeight : $( window ).height();
				}

				sliderCss = {
					width : width,
					height : height
				};

				$( '#swipebox-overlay' ).css( sliderCss );

			},

			/**
			 * Reset dimensions on window resize envent
			 */
			resize : function () {
				var $this = this;
				
				$( window ).resize( function() {
					$this.setDim();
				} ).resize();
			},

			/**
			 * Check if device supports CSS transitions
			 */
			supportTransition : function () {
				
				var prefixes = 'transition WebkitTransition MozTransition OTransition msTransition KhtmlTransition'.split( ' ' );
				
				for ( var i = 0; i < prefixes.length; i++ ) {
					if ( document.createElement( 'div' ).style[ prefixes[i] ] !== undefined ) {
						return prefixes[i];
					}
				}
				return false;
			},

			/**
			 * Check if CSS transitions are allowed (options + devicesupport)
			 */
			doCssTrans : function () {
				if ( plugin.settings.useCSS && this.supportTransition() ) {
					return true;
				}
			},

			/**
			 * Touch navigation
			 */
			gesture : function () {
				
				var $this = this,
				distance = null,
				swipMinDistance = 10,
				startCoords = {},
				endCoords = {};
				var bars = $( '#swipebox-caption, #swipebox-action' );

				bars.addClass( 'visible-bars' );
				$this.setTimeout();

				$( 'body' ).bind( 'touchstart', function( event ) {

					$(this).addClass( 'touching' );

					endCoords = event.originalEvent.targetTouches[0];
					startCoords.pageX = event.originalEvent.targetTouches[0].pageX;

					$( '.touching' ).bind( 'touchmove',function( event ) {
						event.preventDefault();
						event.stopPropagation();
						endCoords = event.originalEvent.targetTouches[0];

					} );
	
					return false;

				} ).bind( 'touchend',function( event ) {
					event.preventDefault();
					event.stopPropagation();
				
					distance = endCoords.pageX - startCoords.pageX;
						
					if ( distance >= swipMinDistance ) {
						
						// swipeLeft
						$this.getPrev();
					
					} else if ( distance <= - swipMinDistance ) {
						
						// swipeRight
						$this.getNext();

					} else {
						// tap
						if ( ! bars.hasClass( 'visible-bars' ) ) {
							$this.showBars();
							$this.setTimeout();
						} else {
							$this.clearTimeout();
							$this.hideBars();
						}

					}	

					$( '.touching' ).off( 'touchmove' ).removeClass( 'touching' );
						
				} );

			},

			/**
			 * Set timer to hide the action bars
			 */
			setTimeout: function () {
				if ( plugin.settings.hideBarsDelay > 0 ) {
					var $this = this;
					$this.clearTimeout();
					$this.timeout = window.setTimeout( function() {
							$this.hideBars(); 
						},
						
						plugin.settings.hideBarsDelay
					);
				}
			},
			
			/**
			 * Clear timer
			 */
			clearTimeout: function () {	
				window.clearTimeout( this.timeout );
				this.timeout = null;
			},

			/**
			 * Show navigation and title bars
			 */
			showBars : function () {
				var bars = $( '#swipebox-caption, #swipebox-action' );
				if ( this.doCssTrans() ) {
					bars.addClass( 'visible-bars' );
				} else {
					$( '#swipebox-caption' ).animate( { top : 0 }, 500 );
					$( '#swipebox-action' ).animate( { bottom : 0 }, 500 );
					setTimeout( function() {
						bars.addClass( 'visible-bars' );
					}, 1000 );
				}
			},

			/**
			 * Hide navigation and title bars
			 */
			hideBars : function () {
				var bars = $( '#swipebox-caption, #swipebox-action' );
				if ( this.doCssTrans() ) {
					bars.removeClass( 'visible-bars' );
				} else {
					$( '#swipebox-caption' ).animate( { top : '-50px' }, 500 );
					$( '#swipebox-action' ).animate( { bottom : '-50px' }, 500 );
					setTimeout( function() {
						bars.removeClass( 'visible-bars' );
					}, 1000 );
				}
			},

			/**
			 * Animate navigation and top bars
			 */
			animBars : function () {
				var $this = this;
				var bars = $( '#swipebox-caption, #swipebox-action' );
					
				bars.addClass( 'visible-bars' );
				$this.setTimeout();
				
				$( '#swipebox-slider' ).click( function() {
					if ( ! bars.hasClass( 'visible-bars' ) ) {
						$this.showBars();
						$this.setTimeout();
					}
				} );

				if ( ! isMobile ) {

					$( '#swipebox-action' ).hover( function() {
						$this.showBars();
						bars.addClass( 'visible-bars' );
						$this.clearTimeout();
					
						}, function() { 
							bars.removeClass( 'visible-bars' );
							$this.setTimeout();

					} );

				}
			},

			/**
			 * Keyboard navigation
			 */
			keyboard : function () {
				var $this = this;
				$( window ).bind( 'keyup', function( event ) {
					event.preventDefault();
					event.stopPropagation();
					
					if ( event.keyCode === 37 ) {
						
						$this.getPrev();
					
					} else if ( event.keyCode === 39 ) {
						
						$this.getNext();
					
					} else if ( event.keyCode === 27 ) {
						
						$this.closeSlide();
					
					}
				} );
			},

			/**
			 * Navigation events : go to next slide, go to prevous slide and close
			 */
			actions : function () {
				var $this = this;

				var action = isMobile ? 'touchend' : 'click';
				
				if ( elements.length < 2 ) {
					
					$( '#swipebox-prev, #swipebox-next' ).hide();
				
				} else {
					$( '#swipebox-prev' ).bind( action, function( event ) {
						event.preventDefault();
						event.stopPropagation();
						$this.getPrev();
						$this.setTimeout();
					} );
					
					$( '#swipebox-next' ).bind( action, function( event ) {
						event.preventDefault();
						event.stopPropagation();
						$this.getNext();
						$this.setTimeout();
					} );
				}

				$( '#swipebox-close' ).bind( action, function() {
					$this.closeSlide();
				} );
			},
			
			/**
			 * Set current slide
			 */
			setSlide : function ( index, isFirst ) {
				isFirst = isFirst || false;
				
				var slider = $( '#swipebox-slider' );
				
				if ( this.doCssTrans() ) {
					slider.css( { left : ( -index*100 )+'%' } );
				} else {
					slider.animate( { left : ( -index*100 )+'%' } );
				}
				
				$( '#swipebox-slider .slide' ).removeClass( 'current' );
				$( '#swipebox-slider .slide' ).eq( index ).addClass( 'current' );
				$( '.fotos .foto a' ).removeClass( 'current' );
				$( '.fotos .foto' ).eq( index ).children('a').addClass( 'current' );
				this.setTitle( index );

				if ( isFirst ) {
					slider.fadeIn();
				}

				$( '#swipebox-prev, #swipebox-next' ).removeClass( 'disabled' );
				
				if ( index === 0 ) {
					$( '#swipebox-prev' ).addClass( 'disabled' );
				} else if ( index === elements.length - 1 ) {
					$( '#swipebox-next' ).addClass( 'disabled' );
				}
				this.target.trigger( 'swipebox-change' );
			},
		
			/**
			 * Open slide
			 */
			openSlide : function ( index ) {
				$( 'html' ).addClass( 'swipebox-html' );
				if ( isMobile ) {
					$( 'html' ).addClass( 'swipebox-touch' );
				}
				$( window ).trigger( 'resize' ); // fix scroll bar visibility on desktop
				this.setSlide( index, true );
			},
		
			/**
			 * Set a time out if the media is a video
			 */
			preloadMedia : function ( index ) {
				var $this = this, 
				src = null;

				if ( elements[index] !== undefined )
					src = elements[index].href;

				if ( ! $this.isVideo( src ) ) {
					setTimeout( function() {
						$this.openMedia( index );
					}, 1000);
				} else {
					$this.openMedia( index );
				}
			},
			
			/**
			 * Open
			 */
			openMedia : function ( index ) {
				var $this = this, 
					src = null;

				if ( elements[index] !== undefined )
					src = elements[index].href;

				if (index < 0 || index >= elements.length) {
					return false;
				}

				if ( ! $this.isVideo( src ) ) {
					$this.loadMedia( src, function() {
						$( '#swipebox-slider .slide' ).eq( index ).html( this );
					} );
				} else {
					$( '#swipebox-slider .slide' ).eq( index ).html( $this.getVideo( src ) );
				}
				
			},

			/**
			 * Set link title attribute as caption
			 */
			setTitle : function ( index, isFirst ) {
				var title = null;
				var redes = "<div><!--Facebook--><div class='fb-share-button' data-type='button_count' data-width='120'></div>";
					redes += "<div><!--Twitter--><a id='twitter' href='https://twitter.com/share' class='twitter-share-button' data-text='#telemedellin' data-lang='es'>Twittear</a></div>";
					redes += "<div><!--G+--><div class='g-plusone' data-size='medium'></div></div>";
					redes += "<div><!--Pinterest--><a href='//pinterest.com/pin/create/button/' data-pin-do='buttonBookmark' ><img src='//assets.pinterest.com/images/pidgets/pin_it_button.png' /></a><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = '//connect.facebook.net/es_LA/all.js#xfbml=1&appId=26028648916';fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script><script type='text/javascript'>window.___gcfg = {lang: 'es'};(function() {var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;po.src = 'https://apis.google.com/js/plusone.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);})();FB.XFBML.parse();window.twttr.widgets.load();</script><script type='text/javascript' src='//assets.pinterest.com/js/pinit.js'></script></div></div>";

				$( '#swipebox-caption' ).empty();

				if ( elements[index] !== undefined )
					title = elements[index].title + redes;
				
				if ( title ) {
					$( '#swipebox-caption' ).append( title );
				}
			},

			/**
			 * Check if the URL is a video
			 */
			isVideo : function ( src ) {

				if ( src ) {
					if ( src.match( /youtube\.com\/watch\?v=([a-zA-Z0-9\-_]+)/) || src.match( /vimeo\.com\/([0-9]*)/ ) || src.match( /youtu\.be\/([a-zA-Z0-9\-_]+)/ ) ) {
						return true;
					}
				}
					
			},

			/**
			 * Get video iframe code from URL
			 */
			getVideo : function( url ) {
				var iframe = '';
				var output = '';
				var youtubeUrl = url.match( /watch\?v=([a-zA-Z0-9\-_]+)/ );
				var youtubeShortUrl = url.match(/youtu\.be\/([a-zA-Z0-9\-_]+)/);
				var vimeoUrl = url.match( /vimeo\.com\/([0-9]*)/ );
				if ( youtubeUrl || youtubeShortUrl) {
					if ( youtubeShortUrl ) {
						youtubeUrl = youtubeShortUrl;
					}
					iframe = '<iframe width="560" height="315" src="//www.youtube.com/embed/' + youtubeUrl[1] + '" frameborder="0" allowfullscreen></iframe>';
				
				} else if ( vimeoUrl ) {

					iframe = '<iframe width="560" height="315"  src="//player.vimeo.com/video/' + vimeoUrl[1] + '?byline=0&amp;portrait=0&amp;color='+plugin.settings.vimeoColor+'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
				
				}

				return '<div class="swipebox-video-container" style="max-width:' + plugin.settings.videomaxWidth + 'px"><div class="swipebox-video">'+iframe+'</div></div>';
			},
			
			/**
			 * Load image
			 */
			loadMedia : function ( src, callback ) {
				if ( ! this.isVideo( src ) ) {
					var img = $( '<img>' ).on( 'load', function() {
						callback.call( img );
					} );
					
					img.attr( 'src', src );
				}	
			},
			
			/**
			 * Get next slide
			 */
			getNext : function () {
				var $this = this;
				index = $( '#swipebox-slider .slide' ).index( $( '#swipebox-slider .slide.current' ) );
				if ( index+1 < elements.length ) {
					index++;
					$this.setSlide( index );
					$this.preloadMedia( index+1 );
				
				} else {
					
					$( '#swipebox-slider' ).addClass( 'rightSpring' );
					setTimeout( function() {
						$( '#swipebox-slider' ).removeClass( 'rightSpring' );
					}, 500 );
				}
			},
			
			/**
			 * Get previous slide
			 */
			getPrev : function () {
				index = $( '#swipebox-slider .slide' ).index( $( '#swipebox-slider .slide.current' ) );
				if ( index > 0 ) {
					index--;
					this.setSlide( index );
					this.preloadMedia( index-1 );
				}
				else {
					
					$( '#swipebox-slider' ).addClass( 'leftSpring' );
					setTimeout( function() {
						$( '#swipebox-slider' ).removeClass( 'leftSpring' );
					}, 500 );
				}
			},

			/**
			 * Close
			 */
			closeSlide : function () {
				$( 'html' ).removeClass( 'swipebox-html' );
				$( 'html' ).removeClass( 'swipebox-touch' );
				$( window ).trigger( 'resize' );
				this.destroy();
			},

			/**
			 * Destroy the whole thing
			 */
			destroy : function () {
				$( window ).unbind( 'keyup' );
				$( 'body' ).unbind( 'touchstart' );
				$( 'body' ).unbind( 'touchmove' );
				$( 'body' ).unbind( 'touchend' );
				$( '#swipebox-slider' ).unbind();
				$( '#swipebox-overlay' ).remove();
				if ( ! $.isArray( elem ) )
					elem.removeData( '_swipebox' );
				if ( this.target )
					this.target.trigger( 'swipebox-destroy' );
				$.swipebox.isOpen = false;
				if ( plugin.settings.afterClose ) 
					plugin.settings.afterClose();
			}

		};

		plugin.init();
		
	};

	$.fn.swipebox = function( options ) {

		if ( ! $.data( this, '_swipebox' ) ) {
			var swipebox = new $.swipebox( this, options );
			this.data( '_swipebox', swipebox );
		}
		return this.data( '_swipebox' );
	
	};

}( window, document, jQuery ) );
