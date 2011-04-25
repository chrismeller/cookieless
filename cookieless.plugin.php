<?php

	class Cookieless extends Plugin {
		
		public function filter_final_output ( $content = '' ) {
			
			// if we're running on localhost, don't run
			if ( $_SERVER['SERVER_NAME'] == 'localhost' ) {
				return $content;
			}
			
			$dom = new DOMDocument();
			$dom->validateOnParse = false;
			$dom->strictErrorChecking = false;
			$dom->formatOutput = true;
			$dom->recover = true;
			$dom->substituteEntities = true;
			
			@$dom->loadHTML( $content );
			
			$xpath = new DOMXPath( $dom );
			$images = $xpath->query( '//img[contains(@src, "' . Site::get_url('habari') . '")]' );
			$jses = $xpath->query( '//js[contains(@src, "' . Site::get_url('habari') . '")]' );
			$links = $xpath->query( '//link[contains(@href, "' . Site::get_url('habari') . '")]' );
			
			foreach ( $images as $image ) {
				
				$url = $image->getAttribute('src');
				$new_url = MultiByte::str_replace( Site::get_url('habari'), Site::get_url('cookieless_habari'), $url );
				$image->setAttribute( 'src', $new_url );
				
			}
			
			foreach ( $jses as $js ) {
				
				$url = $js->getAttribute('src');
				$new_url = MultiByte::str_replace( Site::get_url('habari'), Site::get_url('cookieless_habari'), $url );
				$js->setAttribute( 'src', $new_url );
				
			}
			
			foreach ( $links as $link ) {
				
				$url = $link->getAttribute('href');
				$new_url = MultiByte::str_replace( Site::get_url('habari'), Site::get_url('cookieless_habari'), $url );
				$link->setAttribute( 'src', $new_url );
				
			}
			
			return $dom->saveHTML();
			
		}
		
		public function filter_site_url_cookieless_habari ( $url ) {
			
			$url = Site::get_url( 'habari' );
			
			$pieces = InputFilter::parse_url( $url );
			$pieces['host'] = 'static.' . $pieces['host'];
			
			$url = InputFilter::glue_url( $pieces );
			
			return $url;
			
		}
		
	}

?>