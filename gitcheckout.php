<?php
$action = '';
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
		}else{
			$allBranch[$key] = str_replace('remotes/origin/', '', $value);
			$allBranch[$key] = str_replace('*', '', $allBranch[$key]);
			$allBranch[$key] = str_replace('origin/', '', $allBranch[$key]);
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
print_r($_POST);
if($action == 'commitedFileList'){
	$sBranch = trim($_POST['checkoutBranch']);
	$fileList = shell_exec('git diff --name-status '.$cBranch.'..'.$sBranch); 
	$fileList = explode(' ', $fileList);
	echo '<br><br> New branch changes are: <bR><br>';
	echo 'Tryp'.  "\t \t" . 'File name';
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
	echo 'git checkout '.$sBranch;
	//$result = shell_exec('git checkout '.$sBranch);
	echo "<pre>$result</pre>";
	echo "<br> cd /www/cronjobs/tiezcvs5/; git fetch origin; git checkout ".$sBranch."; git pull origin ".$sBranch.";";
	$result1 = shell_exec("cd /www/cronjobs/tiezcvs5/; git fetch origin; git checkout ".$sBranch."; git pull origin ".$sBranch.";"); 
	echo "<pre>$result1</pre>";
	//$result1 = exec('git checkout '.$sBranch);
}
?>