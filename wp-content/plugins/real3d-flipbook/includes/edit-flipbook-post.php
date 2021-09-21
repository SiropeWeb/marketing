<?php 
   if ( ! defined( 'ABSPATH' ) ) {
       exit; // Exit if accessed directly
   }

   $post_id = get_the_ID();
   $current_id = get_post_meta($post_id, 'flipbook_id', true);

   if(!$current_id){

      $highest_id = 0;

      $real3dflipbooks_ids = get_option('real3dflipbooks_ids');

      if($real3dflipbooks_ids){
         foreach ($real3dflipbooks_ids as $id) {
            if((int)$id > $highest_id) {
               $highest_id = (int)$id;
            }
         }

         $current_id = $highest_id + 1;
      }else{
         $current_id = 1;
      }
      
   }

   // trace("current_id : " . $current_id);

   ?>
   
   <div id='real3dflipbook-admin' style="display:none;">

   <input type="hidden" name="id" value="<?php echo($current_id);?>">
   <input type="hidden" name="bookId" value="<?php echo($current_id);?>">

         <div>
            <h2 id="r3d-tabs" class="nav-tab-wrapper wp-clearfix">
               <a href="#" class="nav-tab" data-tab="tab-pages">Pages</a>
               <a href="#" class="nav-tab" data-tab="tab-toc">Table of Contents</a>
               <a href="#" class="nav-tab" data-tab="tab-general">General</a>
               <a href="#" class="nav-tab" data-tab="tab-lightbox">Lightbox</a>
               <a href="#" class="nav-tab" data-tab="tab-webgl">WebGL</a>
               <a href="#" class="nav-tab" data-tab="tab-mobile">Mobile</a>
               <a href="#" class="nav-tab" data-tab="tab-ui">UI</a>
               <a href="#" class="nav-tab" data-tab="tab-menu">Menu Buttons</a>
               <a href="#" class="nav-tab" data-tab="tab-translate">Translate</a>
               <!-- <a href="#" class="nav-tab" data-tab="tab-info">Book Info</a> -->
            </h2>
            <div id="tab-pages" style="display:none;">

               <p><?php _e("Select PDF or images from media library, or enter PDF URL.", "r3dfb") ?></p>

               <table class="form-table">
                  <tbody>
                     <tr>
                        <th><label><?php _e("PDF Flipbook", "r3dfb") ?></label></th>
                        <td>
                           <input type='text' class='regular-text' name="pdfUrl" id='r3d-pdf-url' placeholder="PDF URL">
                           <button class='button-secondary add-pdf-pages-button' id='r3d-select-pdf'><?php _e( "Select PDF", "r3dfb" ); ?></button>
                           <p class="description"><?php _e("PDF needs to be on the same domain or CORS needs to be enabled.", "r3dfb") ?></p>
                        </td>
                     </tr>
                     <tr>
                        <th>
                           <strong><label>JPG Flipbook</label></strong>
                        </th>
                        <td>
                           <button class='button-secondary add-jpg-pages-button' id='r3d-select-images'><?php _e( "Select images", "r3dfb" ); ?></button>
                           <p class="description"><?php _e("Create flipbook from images. Multiple file upload is enabled.", "r3dfb") ?></p>
                        </td>
                     </tr>
                  </tbody>
               </table>

               <div>
                  <ul id="pages-container" tabindex="-1" class="attachments ui-sortable"></ul>
                  <span class="delete-pages-button">Delete all pages</span>
               </div>
            </div>

            <div id="tab-toc" style="display:none;">
               <p class="description">
               <p>Create custom Table of Contents. This overrides default PDF outline or table of contents created by page titles.</p>
               </p>
               <p>
                  <a class="add-toc-item button-primary" href="#">Add item</a>
                  <a href="#" type="button" class="button-link toc-delete-all">Delete all</a>
               </p>
               <table class="form-table" id="flipbook-toc-options">
                  <tbody></tbody>
               </table>
               <div id="toc-items" tabindex="-1" class="attachments ui-sortable"></div>
            </div>
            <div id="tab-general" style="display:none;">
               <table class="form-table" id="flipbook-general-options">
                  <tbody></tbody>
               </table>
            </div>
            <div id="tab-normal"  style="display:none;">
               <table class="form-table" id="flipbook-normal-options">
                  <tbody></tbody>
               </table>
            </div>
            <div id="tab-mobile"  style="display:none;">
               <p class="description">
               <p>Override settings for mobile devices (use different view mode, smaller textures ect)</p>
               </p>
               <table class="form-table" id="flipbook-mobile-options">
                  <tbody></tbody>
               </table>
            </div>
            <div id="tab-lightbox"  style="display:none;">
               <table class="form-table" id="flipbook-lightbox-options">
                  <tbody></tbody>
               </table>
            </div>
            <div id="tab-webgl"  style="display:none;">
               <table class="form-table" id="flipbook-webgl-options">
                  <tbody></tbody>
               </table>
            </div>
            <div id="tab-ui"  style="display:none;">
               <!-- <div id="poststuff"> -->
                  <div class="meta-box-sortables">

                     <table class="form-table" id="flipbook-ui-options">
                        <tbody></tbody>
                     </table>
                     <h3>Advanced settings</h3>
                     <p>Override layout and skin settings</p>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Skin</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Skin</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-skin-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Flipbook background</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Flipbook background</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-bg-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Top Menu</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Top Menu</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-menu-bar-2-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Bottom Menu</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Bottom Menu</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-menu-bar-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Buttons</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Buttons</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-menu-buttons-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>

                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Floating buttons (on transparent menu)</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Floating buttons (on transparent menu)</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-menu-floating-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>

                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Side navigation buttons</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Side navigation buttons</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-side-buttons-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>

                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Close lightbox button</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Close lightbox button</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-close-button-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>


                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Sidebar</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Sidebar</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-sidebar-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                  </div>
               <!-- </div> -->
            </div>
            <div id="tab-translate"  style="display:none;">
               <table class="form-table" id="flipbook-translate-options">
                  <tbody></tbody>
               </table>
            </div>

            <!-- <div id="tab-info"  style="display:none;">
               <table class="form-table" id="flipbook-info-options">
                  <tbody>
                     
                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_title">Title</label></th>
                        <td><input name="book_title" id="book_title" type="text" value="<?php _e(get_post_meta($post_id, 'book_title', true));?>" size="40" aria-required="true">
                        <p class="description">Book title.</p></td>
                     </tr>
                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_summary">Summary</label></th>
                        <td><textarea name="book_summary" id="book_summary" type="text" value="" rows="5" cols="50" aria-required="true"><?php _e(get_post_meta($post_id, 'book_summary', true));?></textarea>
                        <p class="description">Book summary.</p></td>
                     </tr>
                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_author">Author</label></th>
                        <td><input name="book_author" id="book_author" type="text" value="<?php _e(get_post_meta($post_id, 'book_author', true));?>" size="40" aria-required="true">
                        <p class="description">Book author.</p></td>
                     </tr>
                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_notation">Notation</label></th>
                        <td><input name="book_notation" id="book_notation" type="text" value="<?php _e(get_post_meta($post_id, 'book_notation', true));?>" size="40" aria-required="true">
                        <p class="description">Book notation.</p></td>
                     </tr>
                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_publisher">Publisher</label></th>
                        <td><input name="book_publisher" id="book_publisher" type="text" value="<?php _e(get_post_meta($post_id, 'book_publisher', true));?>" size="40" aria-required="true">
                        <p class="description">Book publisher.</p></td>
                     </tr>
                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_isbn">ISBN</label></th>
                        <td><input name="book_isbn" id="book_isbn" type="text" value="<?php _e(get_post_meta($post_id, 'book_isbn', true));?>" size="40" aria-required="true">
                        <p class="description">Book ISBN.</p></td>
                     </tr>
                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_subject">Subject</label></th>
                        <td><input name="book_subject" id="book_subject" type="text" value="<?php _e(get_post_meta($post_id, 'book_subject', true));?>" size="40" aria-required="true">
                        <p class="description">Book subject.</p></td>
                     </tr>
                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_pages">Number of pages</label></th>
                        <td><input name="book_pages" id="book_pages" type="text" value="<?php _e(get_post_meta($post_id, 'book_pages', true));?>" size="40" aria-required="true">
                        <p class="description">Book number of pages.</p></td>
                     </tr>

                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_size">Size</label></th>
                        <td><input name="book_size" id="book_size" type="text" value="<?php _e(get_post_meta($post_id, 'book_size', true));?>" size="40" aria-required="true">
                        <p class="description">Book size.</p></td>
                     </tr>

                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_date">Publication date</label></th>
                        <td><input name="book_date" id="book_date" type="text" value="<?php _e(get_post_meta($post_id, 'book_date', true));?>" size="40" aria-required="true">
                        <p class="description">Book publication date.</p></td>
                     </tr>

                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_date">Language</label></th>
                        <td><input name="book_language" id="book_language" type="text" value="<?php _e(get_post_meta($post_id, 'book_language', true));?>" size="40" aria-required="true">
                        <p class="description">Book language.</p></td>
                     </tr>

                     <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="book_date">Format</label></th>
                        <td><input name="book_format" id="book_format" type="text" value="<?php _e(get_post_meta($post_id, 'book_format', true));?>" size="40" aria-required="true">
                        <p class="description">Book format.</p></td>
                     </tr>


                  </tbody>
               </table>
            </div> -->
            <div id="tab-menu"  style="display:none;">
               <!-- <div id="poststuff"> -->
                  <div class="meta-box-sortables">
                     <h3>Menu buttons</h3>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Current page</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Current page</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-currentPage-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: First page</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>First page</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnFirst-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Previous page</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Previous page</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnPrev-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Next page</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Next page</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnNext-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Last page</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Last page</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnLast-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Autoplay</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Autoplay</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnAutoplay-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Zoom in</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Zoom in</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnZoomIn-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Zoom out</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Zoom out</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnZoomOut-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Table of Contents</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Table of Contents</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnToc-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Thumbnails</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Thumbnails</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnThumbs-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Share</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Share</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnShare-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Print</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Print</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnPrint-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Download pages</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Download pages</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnDownloadPages-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Download PDF</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Download PDF</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnDownloadPdf-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Sound</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Sound</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnSound-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Fullscreen</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Fullscreen</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnExpand-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Select tool</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Select tool</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnSelect-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Search</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Search</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnSearch-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Bookmark</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Bookmark</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-btnBookmark-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>

                     <h3>Social share buttons</h3>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Share on Google plus</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Share on Google plus</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-google_plus-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Share on Twitter</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Share on Twitter</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-twitter-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Share on Facebook</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Share on Facebook</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-facebook-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Share on Pinterest</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Share on Pinterest</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-pinterest-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                     <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Share by Email</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>Share by Email</span></h2>
                        <div class="inside">
                           <table class="form-table" id="flipbook-email-options">
                              <tbody></tbody>
                           </table>
                           <div class="clear"></div>
                        </div>
                     </div>
                  </div>
            </div>
         </div>
        <!--  <p id="r3d-save" class="submit">
            <span class="spinner"></span>
            <input type="submit" name="btbsubmit" class="alignright button save-button button-primary" value="Update" style="display:none;">
            <input type="submit" name="btbsubmit" class="alignright button create-button button-primary" value="Publish" style="display:none;">
            <a id="r3d-preview" href="#" class="alignright flipbook-preview button save-button button-secondary">Preview</a>
            <a href="#" class="alignright flipbook-reset-defaults button button-secondary">Reset all settings</a>
         </p> -->
         <!-- <div id="r3d-save-holder" style="display: none;" ></div> -->
      <!-- </form> -->
   </div>
