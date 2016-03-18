<?php


class UploadController extends Controller
{
	function upload()
	{
		
		$output_dir = "uploads/file/";
		
		$ret = array();

		$filekey = 'myfile';
		
		if(Input::hasFile($filekey) === false )
			return "No input file";
	
		if (Input::file($filekey)->isValid() === false )
			return "No valid file";
		
		//You need to handle  both cases
		//If Any browser does not support serializing of multiple files using FormData() 
		if (!is_array($_FILES[$filekey]["name"])) //single file
		{
			$fileName = $_FILES[$filekey]["name"];
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);	
			$filename1 = "pro_".time().".".strtolower($ext);		
			move_uploaded_file($_FILES[$filekey]["tmp_name"], $output_dir . $filename1);
			
			$ret[] = $filename1;
		}
		else  //Multiple files, file[]
		{
			$fileCount = count($_FILES[$filekey]["name"]);
			for ($i = 0; $i < $fileCount; $i++)
			{
				$fileName = $_FILES[$filekey]["name"][$i];
				$ext = pathinfo($fileName, PATHINFO_EXTENSION);	
				$filename1 = "pro_".time().".".strtolower($ext);
				move_uploaded_file($_FILES[$filekey]["tmp_name"][$i], $output_dir . $filename1);
				$ret[] = $filename1;
			}
		}
		
		echo $ret[0];//json_encode($ret);
	}
	
}
