<?php
ob_start();
$ticker_options= get_option('vertical_ticker_option'); 


$thumb_height=$ticker_options['thmb_height'];
if($thumb_height=='0' || $thumb_height=="" )$thumb_height="100";

$thumb_width=$ticker_options['thmb_width'];
if($thumb_width=='0' || $thumb_width=="" )$thumb_width="100";

$ticker_color=$ticker_options['ticker_bg'];
$ticker_title_size=$ticker_options['ticker_title_size'];
$ticker_title_color=$ticker_options['ticker_title_color'];
$ticker_text_color=$ticker_options['ticker_text_color'];
$ticker_text_size=$ticker_options['ticker_text_size'];
$thmb_border=$ticker_options['thmb_border'];
$thmb_border_color=$ticker_options['thmb_border_color'];


$ticker_css= '<style>
.wp-vslider img{
		height: '.$thumb_height.'px!important;
		width: '.$thumb_width.'px!important;
		border: '.$thmb_border.'px solid '.$thmb_border_color.';
	}
	
.wp-vslider li.odd {
  background:'.$ticker_color.'!important;
}


.wp-vslider h3{
  font-size:'.$ticker_title_size.'!important;
  
}

.wp-vslider h3 a{
color:'.$ticker_title_color.'!important;
}

.wp-vslider p {
color:'.$ticker_text_color.'!important;
font-size:'.$ticker_text_size.'!important;
}
</style>';
echo  $ticker_css;

?>