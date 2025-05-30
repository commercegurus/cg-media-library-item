/**
 * Settings page JavaScript for CG Media Library Item
 * @param $
 */
( function ( $ ) {
	'use strict';

	$( function () {
		// Initialize color pickers with live preview
		$( '.cg-color-picker' ).wpColorPicker( {
			change( event, ui ) {
				// Get the color value
				const color = ui.color.toString();

				// Get the target element from data attribute
				const target = $( this ).data( 'target' );

				// Update the preview
				updateColorPreview( target, color );
			},
			clear( event ) {
				// Get the target element from data attribute
				const target = $( this ).data( 'target' );

				// Get the default color
				const defaultColor = $( this ).data( 'default-color' );

				// Update the preview with default color
				updateColorPreview( target, defaultColor );
			},
		} );

		// Function to update color preview
		function updateColorPreview( target, color ) {
			const selector =
				'#cg-media-item-preview' +
				( cgMediaLibrarySettings.colorMap[ target ] !== '.media-item'
					? ' ' + cgMediaLibrarySettings.colorMap[ target ]
					: '' );
			const property = cgMediaLibrarySettings.cssProperties[ target ];

			// Special case for hover state
			if ( selector.includes( ':hover' ) ) {
				// Create a style element for hover state if it doesn't exist
				if ( $( '#cg-hover-style' ).length === 0 ) {
					$( 'head' ).append( '<style id="cg-hover-style"></style>' );
				}

				// Update the hover style
				$( '#cg-hover-style' ).text(
					selector +
						' { ' +
						property +
						': ' +
						color +
						' !important; }'
				);

				// Also update the download button to show the hover effect when hovered in the preview
				$( '#cg-media-item-preview .media-item__download-btn' )
					.on( 'mouseenter', function () {
						$( this ).css( property, color );
					} )
					.on( 'mouseleave', function () {
						$( this ).css( property, '' );
					} );
			} else {
				// Update the regular style
				$( selector ).css( property, color );
			}
		}

		// Typography live preview
		$( '.cg-typography-field select, .cg-typography-field input' ).on(
			'change input',
			function () {
				const id = $( this ).attr( 'id' );
				const value = $( this ).val();

				// Extract the field type from the ID
				const fieldType = id.replace( 'cg_typography_', '' );

				// Update the preview
				updateTypographyPreview( fieldType, value );
			}
		);

		// Function to update typography preview
		function updateTypographyPreview( fieldType, value ) {
			// Skip if this field type is not in our mapping
			if ( ! cgMediaLibrarySettings.typographyMap[ fieldType ] ) {
				return;
			}

			const selector =
				'#cg-media-item-preview ' +
				cgMediaLibrarySettings.typographyMap[ fieldType ];
			const property =
				cgMediaLibrarySettings.typographyProperties[ fieldType ];

			// Special handling for font-family
			if ( property === 'font-family' && value === 'inherit' ) {
				value = '';
			}

			// Update the preview
			$( selector ).css( property, value );
		}

		// Add hover effect to the download button in preview
		$( '#cg-media-item-preview .media-item__download-btn' ).hover(
			function () {
				// Get the hover color from the color picker
				const hoverColor = $(
					'#cg_media_library_item_download_btn_hover_color'
				).val();
				$( this ).css( 'color', hoverColor );
			},
			function () {
				// Restore the normal color
				const normalColor = $(
					'#cg_media_library_item_download_btn_color'
				).val();
				$( this ).css( 'color', normalColor );
			}
		);

		// Reset colors button
		$( '#cg-reset-colors' ).on( 'click', function () {
			const $button = $( this );
			const $spinner = $button.next( '.spinner' );
			const $message = $spinner.next( '.cg-reset-message' );

			// Show spinner
			$spinner.css( 'visibility', 'visible' );

			// Reset each color picker to its default value
			$( '.cg-color-picker' ).each( function () {
				const $picker = $( this );
				const defaultColor = $picker.data( 'default-color' );
				const target = $picker.data( 'target' );

				// Set the value
				$picker
					.val( defaultColor )
					.wpColorPicker( 'color', defaultColor );

				// Update preview
				updateColorPreview( target, defaultColor );
			} );

			// Hide spinner and show success message
			setTimeout( function () {
				$spinner.css( 'visibility', 'hidden' );
				$message
					.text(
						'Colors reset to defaults. Click Save Changes to apply.'
					)
					.fadeIn()
					.delay( 3000 )
					.fadeOut();
			}, 500 );
		} );

		// Reset typography button
		$( '#cg-reset-typography' ).on( 'click', function () {
			const $button = $( this );
			const $spinner = $button.next( '.spinner' );
			const $message = $spinner.next( '.cg-reset-message' );

			// Show spinner
			$spinner.css( 'visibility', 'visible' );

			// Reset each typography field to its default value
			$( '.cg-typography-field select, .cg-typography-field input' ).each(
				function () {
					const $field = $( this );
					const id = $field.attr( 'id' );
					const fieldType = id.replace( 'cg_typography_', '' );

					// Get default value from data attribute
					const defaultValue = $field.data( 'default-value' );

					if ( defaultValue !== undefined ) {
						// Set the value
						$field.val( defaultValue );

						// Update preview
						updateTypographyPreview( fieldType, defaultValue );
					}
				}
			);

			// Hide spinner and show success message
			setTimeout( function () {
				$spinner.css( 'visibility', 'hidden' );
				$message
					.text(
						'Typography reset to defaults. Click Save Changes to apply.'
					)
					.fadeIn()
					.delay( 3000 )
					.fadeOut();
			}, 500 );
		} );
	} );
} )( jQuery );