<!-- </div> -->

<div tabindex="0" class="media-modal wp-core-ui upload-php" id="edit-page-modal" style="display: none;">

   <button type="button" class="media-modal-close STX-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>
    <div class="media-modal-content STX-modal-content">
        <div class="edit-attachment-frame mode-select hide-menu hide-router">

            <div class="edit-media-header">
               <button class="left dashicons"><span class="screen-reader-text">Edit previous media item</span></button>
               <button class="right dashicons"><span class="screen-reader-text">Edit next media item</span></button>
               <button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close dialog</span></span></button>
            </div>

            <div class="media-frame-title STX-modal-title"><h1>Edit page</h1></div>

            <div class="media-frame-content STX-modal-frame-content">

               <div class="attachment-details save-ready">
                  <div class="attachment-media-view portrait">
                     <h2 class="screen-reader-text">Attachment Preview</h2>
                     <div class="thumbnail thumbnail-image">
                        
                        <img id="edit-page-img" class="details-image" draggable="false" alt="">
                        
                        <div class="attachment-actions">
                           
                           <button type="button" class="button edit-attachment replace-page">Replace Image</button>
                           
                        </div>
                     </div>
                  </div>
                  <div class="attachment-info">

                     <div class="settings">
                        
                        <span class="setting" data-setting="title">
                           <label for="edit-page-title" class="name">Title</label>
                           <input type="text" id="edit-page-title" placeholder="Page title (for Table of Content)">
                        </span>
                                    
                        <span class="setting" data-setting="html-content">
                           <label for="edit-page-html-content" class="name">HTML Content</label>
                           <textarea id="edit-page-html-content" placeholder="Add any HTML content to page, set style and position with inline CSS" style="height: 300px;"></textarea>
                        </span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="media-modal-backdrop" style="display: none;"></div>

