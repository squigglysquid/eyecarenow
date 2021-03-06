<?php
/**
 * Template Name: Locations
 *
 * @package EyeCareNow
 */

$newsenabled    = get_field('show_news_feed');
$newscat        = get_field('news_category');
$sidebarbuttons = get_field('sidebar_buttons');
$slider         = get_field('header_slideshow');

get_header(); ?>
    <div id="primary" class="support-area">
        <div id="mid">
            <div class="container">

                <?php if ($slider != '') { ?>
                    <div id="featured-image">
                        <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/inc/flexslider/flexslidermin.css" type="text/css">
                        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
                        <script src="<?php echo get_template_directory_uri() ?>/inc/flexslider/jquery.flexslider-min.js"></script>
                        <div class="flexslider">
                            <ul class="slides">
                                <?php


                                $p = 1;
                                foreach ($slider[0] as $ph) {
                                    echo '<li>';
                                    echo '<img src="' . $ph['imageURL'] . '" alt="' . $ph['alttext'] . '" />';
                                    echo '</li>';
                                    $p++;
                                }


                                ?>
                            </ul>
                        </div>

                    </div>
                <?php } ?>

                <div id="content-left" class="col res-13 tab-13 wide-1 ph-1">
                    <div id="primary" class="content-area">
                        <main id="locations" class="locations" role="contact">

                            <?php while (have_posts()) : the_post(); ?>

                                <?php get_template_part('content', 'page'); ?>

                            <?php endwhile; // end of the loop. ?>

                            <?php
                            $args = [
                                'post_type'      => 'location',
                                'posts_per_page' => -1,
                                'offset'         => 0,
                                'post_status'    => 'publish'
                            ];

                            $locations = get_posts($args);


                            ?>

                        </main><!-- #main -->
                    </div><!-- #primary -->

                </div>
                <div id="content-right" class="nopad col res-23 tab-23 wide-1 ph-1">

                    <div id="map" style="height:540px; max-height:90%;"></div>

                    <script type="text/javascript">
                        function initialize() {
                            var myLatlng = new google.maps.LatLng(30.363516, -85.554483);
                            var bounds = new google.maps.LatLngBounds();
                            var mapOptions = {
                                zoom: 9,
                                center: myLatlng
                            }
                            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

                            <?php

                            foreach ($locations as $location) {
                                $id       = $location->ID;
                                $photo    = get_field('location_photo', $id);
                                $address  = get_field('address', $id);
                                $address2 = get_field('address_2', $id);
                                $latlng   = get_field('latlng', $id);
                                $phone    = get_field('phone_number', $id);


                                echo 'var contentString' . $id . ' = \'<div id="infowindow">\'+     							' . "\r\n";
                                echo '	\'<img src="' . $photo['url'] . '" class="office-photo" style="max-width:100%;" />\'+ 	    					' . "\r\n";
                                echo '	\'<h2>' . $location->post_title . '</h2>\'+ 	 			' . "\r\n";
                                echo '	\'<div id="address" class="col res-1">\'+											' . "\r\n";
                                echo '	\'<p>' . $address . '<br>\'+																' . "\r\n";
                                echo '	\'' . $address2 . '</p>\'+																' . "\r\n";
                                echo '	\'</div>\'+																			' . "\r\n";
                                echo '	\'<strong>Office:</strong> ' . $phone . '</div>\'+     									' . "\r\n";
                                echo '	\'</div></div>\';     																' . "\r\n";


                                echo 'var infowindow' . $id . ' = new google.maps.InfoWindow({     								' . "\r\n";
                                echo '	  maxWidth: 300,     																' . "\r\n";
                                echo '	  content: contentString' . $id . '  													' . "\r\n";
                                echo '});     																				' . "\r\n";


                                echo 'var marker' . $id . ' = new google.maps.Marker({     										' . "\r\n";
                                echo '	position: new google.maps.LatLng(' . $latlng . '),    			 						' . "\r\n";
                                echo '	map: map,     																		' . "\r\n";
                                echo '	title: \'' . $location->post_title . '\'	   		  									' . "\r\n";
                                //echo '	icon: image     																'."\r\n";
                                echo '});     																				' . "\r\n";

                                echo 'google.maps.event.addListener(marker' . $id . ', \'click\', function() {     				' . "\r\n";
                                echo '	infowindow' . $id . '.open(map, marker' . $id . ');     									' . "\r\n";
                                echo '});      																				' . "\r\n";

                            }

                            ?>
                        }

                        google.maps.event.addDomListener(window, 'load', initialize);
                    </script>


                </div>

                <div id="content-bottom" class="locations">
                    <?php

                    foreach ($locations as $location) {
                        $id       = $location->ID;
                        $photo    = get_field('location_photo', $id);
                        $address  = get_field('address', $id);
                        $address2 = get_field('address_2', $id);
                        $latlng   = get_field('latlng', $id);
                        $phone    = get_field('phone_number', $id);
                        echo '<div class="location col res-14 tab-13 wide-12 ph-1">';
                        echo '<h3>' . $location->post_title . '</h3>';
                        echo $address . '<br>';
                        echo $address2;
                        echo '<p>Phone: ' . $phone . '</p>';
                        echo '<form action="http://maps.google.com/maps" method="get" target="_blank"><input name="saddr" type="hidden" value="" /><input name="daddr" type="hidden" value="' . strip_tags($address) . '" /><input type="submit" value=" Get Directions " />&nbsp;</form>&nbsp;';
                        //echo '<form action="/patient-center/request-an-appointment/" method="get" ><input name="office" type="hidden" value="'.$location->post_title.'" /><input type="submit" value=" Request Appointment " />&nbsp;</form>';
                        echo '</div>';
                    }

                    ?>
                </div>

                <script type="text/javascript" charset="utf-8">
                    $(window).load(function () {
                        $('.flexslider').flexslider({
                            controlNav: true,                //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
                            directionNav: false,             //Boolean: Create navigation for previous/next navigation? (true/false)
                            prevText: "<",           		 //String: Set the text for the "previous" directionNav item
                            nextText: ">"               	 //String: Set the text for the "next" directionNav item
                        });
                    });
                </script>
            </div>
        </div>
    </div>
<?php get_footer(); ?>