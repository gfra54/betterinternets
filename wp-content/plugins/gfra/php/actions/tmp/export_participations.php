<?php 

add_action( 'init', 'export_participations' );

function export_participations() {
	if(isset($_GET['export_participations'])) {
		$args=array(
		  'post_type' => 'participation',
		  'post_status' => 'publish',
		  'posts_per_page' => -1
		);
		if(isset($_GET['adate'])) {
			if($date_export = get_transient('date_export')) {
	   			$args['date_query'] = array( 'after' => date('Y-m-d H:i:s',$date_export) );
			}
			set_transient('date_export',time());
		}
		$my_query = new WP_Query($args);
		if( $my_query->have_posts() ) {
			$out=array();
			$heading = array();
			foreach($my_query->posts as $post) {

				$meta = get_post_meta($post->ID);
				$final = array(
					'date'=>array(date('d/m/Y',strtotime($post->post_date))),
					'heure'=>array(date('H:i',strtotime($post->post_date))),
				) + $meta;
				unset($final['ip']);
				unset($final['niveau']);
				if(!empty($final['tel'][0])) {
					$final['tel'][0]=formatPhoneNumber($final['tel'][0])." ";
				}
				if(!empty($final['cp'][0])) {
					$final['cp'][0]=formatCp($final['cp'][0]);
				}
				$values=array();
				foreach($final as $k=>$v) {
					if($heading!==false && count($out)==0) {
						$heading[]=$k;
					}
					$values[]=$v[0];
				}
				if($heading!==false && count($heading)) {
					$out[]=arrayToCsv($heading);
					$heading=false;
				}
				$out[] = arrayToCsv($values);
			}

		}
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="sfr-readyforpremierleague-'.date('Y-m-d-H-i-s').'.csv"');
		$data = implode(PHP_EOL, $out);
		echo utf8_decode($data);
		exit;
	}
}