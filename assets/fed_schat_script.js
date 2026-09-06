/**
 * Frontend Dashboard Social Chat - Modern Scripts
 * Version 3.0.0
 */

jQuery( document ).ready( function ( $ ) {
	'use strict';

	var $body = $( 'body' );

	/* ==========================================================================
	   1. Frontend Floating WhatsApp Chat Widget
	   ========================================================================== */

	// Open / Toggle WhatsApp Chat Box
	$body.on( 'click', '#fed_wa_trigger, .fed_wa_floating_trigger, .fed_wa_floating_btn', function ( e ) {
		e.preventDefault();
		e.stopPropagation();

		var $container = $( this ).closest( '#fed_wa_container' );
		var $box       = $container.find( '#fed_wa_box' );
		var $prompt    = $container.find( '#fed_wa_prompt_bubble' );

		if ( $box.hasClass( 'fed_hide' ) ) {
			$box.removeClass( 'fed_hide' );
			$prompt.addClass( 'fed_hide' );
		} else {
			$box.addClass( 'fed_hide' );
			$prompt.removeClass( 'fed_hide' );
		}
	} );

	// Close WhatsApp Chat Box via Header Close Button
	$body.on( 'click', '#fed_wa_box_close, .fed_wa_box_close, .fed_wa_close', function ( e ) {
		e.preventDefault();
		e.stopPropagation();

		var $container = $( this ).closest( '#fed_wa_container' );
		$container.find( '#fed_wa_box' ).addClass( 'fed_hide' );
		$container.find( '#fed_wa_prompt_bubble' ).removeClass( 'fed_hide' );
	} );

	// Close when clicking outside of the chat widget
	$( document ).on( 'click', function ( e ) {
		if ( ! $( e.target ).closest( '#fed_wa_container' ).length ) {
			var $box = $( '#fed_wa_box' );
			if ( $box.length && ! $box.hasClass( 'fed_hide' ) ) {
				$box.addClass( 'fed_hide' );
				$( '#fed_wa_prompt_bubble' ).removeClass( 'fed_hide' );
			}
		}
	} );

	// Prevent click inside chat box from closing it
	$body.on( 'click', '#fed_wa_box', function ( e ) {
		e.stopPropagation();
	} );

	/* ==========================================================================
	   2. Admin Support Agents Management
	   ========================================================================== */

	// Add New Support Agent via AJAX
	$body.on( 'click', '#fed_whatsapp_add_new_user_button', function ( e ) {
		e.preventDefault();
		var $button = $( this );
		var ajaxUrl = $button.data( 'url' );

		if ( ! ajaxUrl ) {
			return;
		}

		var originalText = $button.html();
		$button.prop( 'disabled', true ).html( '<i class="fas fa-spinner fa-spin"></i> Adding...' );
		$( '.preview-area' ).removeClass( 'hide' );

		$.ajax( {
			type: 'POST',
			url: ajaxUrl,
			data: {},
			success: function ( response ) {
				$( '.preview-area' ).addClass( 'hide' );
				$button.prop( 'disabled', false ).html( originalText );

				if ( response && response.success && response.data && response.data.html ) {
					var $list = $button.closest( 'form' ).find( '.fed_whatsapp_users_list' );
					var $newRow = $( response.data.html ).hide();
					$list.prepend( $newRow );
					$newRow.fadeIn( 250 );

					// Hide empty state and show submit button
					$( '#fed_whatsapp_empty_state' ).hide();
					$( '#fed_whatsapp_add_user_form_submit' ).removeClass( 'hide' );
				}
			},
			error: function () {
				$( '.preview-area' ).addClass( 'hide' );
				$button.prop( 'disabled', false ).html( originalText );
			}
		} );
	} );

	// Delete Support Agent Row
	$body.on( 'click', '.fed_whatsapp_delete_user_form', function ( e ) {
		e.preventDefault();
		var $card = $( this ).closest( '.fed_whatsapp_user_card, .fed_whatsapp_user_list' );

		$card.fadeOut( 200, function () {
			$( this ).remove();

			// If no agents remain, show empty state and hide submit button
			if ( ! $( '.fed_whatsapp_user_list' ).length ) {
				$( '#fed_whatsapp_empty_state' ).fadeIn( 200 );
				$( '#fed_whatsapp_add_user_form_submit' ).addClass( 'hide' );
			}
		} );
	} );

	/* ==========================================================================
	   3. Admin Live Preview & Interactive Controls
	   ========================================================================== */

	// Live input typing sync to live preview window
	$body.on( 'input keyup change', '.fed_schat_live_input', function () {
		var $input    = $( this );
		var targetSel = $input.data( 'target' );
		var value     = $input.val();

		if ( targetSel ) {
			var $target = $( targetSel );
			if ( $target.length ) {
				if ( targetSel === '#fed_schat_preview_body_title' ) {
					$target.html( '<i class="fas fa-bolt" style="margin-right: 4px;"></i> ' + ( value ? $( '<div/>' ).text( value ).html() : '' ) );
					$target.toggle( !! value.trim() );
				} else if ( targetSel === '#fed_schat_preview_footer_title' ) {
					$target.html( '<i class="fas fa-info-circle" style="margin-right: 4px;"></i> ' + ( value ? $( '<div/>' ).text( value ).html() : 'Assistance available 24/7' ) );
				} else {
					$target.text( value || $input.attr( 'placeholder' ) || '' );
				}
			}
		}
	} );

	// Status radio card selection
	$body.on( 'change', '.fed_schat_radio_trigger', function () {
		var selectedVal = $( this ).val();
		$( '.fed_schat_status_card' ).removeClass( 'active' );
		$( this ).closest( 'label' ).find( '.fed_schat_status_card' ).addClass( 'active' );
	} );

} );


