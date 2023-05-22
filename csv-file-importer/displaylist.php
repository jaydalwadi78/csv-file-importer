<?php
 if (isset($_POST['submit'])) {
    $csv_file = $_FILES['csv_file'];
    $csv_to_array = array_map('str_getcsv', file($csv_file['tmp_name']));
    require_once(ABSPATH . 'wp-admin/includes/image.php');
     if( count($csv_to_array) < 1)
        return;
      $i = 0;
  foreach( $csv_to_array as $product_details)
    {
      if($i < 6500){
          $name2 =  $product_details[1];
          $item2 = 'tyre_brand'; 

          $name3 = $product_details[19];
          $item3 = 'tyre_type';

          $name4 = $product_details[8];
          $item4 = 'pa_speed';

          $name5 = $product_details[9];
          $item5 = 'pa_load-spd';

          $cat_defaults = array(          
              'post_title' => $product_details[2] ,
           //   'post_content' => $product_details[1],
              'post_type' => 'product',
              'post_status' => 'publish'
          );

          $attributedata = Array(
               'Speed'=>Array( 
                     'name'=>'Speed', 
                     'value'=>$product_details[8],
                     'is_visible' => '1',
                     'is_taxonomy' => '0'
               ),
               'Load / Spd'=>Array( 
                     'name'=>'Load / Spd', 
                     'value'=>$product_details[9],
                     'is_visible' => '1',
                     'is_taxonomy' => '0'
               )
          );


          $ext = image_type_to_extension(IMAGETYPE_PNG, TRUE);


          if (file_exists("D:/wamp64/www/tyres/wp-content/import/".$product_details[25].$ext)){

              $image_url     ="D:/wamp64/www/tyres/wp-content/import/".$product_details[25].$ext;

              $image_name       = $product_details[25].'_'.time().$ext;

              $upload_dir       = wp_upload_dir(); // Set upload folder
              $image_data       = file_get_contents($image_url); // Get image data

              //  $upload_dir['path']
              $unique_file_name = wp_unique_filename( $upload_dir['path'],$image_name ); // Generate unique namemm
              $filename         = basename( $unique_file_name ); // Create image file name

              // Check folder permission and define file location
              if( wp_mkdir_p( $upload_dir['path'] )) {
                  $file = $upload_dir['path'] . '/'.$filename;
              } else {
                  $file = $upload_dir['basedir'] . '/'.$filename;
              }
              // Create the image  file on the server
              file_put_contents( $file, $image_data );

              // Check image file type
              $wp_filetype = wp_check_filetype( $filename, null );


             // echo "Yes, file exist";
           }
           else{

              $image_url = "D:/wamp64/www/tyres/wp-content/import/NoImage.png";

              $image_name       = 'NoImage'.'_'.time().$ext;


              $upload_dir       = wp_upload_dir(); // Set upload folder
              $image_data       = file_get_contents($image_url); // Get image data

              //  $upload_dir['path']
              $unique_file_name = wp_unique_filename( $upload_dir['path'],$image_name ); // Generate unique namemm
              $filename         = basename( $unique_file_name ); // Create image file name

              // Check folder permission and define file location
              if( wp_mkdir_p( $upload_dir['path'] )) {
                  $file = $upload_dir['path'] . '/'.$filename;
              } else {
                  $file = $upload_dir['basedir'] . '/'.$filename;
              }
              // Create the image  file on the server
              file_put_contents( $file, $image_data );

              // Check image file type
              $wp_filetype = wp_check_filetype( $filename, null );
           }



          $product_name = $product_details[2];
          $customPost = get_page_by_title($product_name, OBJECT, 'product');
          

          if(!$customPost){
            $post_id = $customPost->ID;

             $attachment = array(
              'post_mime_type' => $wp_filetype['type'],
              'post_title'     => sanitize_file_name( $filename ),
              'post_content'   => '',
              'post_status'    => 'inherit'
          );          
        
          $post_id = wp_insert_post($cat_defaults);

          // Create the attachment
              $attach_id = wp_insert_attachment( $attachment, $file, $post_id );


              // Define attachment metadata
              $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

              // Assign metadata to attachment
              wp_update_attachment_metadata( $attach_id, $attach_data );

              // And finally assign featured image to post
              set_post_thumbnail( $post_id, $attach_id );

             // wp_set_object_terms( $post_id, $name,  $item);
              wp_set_object_terms( $post_id, $name2, $item2);
              wp_set_object_terms( $post_id, $name3, $item3);
              wp_set_object_terms( $post_id, $name4, $item4);
              wp_set_object_terms( $post_id, $name5, $item5);

                update_post_meta( $post_id, '_visibility'             , 'visible' );
                update_post_meta( $post_id, '_stock_status'           , 'stock');
                update_post_meta( $post_id, 'total_sales'             , '0' );
                update_post_meta( $post_id, '_downloadable'           , 'no' );
                update_post_meta( $post_id, '_virtual'                , 'yes' );
          //      update_post_meta( $post_id, '_regular_price'          , $product_details[13] );
          //      update_post_meta( $post_id, '_sale_price'             , $product_details[14] );
                update_post_meta( $post_id, '_purchase_note'          , '' );
                update_post_meta( $post_id, '_featured'               , 'yes' );
                update_post_meta( $post_id, '_weight'                 , $product_details[28] );
                update_post_meta( $post_id, '_length'                 , $product_details[7]);
                update_post_meta( $post_id, '_width'                  , $product_details[5] );
                update_post_meta( $post_id, '_height'                 , $product_details[6] );
                update_post_meta( $post_id, '_sku'                    , $product_details['SKU'] );
                update_post_meta( $post_id, '_product_attributes'     , $attributedata);
                update_post_meta( $post_id, '_sale_price_dates_from'  , '' );
                update_post_meta( $post_id, '_sale_price_dates_to'    , '' );
                update_post_meta( $post_id, '_price'                  , $product_details[13] );
                update_post_meta( $post_id, '_sold_individually'      , '' );
                update_post_meta( $post_id, '_manage_stock'           , 'no' );
                update_post_meta( $post_id, '_backorders'             , 'no' );
                update_post_meta( $post_id, '_stock'                  , $product_details[15]); 

          //      update_post_meta( $post_id, 'product_vehicle'         , $product_details[20]);    
                update_post_meta( $post_id, 'tyre_size'               , $product_details[4]);

                update_post_meta( $post_id, 'type'                    , $product_details[3]);
                update_post_meta( $post_id, 'stock_code'              , $product_details[0]);
                update_post_meta( $post_id, 'ipc_code'                , $product_details[16]);
                update_post_meta( $post_id, 'stock_description'       , $product_details[17]);
                update_post_meta( $post_id, 'make_type'               , $product_details[18]);
                update_post_meta( $post_id, 'ean'                     , $product_details[27]);
                update_post_meta( $post_id, 'oe_sidewall'             , $product_details[29]);
                update_post_meta( $post_id, 'oe_fitment'              , $product_details[30]);
                update_post_meta( $post_id, 'remarks'                 , $product_details[31]);
                update_post_meta( $post_id, 'status'                  , $product_details[32]);
                update_post_meta( $post_id, 'runflat'                 , $product_details[10]);
                update_post_meta( $post_id, 'xl'                      , $product_details[11]);
                update_post_meta( $post_id, 'winter'                  , $product_details[12]);
                update_post_meta( $post_id, 'veh_class'               , $product_details[20]);
                update_post_meta( $post_id, 'rolling_res'             , $product_details[21]);
                update_post_meta( $post_id, 'wet_grip'                , $product_details[22]);
                update_post_meta( $post_id, 'noise_class_type'        , $product_details[23]);
                update_post_meta( $post_id, 'noise_performance'       , $product_details[24]);

          }else{
            $post_id = $customPost->ID;

              $attachment = array(
                  'post_mime_type' => $wp_filetype['type'],
                  'post_title'     => sanitize_file_name( $filename ),
                  'post_content'   => '',
                  'post_status'    => 'inherit'
              ); 

              $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
              $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
              wp_update_attachment_metadata( $attach_id, $attach_data );
             
              set_post_thumbnail( $post_id, $attach_id );

            //  wp_set_object_terms( $post_id, $name,  $item);
              wp_set_object_terms( $post_id, $name2, $item2);
              wp_set_object_terms( $post_id, $name3, $item3);
              wp_set_object_terms( $post_id, $name4, $item4);
              wp_set_object_terms( $post_id, $name5, $item5);

                update_post_meta( $post_id, '_visibility'             , 'visible' );
                update_post_meta( $post_id, '_stock_status'           , 'stock');
                update_post_meta( $post_id, 'total_sales'             , '0' );
                update_post_meta( $post_id, '_downloadable'           , 'no' );
                update_post_meta( $post_id, '_virtual'                , 'yes' );
             // update_post_meta( $post_id, '_regular_price'          , $product_details[13] );
             // update_post_meta( $post_id, '_sale_price'             , $product_details[14] );
                update_post_meta( $post_id, '_purchase_note'          , '' );
                update_post_meta( $post_id, '_featured'               , 'yes' );
                update_post_meta( $post_id, '_weight'                 , $product_details[28] );
                update_post_meta( $post_id, '_length'                 , $product_details[7]);
                update_post_meta( $post_id, '_width'                  , $product_details[5] );
                update_post_meta( $post_id, '_height'                 , $product_details[6] );
                update_post_meta( $post_id, '_sku'                    , $product_details['SKU'] );
                update_post_meta( $post_id, '_product_attributes'     , $attributedata);
                update_post_meta( $post_id, '_sale_price_dates_from'  , '' );
                update_post_meta( $post_id, '_sale_price_dates_to'    , '' );
                update_post_meta( $post_id, '_price'                  , $product_details[13] );
                update_post_meta( $post_id, '_sold_individually'      , '' );
                update_post_meta( $post_id, '_manage_stock'           , 'no' );
                update_post_meta( $post_id, '_backorders'             , 'no' );
                update_post_meta( $post_id, '_stock'                  , $product_details[15]); 

           //     update_post_meta( $post_id, 'product_vehicle'         , $product_details[20]);    
                update_post_meta( $post_id, 'tyre_size'               , $product_details[4]);

                update_post_meta( $post_id, 'type'                    , $product_details[3]);
                update_post_meta( $post_id, 'stock_code'              , $product_details[0]);
                update_post_meta( $post_id, 'ipc_code'                , $product_details[16]);
                update_post_meta( $post_id, 'stock_description'       , $product_details[17]);
                update_post_meta( $post_id, 'make_type'               , $product_details[18]);
                update_post_meta( $post_id, 'ean'                     , $product_details[27]);
                update_post_meta( $post_id, 'oe_sidewall'             , $product_details[29]);
                update_post_meta( $post_id, 'oe_fitment'              , $product_details[30]);
                update_post_meta( $post_id, 'remarks'                 , $product_details[31]);
                update_post_meta( $post_id, 'status'                  , $product_details[32]);
                update_post_meta( $post_id, 'runflat'                 , $product_details[10]);
                update_post_meta( $post_id, 'xl'                      , $product_details[11]);
                update_post_meta( $post_id, 'winter'                  , $product_details[12]);
                update_post_meta( $post_id, 'veh_class'               , $product_details[20]);
                update_post_meta( $post_id, 'rolling_res'             , $product_details[21]);
                update_post_meta( $post_id, 'wet_grip'                , $product_details[22]);
                update_post_meta( $post_id, 'noise_class_type'        , $product_details[23]);
                update_post_meta( $post_id, 'noise_performance'       , $product_details[24]);
          }
      }
      else{
        break;
      }
      $i++;
     }
              $slug = 'speed';
              $attribute_name = 'Speed';
              $taxonomy_slug = wc_attribute_taxonomy_name($slug);

              if (taxonomy_exists($taxonomy_slug))
              {
                  return wc_attribute_taxonomy_id_by_name($slug);
              }
              
              //logg("Creating a new Taxonomy! `".$taxonomy_name."` with name/label `".$name."` and slug `".$slug.'`');
              $attribute_id = wc_create_attribute( array(
                  'name'         => $attribute_name,
                  'slug'         => $slug,
                  'type'         => 'select',
                  'order_by'     => 'menu_order',
                  'has_archives' => false,
              ) );
  
              register_taxonomy(
                  $taxonomy_slug,
                  apply_filters( 'woocommerce_taxonomy_objects_' . $taxonomy_slug, array( 'product' ) ),
                  apply_filters( 'woocommerce_taxonomy_args_' . $taxonomy_slug, array(
                      'labels'       => array(
                          'name' => $attribute_name,
                      ),
                      'hierarchical' => true,
                      'show_ui'      => false,
                      'query_var'    => true,
                      'rewrite'      => false,
                  ) )
              );

              //Clear caches
              delete_transient('wc_attribute_taxonomies');


              $slug1 = 'load-spd';
              $attribute_name1 = 'Load / Spd';
              $taxonomy_slug1 = wc_attribute_taxonomy_name($slug1);

              if (taxonomy_exists($taxonomy_slug1))
              {
                  return wc_attribute_taxonomy_id_by_name($slug1);
              }
              
              //logg("Creating a new Taxonomy! `".$taxonomy_name."` with name/label `".$name."` and slug `".$slug.'`');

              $attribute_id = wc_create_attribute( array(
                  'name'         => $attribute_name1,
                  'slug'         => $slug1,
                  'type'         => 'select',
                  'order_by'     => 'menu_order',
                  'has_archives' => false,
              ) );
              //Register it as a wordpress taxonomy for just this session. Later on this will be loaded from the woocommerce taxonomy table.
              register_taxonomy(
                  $taxonomy_slug1,
                  apply_filters( 'woocommerce_taxonomy_objects_' . $taxonomy_slug1, array( 'product' ) ),
                  apply_filters( 'woocommerce_taxonomy_args_' . $taxonomy_slug1, array(
                      'labels'       => array(
                          'name' => $attribute_name1,
                      ),
                      'hierarchical' => true,
                      'show_ui'      => false,
                      'query_var'    => true,
                      'rewrite'      => false,
                  ) )
              );
              //Clear caches
              delete_transient('wc_attribute_taxonomies');
} else {
    echo '<center>';
    echo '<h1>CSV File Impoter </h1>';
    echo '<form action="" method="post" enctype="multipart/form-data">';
    echo '<input type="file" name="csv_file"><br><br>';
    echo '<input type="submit" name="submit" value="Import">';
    echo '</form>';
    echo '</center>';
  }
?>





