<?php
	//Reads csv list and saves it into an array
	$row = 1;
	if (($handle = fopen("filelist.csv", "r")) !== FALSE) {
		
	  while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		  
		$csv_array[] = $data;	
		
	  }
	  
	  fclose($handle);
	}
	
	//Display the array
	echo "<pre>";
	var_dump($csv_array);
	echo "</pre>";
	
	//Cycle throught the filelist
	for($i = 0; $i < sizeof($csv_array); $i++){
		$sku = $csv_array[$i][0];

		//This will return the HTML source of the page as a string.
		$htmlString = file_get_contents('http://www.mercateo.com/mimegallery.jsp?CatalogID=C2457&SKU='.$sku.'&image=0');
		 
		//Create a new DOMDocument object.
		$htmlDom = new DOMDocument;
		 
		//Load the HTML string into our DOMDocument object.
		@$htmlDom->loadHTML($htmlString);
		 
		//Extract all img elements / tags from the HTML.
		$imageTags = $htmlDom->getElementsByTagName('img');
		 
		//Create an array to add extracted images to.
		$extractedImages = array();
		 
		//Loop through the image tags that DOMDocument found.
		foreach($imageTags as $imageTag){ 
		 
		    $img = $imageTag->getAttribute('src');
		    
		    //Search for the first "real" image
		    if($img != "/p.gif" && $img != "/design/mercateo/me_logo4_transp.gif" && $img != "//mycliplister.com/static/playbtn.png"){
			    
			    //Download the image
			    file_put_contents("img/".$sku.".jpg", fopen($img, 'r'));
			    echo "Successfully downloaded " . $sku . "!<br>";
			    break;
		    }
		    
		}
	}

	