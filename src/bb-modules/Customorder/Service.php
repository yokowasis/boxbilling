<?php

	$secret_folder = 'secret_folder';
	
	switch ($order['product_id']) {
		case 32 :
			# Example 1 - Product ID 32
			if (isset($_GET['download'])) {
				//Download
				$secret_filename = 'file1.zip'
				$filename = 'downloadedfile1.zip'; 
				$file = './bb-modules/Customorder/downloads/'.$secret_folder.'/'.$secret_filename;      

				set_time_limit(0);
				header("Content-Description: File Transfer");
				header('Content-type: application/zip');
				header("Content-Disposition: attachment; filename=\"{$filename}\"");
				header("Content-Length: " . filesize($file));
				header('Pragma: public');
				header("Expires: 0");
				readfile($file);
				exit;
			} else {
				//Show Download Button
				$order['onetimelink'] = true;

			}
			break;
		
		case 33 :
			# Example 1 - Product ID 33
			if (isset($_GET['download'])) {
				$secret_filename = 'file2.zip'
				$filename = 'downloadedfile2.zip'; 
				$file = './bb-modules/Customorder/downloads/'.$secret_folder.'/'.$secret_filename;


				set_time_limit(0);
				header("Content-Description: File Transfer");
				header('Content-type: application/zip');
				header("Content-Disposition: attachment; filename=\"{$filename}\"");
				header("Content-Length: " . filesize($file));
				header('Pragma: public');
				header("Expires: 0");
				readfile($file);
				exit;

			} else {
				//Show Download Button
				$order['onetimelink'] = true;

			}
			break;
		
		default:
			# code...
			break;
	}
?>