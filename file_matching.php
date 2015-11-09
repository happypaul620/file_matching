<pre>
<?php
		header("Content-type: text/html; charset=utf-8"); //utf-8

		require 'C:\AppServ\www\PHPExcel\Classes\PHPExcel.php'; //加入 PHPExcel.php 檔
		
		//require 'C:\xampp\htdocs\PHPExcel\Classes\PHPExcel.php'; //加入 PHPExcel.php 檔
	
		ini_set('memory_limit', '-1'); //加大記憶體空間
		
		set_time_limit(5000);  
		
		ini_set("upload_max_filesize","400M"); 
		
		ini_set("post_max_size","500M"); 
		
		
		function inputUI($t){
			if($t!="" && preg_match('/\D{1}\:\/(.*)\//',$t,$v)){
				fwrite(STDOUT, "Hello, Your copy destination location is $t !\n");
			}
			else{
				fwrite(STDERR, "You not input path or wrong path!\n");
				fwrite(STDOUT, "Enter your location again: (eg.M:/passer_test5/)\n");
				$p = trim(fgets(STDIN));
				inputUI($p);
			}
		}
		
		//設定來源檔輸出檔
		//================================================================================================
		//透過 標準輸出 印出要詢問的內容
		fwrite(STDOUT, "Enter your copy destination location: (eg.M:/passer_test5/)\n");
		// 抓取 標準輸入 的 內容
		$_out = trim(fgets(STDIN));
		// 將剛剛輸入的內容, 送到 標準輸出
		inputUI($_out);
		
		//透過 標準輸出 印出要詢問的內容
		fwrite(STDOUT, "Enter your first copy source location: (eg.H:/012/)\n");
		// 抓取 標準輸入 的 內容
		$_src1 = trim(fgets(STDIN));
		// 將剛剛輸入的內容, 送到 標準輸出
		inputUI($_src1);
		
		//透過 標準輸出 印出要詢問的內容
		fwrite(STDOUT, "Enter your second copy source location: (eg.H:/012/)\n");
		// 抓取 標準輸入 的 內容
		$_src2 = trim(fgets(STDIN));
		// 將剛剛輸入的內容, 送到 標準輸出
		inputUI($_src2);
		
		$do1 = scandir($_src1);
		$do2 = scandir($_src2);
		
		array_shift($do1);
		array_shift($do1);
		array_shift($do2);
		array_shift($do2);
		
		//var_dump($do1);
		//var_dump($do2);
	
		function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
		{
			$result=false;
 
			if (is_file($source)) {
				if ($dest[strlen($dest)-1]=='/') {
					if (!file_exists($dest)) {
						cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
					}
					$__dest=$dest."/".basename($source);
				} else {
                $__dest=$dest;
            }
            $result=copy($source, $__dest);
            chmod($__dest,$options['filePermission']);
 
			} elseif(is_dir($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if ($source[strlen($source)-1]=='/') {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest=$dest.basename($source);
                    @mkdir($dest);
                    chmod($dest,$options['filePermission']);
                }
            } else {
                if ($source[strlen($source)-1]=='/') {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                }
            }
 
            $dirHandle=opendir($source);
			
            while($file=readdir($dirHandle))
            {
                if($file!="." && $file!="..")
                {
                     if(!is_dir($source."/".$file)) {
							$__dest=$dest."/".$file;
					} 
					else {
							$__dest=$dest."/".$file;
					}
						//echo "$source/$file ||| $__dest<br />";
						$result=smartCopy($source."/".$file, $__dest, $options);
				}
			}
				closedir($dirHandle);
 
			} else {
				$result=false;
			}
			return $result;
		}
		
		foreach ($do1 as $dc1)		
		{		
			$flag = 0;
			foreach ($do2 as $dc2)		
			{
				if ($dc1 != 'Thumbs.db' || $dc2 != 'Thumbs.db')
				{
					if($dc1 == $dc2)
					{
						$flag = 1;
						continue;		
					}
				}
			}
			if($flag == 0)
			{
				echo $dc1."\n";
				$sum = $sum + 1;
				smartCopy($_src1.'/'.$dc1 , $_out);
			}
		}
		
		echo "\n". "sum = ". $sum . "\n";
?>
</pre>