<?php 

wp_enqueue_media();
add_thickbox(); 

wp_enqueue_script( "real3d-flipbook-iscroll" ); 
wp_enqueue_script( "real3d-flipbook-pdfjs" ); 
wp_enqueue_script( "real3d-flipbook-pdfworkerjs" ); 
wp_enqueue_script( "real3d-flipbook-pdfservice" ); 
wp_enqueue_script( "real3d-flipbook-threejs" ); 
wp_enqueue_script( "real3d-flipbook-book3" );
wp_enqueue_script( "real3d-flipbook-bookswipe" );
wp_enqueue_script( "real3d-flipbook-webgl" );
wp_enqueue_script( "real3d_flipbook" );
wp_enqueue_style( 'real3d-flipbook-style' ); 
wp_enqueue_style( 'real3d-flipbook-font-awesome' ); 

wp_enqueue_script( 'alpha-color-picker' );
wp_enqueue_script( 'real3d-flipbook-edit-post' ); 
wp_enqueue_style( 'alpha-color-picker' );
wp_enqueue_style( 'real3d-flipbook-admin' ); 

$ajax_nonce = wp_create_nonce( "saving-real3d-flipbook");

$flipbook_id = get_post_meta($post_id, 'flipbook_id', true);

if($flipbook_id){

   $flipbook = get_option("real3dflipbook_" . $flipbook_id);

}else{
   $flipbook = array(
      'id' => $current_id
   );
}

$flipbook['security'] = $ajax_nonce;

$flipbook_global = get_option("real3dflipbook_global");

$flipbook_global_defaults = r3dfb_getDefaults();

$flipbook['globals'] = array_merge($flipbook_global_defaults, $flipbook_global);
wp_localize_script( 'real3d-flipbook-edit-post', 'flipbook', array(json_encode($flipbook)) );