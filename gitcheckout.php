<?php
$action = '';
//$basePath = "/www/sites/www.spruyt-hillen.nl/";
$basePath = "/www/cronjobs/tiezcvs5/";
shell_exec("cd " . $basePath);

if($_POST['action'] != ''){
	$action = $_POST['action'];
} 
$cBranch = shell_exec('git branch | grep "*" | sed "s/* //"');
$cBranch = trim($cBranch);

if($action == ''){
	echo shell_exec('git pull');
	
	$allBranch = shell_exec('git branch -a');
	$allBranch = explode(' ', $allBranch);
	foreach($allBranch as $key => $value){
		if($value == ''){
			unset($allBranch[$key]);
		}elseif($value == '->'){
			unset($allBranch[$key]);
		}elseif($value == 'HEAD'){
			unset($allBranch[$key]);
		}elseif($value == ''){
			unset($allBranch[$key]);
		}else{
			$allBranch[$key] = str_replace('remotes/origin/', '', $value);
			$allBranch[$key] = str_replace('*', '', $allBranch[$key]);
			$allBranch[$key] = str_replace('origin/', '', $allBranch[$key]);
		}
	}
	foreach($allBranch as $key => $value){
		if(trim($value) == $cBranch){
			unset($allBranch[$key]);
		}elseif($value == ''){
			unset($allBranch[$key]);
		}
	}
	
	$allBranch = array_unique($allBranch);
	sort($allBranch);
	echo '<br>Current branch name is: <b>' . $cBranch . '</b>';
	echo '<form name="checkout" action="" method="post">';
	echo '<input type="hidden" name="action" value="commitedFileList">';
	echo '<br><br>Checkout on branch: ';
		echo '<select name="checkoutBranch">';
			foreach($allBranch as $key => $value){
				echo '<option value="'.trim($value).'">'.trim($value).'</option>';
			}
		echo '</select>';
		echo '<input type="submit" name="submit" value="Checkout Branch">';
	echo '</form>';
}

if($action == 'commitedFileList'){
	$sBranch = trim($_POST['checkoutBranch']);
	$fileList = shell_exec('git diff --name-status '.$cBranch.'..'.$sBranch); 
	$fileList = explode(' ', $fileList);
	echo '<br><br> New branch changes are: <bR><br>';
	echo 'Action'.  " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . 'File name';
	foreach($fileList as $key => $value){
		echo '<br>'.$value;
	}
	echo '<br><br>';
	echo '<form name="checkoutBranch" action="" method="post">';
		echo '<input type="hidden" name="action" value="checkoutBranch">';
		echo '<input type="hidden" name="branchName" value="'.$sBranch.'">';
		echo '<input type="submit" name="submit" value="Checkout Branch">';
	echo '</form>';
} 
if($action == 'checkoutBranch'){
	$sBranch = trim($_POST['branchName']);
	//$result = shell_exec('git checkout '.$sBranch);
	$result1 = shell_exec("git fetch origin; git checkout ".$sBranch."; git pull origin ".$sBranch.";"); 
	echo "<pre>$result1</pre>";
	shell_exec("chmod -R 777 " . $basePath);
	//$result1 = exec('git checkout '.$sBranch);
}
?>