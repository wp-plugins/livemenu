/**
* ERA Framework
*
* An Awesome Wordpress Framework, created by codezag
*
* @author    Sabri Taieb ( codezag )
* @copyright Copyright (c) Sabri Taieb
* @link    http://vamospace.com
* @since   Version 1.0.0
* @package   era_framework
*
**/

/**
* INFO
*
* Helpers Methods & Actions
*
* Table of Content:
* 1) Prepare Data_string for ajax use
* 2) Upload
* 3) Reset Upload
*
**/


(function($) {

   
   /**
    * set $EraAPI object if is not Loaded
   **/
   if( typeof $EraAPI == 'undefined' ) $EraAPI = {};


   /**
    * Global Era Function: return the data_string for the ajax use!
    * @since 1.0
    * @param $container_id string : id/class of the html container to search in
   **/
   $EraAPI.set_ajax_data_string = function( $container_id ){

      var $data_string = '';

      jQuery($container_id).find('.era-op-wrap').each(function(){
         var $self   = $(this),
            $name    = '',
            $val     = '';

         // input/colorpicker/upload/multiple_upload
         if( $self.is('.era-op-input')
            || $self.is('.era-op-textarea')
            || $self.is('.era-op-colorpicker')
            || $self.is('.era-op-upload')
            || $self.is('.era-op-uploadmultiple')
         ){
            $name = $self.find('.era-op').attr('name');
            $val  = $self.find('.era-op').val();
         }

         // Select
         else if( $self.is('.era-op-select') ){
            $name = $self.find('.era-op').attr('name');
            $val  = $self.find('.era-op option:selected').val();
         }

         // imgselect/radio
         else if( $self.is('.era-op-imgselect') || $self.is('.era-op-radio') ){
            $name = $self.find('.era-op:checked').attr('name');
            $val  = $self.find('.era-op:checked').val();
         }

         // checkbox/enable
         else if( $self.is('.era-op-checkbox') || $self.is('.era-op-enable') ){
            $name = $self.find('.era-op').attr('name');
            $val = $self.find('.era-op');
            if( $val.is(':checked') )
               $val = 'on';
            else
               $val = 'off';
         }

         $data_string = $data_string + '_-_eraAND-_-' + $name + '_-_eraEQAUL-_-' + encodeURIComponent( $val );
      });

      $data_string = $data_string.substring(12);
      return $data_string;
   };


   /**
    * Global Function: Upload File / Multiple Files
    * @since 1.0
    * @param $obj object : config
   **/
   $EraAPI.wp_media_upload = function( $obj ){

      // Media Uploader
      var $frame = wp.media({
         title     : $obj.frame_title,
         button    : { text : $obj.button_title },
         multiple  : $obj.multiple_upload,
         library   : { type : $obj.library_type /* ex : image,audio,video */ },
      });

      // When Close Get Selected and add them to the input
      $frame.on( 'select', function(){
         
         // get selections and save to hidden input plus other AJAX stuff etc.
         var $selection    = $frame.state().get('selection');

         // Multiple Upload
         if( $obj.multiple_upload ){
            
            var $gallery_ids  = new Array();
            var $gallery_urls = new Array();
            var $counter      = 0;

            $selection.each( function( $attachment ) {
               $attachment   = JSON.stringify( $attachment );
               $attachment   = JSON.parse( $attachment );
               $gallery_ids[  $counter ]  = $attachment.id;
               $gallery_urls[ $counter ]  = $attachment.url;
               $counter++;
            });

            $obj.callback({
               counter : $counter,
               ids     : $gallery_ids,
               urls    : $gallery_urls,
            });

         }

         // Single Upload
         else{
            
            var $attachment   = JSON.stringify( $selection.first() );
            $attachment       = JSON.parse( $attachment );

            $obj.callback({
               url : $attachment.url
            });

         }

      });


      // When open re-select the items on the media uploader
      // just for multiple upload
      $frame.on('open',function(){
         
         // Multiple Upload
         if( $obj.multiple_upload ){

            //Preselect attachements from my hidden input
            var $selection  = $frame.state().get('selection');
            var $ids        = $obj.older_ids;
            var $attachment;

            $ids.forEach( function( id ){
               $attachment = wp.media.attachment(id);
               $attachment.fetch();
               $selection.add( $attachment ? [ $attachment ] : [] );
            });

         }

      });


      // Open WP Media Uploader
      $frame.open();

   };


   /**
    * Global Era Function: set the box content height
    * @since 1.0
    * @param $container_id string : id/class of the box
   **/
   $EraAPI.set_box_content_height = function( $container_id ){

      var $self = jQuery($container_id);
      $self.find('> .era-box-content').height( $self.height() - $self.find('> .era-box-header').height() );

   };


   /**
    * Upload file or multiple files
    * @since 1.0
   **/
   $(document).on('click','.era-btn-wpmedia-open',function(){
      
      var $this           = jQuery(this),
      $parent             = jQuery(this).parent(),
      $multiple           = $this.attr('data-multiple'),
      $library_type       = $this.attr('data-library_type'),
      $button_text        = $this.attr('data-button_text'),
      $frame_title        = $this.attr('data-button_text');

      // Multiple
      if( $multiple == true || $multiple == 'true' || $multiple == 1 || $multiple == '1' ){

         // Media Uploader Function
         $EraAPI.wp_media_upload({
            frame_title       : $frame_title,
            button_title      : $button_text,
            multiple_upload   : true,
            library_type      : $library_type,
            older_ids         : jQuery( 'input', $parent ).val().split(','),
            callback          : function( $obj ){

               if( $obj.counter > 0 ){

                  // remove older images
                  $parent.find('img').remove();

                  //Store ids in my hidden input
                  var $ids = $obj.ids.join(",");
                  if( $ids.charAt(0) == ',' ) $ids = $ids.substr(1);

                  jQuery( 'input[type="hidden"]', $parent ).val( $ids );

                  // Preview the images
                  for( var $i = 0; $i < $obj.counter ; $i++ ){
                     $parent.append( '<img src="' +$obj.urls[$i]+ '" >' );
                  }

               }

            }// End Callback
         });

      }

      else{

         // Media Uploader Function
         $EraAPI.wp_media_upload({
            frame_title       : $frame_title,
            button_title      : $button_text,
            multiple_upload   : false,
            library_type      : $library_type,
            callback          : function( $obj ){

               $( 'input[type="text"]', $parent ).val( $obj.url );
               $parent.find('img').remove();
               $parent.append('<img src="'+$obj.url+'" >');

            }// End Callback
         });
      }

      return false;
   
   });


   /**
    * Reset WP Media Upload
    * @since 1.0
   **/
   $(document).on('click','.era-btn-wpmedia-reset',function(){
      var $this               = jQuery(this),
      $parent             = jQuery(this).parent();

      $parent.find('img').remove();
      $parent.find('input[type="hidden"]').val('');

      return false;
   });


   /**
    * Enable ColorPicker Plugin
    * @since 1.0
   **/
   jQuery('.era-op-colorpicker input').spectrum({
      showInput: true,
      showAlpha: true,
      preferredFormat: "rgb",
   });


   /**
    * Background Options
    * @since 1.0
   **/
   jQuery(document).on('change','.era-op-background .era-op-background-type',function(){
      var $self = $(this);

      // Hide All Types Containers
      $self.parents('.era-op-background')
         .find('.era-op-bg-type-color, .era-op-bg-type-image, .era-op-bg-type-gradient').addClass('hidden');

      // Show Selected Type
      $self.parents('.era-op-background')
         .find( '.era-op-bg-type-' + $self.find(':selected').val() )
         .removeClass('hidden');
   });


})(jQuery);