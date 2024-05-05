<?php

$welcome = '


██████╗░░█████╗░██╗░░██╗███████╗░█████╗░██╗░░██╗███████╗██████╗░
██╔══██╗██╔══██╗██║░░██║██╔════╝██╔══██╗██║░██╔╝██╔════╝██╔══██╗
██████╦╝██║░░╚═╝███████║█████╗░░██║░░╚═╝█████═╝░█████╗░░██████╔╝
██╔══██╗██║░░██╗██╔══██║██╔══╝░░██║░░██╗██╔═██╗░██╔══╝░░██╔══██╗
██████╦╝╚█████╔╝██║░░██║███████╗╚█████╔╝██║░╚██╗███████╗██║░░██║
╚═════╝░░╚════╝░╚═╝░░╚═╝╚══════╝░╚════╝░╚═╝░░╚═╝╚══════╝╚═╝░░╚═╝v.1.0
CMD Version by Vinícius Pinheiro
https://github.com/enkryptasecurity

Options:

1 - Help
2 - Bcrypt Attack Mode 1
3 - Bcrypt Attack Mode 2
4 - WordPress PHPass Attack Mode 1
5 - WordPress PHPass Attack Mode 2


';
if(phpversion() >= '8' && phpversion() < '7'){
	echo "PHP version looks incompatible. BChecker may face problems. Are you sure you want to continue? [y/n] ";
	$continue_in = fopen("php://stdin","r"); $continue = trim(fgets($continue_in));
	if($continue === 'n'){
		exit;
	}
}
ini_set('memory_limit', '-1');
echo $welcome."\n";
echo "Select Option: "; $input_option = fopen("php://stdin","r"); $option = trim(fgets($input_option));
switch($option){
	case '1':
		echo "Bcrypt Attack Mode 1 - Dictionary cracking method \n";
		echo "Bcrypt Attack Mode 2 - Single String cracking method \n";
		echo "WordPress PHPass Attack Mode 1 - Dictionary cracking method \n";
		echo "WordPress PHPass Attack Mode 2 - Single String cracking method \n\n";
		echo "NOTE: WP PHPass requires WP Core";
		exit;
	break;
	case '2':
		echo "Enter Hashlist path:";
		$input_hashlist = fopen("php://stdin","r"); $hashlist = trim(fgets($input_hashlist));
		echo "\n";
		if(!file_exists($hashlist)){
			die('File does not exist!'."\n"."Aborting..."."\n");
		}else{
			$allowed = ['txt'];
			if(!in_array(pathinfo($hashlist, PATHINFO_EXTENSION),$allowed)){
				die('This is not a valid hashlist'."\n"."Aborting..."."\n");
			}
		}
		$hashes = file_get_contents($hashlist);
		$unique_hash = explode("\n",$hashes);
		echo 'Now enter Wordlist path:';
		$input_wordlist = fopen("php://stdin","r"); $wordlist = trim(fgets($input_wordlist));
		if(!file_exists($wordlist)){
			die('File does not exist!'."\n"."Aborting..."."\n");
		}else{
			$allowed = ['txt'];
			if(!in_array(pathinfo($wordlist, PATHINFO_EXTENSION),$allowed)){
				die('This is not a valid wordlist'."\n"."Aborting..."."\n");
			}
		}
		echo "\n";
		echo 'Crack Option (1 - Crack All, 2 - Crack One and exit): '; $crack_input = fopen("php://stdin","r"); $crack_opt = trim(fgets($crack_input));
		$options = ['1','2'];
		if(!in_array($crack_opt, $options)){
			die('Invalid option. Aborting...');
		}
		echo "Set Index? [y/n] "; $index_input = fopen("php://stdin","r"); $index_opt = trim(fgets($index_input));
		if($index_opt === 'y'){
			echo "Index? "; $index_n_input = fopen("php://stdin","r"); $index_n = trim(fgets($index_n_input));
			if(empty($index_n)){
				die("Invalid Index! \n Aborting... \n");
			}
			if(!is_numeric($index_n)){
				die("Index is not a number! \n Aborting... \n");
			}
			$set_index = true;
		}
		echo "Starting... \n";
		$strings = file_get_contents($wordlist);
		$unique_string = explode("\n",$strings);
		$count = 0;
		$count_found = 0;
		$count_not_found = 0;
		foreach($unique_string as $string_atp){
			foreach($unique_hash as $hash_atp){
				if(isset($set_index)){
					if($count < $index_n){
						$count++;
						continue; // skips if count is lower than last index
					}
				}
				if(password_verify(trim($string_atp), trim($hash_atp))){
					$count++;
					echo "".$count." Success! \n";
					$count_found++;
					$found_hashes[] = "Password Matches! ".$hash_atp." = ".$string_atp." \n";
					$job_success = true;
					if($crack_opt === '2'){
						break 2; // ends loop after cracking 1 hash
					}
				}else{
					$count++;
					$count_not_found++;
					echo "".$count." Fail! \n";
				}
			}
		}
		if(isset($job_success)){
			echo "Cracking was finished: ".$count." hashes were checked. ".$count_found." found, ".$count_not_found." not found. \n";
			$i = 0;
			foreach($found_hashes as $success_recover){
				if($i === 0){
					file_put_contents("bchecker_result.txt", $success_recover);
					$i++;
				}else{
					$content = file_get_contents("bchecker_result.txt");
					file_put_contents("bchecker_result.txt", $content.$success_recover);
				}
			}
			echo "The results have been saved.";
		}else{
			echo "Cracking was finished: ".$count." hashes were checked. ".$count_found." found, ".$count_not_found." not found. \n";
			echo 'No hashes were found! :(';
		}
	break;
	case '3':
		echo "Enter hashlist path:";
		$input_hashlist = fopen("php://stdin","r"); $hashlist = trim(fgets($input_hashlist));
		echo "\n";
		if(!file_exists($hashlist)){
			die('File does not exist!'."\n"."Aborting..."."\n");
		}else{
			$allowed = ['txt'];
			if(!in_array(pathinfo($hashlist, PATHINFO_EXTENSION),$allowed)){
				die('This is not a valid hashlist'."\n"."Aborting..."."\n");
			}
		}
		$hashes = file_get_contents($hashlist);
		$unique_hash = explode("\n",$hashes);
		echo "Enter string:";
		$input_string = fopen("php://stdin","r"); $string = trim(fgets($input_string));
		if(empty($string)){
			die("Empty string detected. \n Aborting... \n");
		}
		echo "Starting... \n";
		$count = 0;
		$count_found = 0;
		$count_not_found = 0;
		foreach($unique_hash as $hash_atp){
			if(password_verify(trim($string), trim($hash_atp))){
				$count++;
				echo "".$count." Success! \n";
				$count_found++;
				$found_hashes[] = "Password Matches! ".$hash_atp." = ".$string." \n";
				$job_success = true;
			}else{
				$count++;
				$count_not_found++;
				echo "".$count." Fail! \n";
			}
		}
		if(isset($job_success)){
			echo "Cracking was finished: ".$count." hashes were checked. ".$count_found." found, ".$count_not_found." not found. \n";
			$i = 0;
			foreach($found_hashes as $success_recover){
				if($i === 0){
					file_put_contents("bchecker_result.txt", $success_recover);
					$i++;
				}else{
					$content = file_get_contents("bchecker_result.txt");
					file_put_contents("bchecker_result.txt", $content.$success_recover);
				}
			}
			echo "The results have been saved.";
		}else{
			echo "Cracking was finished: ".$count." hashes were checked. ".$count_found." found, ".$count_not_found." not found. \n";
			echo 'No hashes were found! :(';
		}
	break;
	case '4':
		if(!file_exists("wp-load.php")){
			die("[!] ERROR: WordPress Core file not found.");
		}
		include("wp-load.php"); // includes wp core
		echo "Enter Hashlist path: ";
		$input_hashlist = fopen("php://stdin","r"); $hashlist = trim(fgets($input_hashlist));
		echo "\n";
		if(!file_exists($hashlist)){
			die("File does not exist! \n Aborting... \n");
		}else{
			$allowed = ['txt'];
			if(!in_array(pathinfo($hashlist, PATHINFO_EXTENSION),$allowed)){
				die("This is not a valid hashlist. \n Aborting... \n");
			}
		}
		$hashes = file_get_contents($hashlist);
		$unique_hash = explode("\n",$hashes);
		echo "Now enter Wordlist path:";
		$input_wordlist = fopen("php://stdin","r"); $wordlist = trim(fgets($input_wordlist));
		if(!file_exists($wordlist)){
			die('File does not exist!'."\n"."Aborting..."."\n");
		}else{
			$allowed = ['txt'];
			if(!in_array(pathinfo($wordlist, PATHINFO_EXTENSION),$allowed)){
				die('This is not a valid wordlist'."\n"."Aborting..."."\n");
			}
		}
		$strings = file_get_contents($wordlist);
		$unique_string = explode("\n",$strings);
		echo "\n";
		echo 'Crack Option (1 - Crack All, 2 - Crack One and exit): '; $crack_input = fopen("php://stdin","r"); $crack_opt = trim(fgets($crack_input));
		$options = ['1','2'];
		if(!in_array($crack_opt, $options)){
			die('Invalid option. Aborting...');
		}
		echo "Set Index? [y/n] "; $index_input = fopen("php://stdin","r"); $index_opt = trim(fgets($index_input));
		if($index_opt === 'y'){
			echo "Index? "; $index_n_input = fopen("php://stdin","r"); $index_n = trim(fgets($index_n_input));
			if(empty($index_n)){
				die("Invalid Index! \n Aborting... \n");
			}
			if(!is_numeric($index_n)){
				die("Index is not a number! \n Aborting... \n");
			}
			$set_index = true;
		}
		if($crack_opt === '1'){
			echo "Want to set limits? [y/n]: "; $limit_input = fopen("php://stdin","r"); $limit = trim(fgets($limit_input));
			if($limit === 'y'){
				echo "How much?: "; $limit_count_input = fopen("php://stdin","r"); $limit_count = trim(fgets($limit_count_input));
				if(empty($limit_count)){
					die("Limit is empty! \n Aborting...");
				}else{
					if(!is_numeric($limit_count)){
						die("Input is not a number! \n Aborting...");
					}else{
						$is_limited = true;
					}
				} 
			}
		}
		$count = 0;
		$count_found = 0;
		$count_not_found = 0;
		foreach($unique_string as $string_atp){
			foreach($unique_hash as $hash_atp){
				if(isset($set_index)){
					if($count < $index_n){
						$count++;
						continue; // skips if count is lower than last index
					}
				}
				if(wp_check_password(trim($string_atp), trim($hash_atp))){
					$count++;
					echo "".$count." Success! \n";
					$count_found++;
					$found_hashes[] = "Password Matches! ".$hash_atp." = ".$string_atp." \n";
					$job_success = true;
					if(isset($is_limited)){
						if($count_found >= $limit_count){
							break 2; // ends loop after reaching limit
						}
					}
					if($crack_opt === '2'){
						break 2; // ends loop after cracking 1 hash
					}
				}else{
					$count++;
					$count_not_found++;
					echo "".$count." Fail! \n";
				}
			}
		}
		if(isset($job_success)){
			echo "Cracking was finished: ".$count." hashes were checked. ".$count_found." found, ".$count_not_found." not found. \n";
			if(isset($is_limited)){
				echo "BChecker reached limit of ".$limit_count." hashes cracked. \n";
			}
			$i = 0;
			foreach($found_hashes as $success_recover){
				if($i === 0){
					file_put_contents("bchecker_wp_result.txt", $success_recover);
					$i++;
				}else{
					$content = file_get_contents("bchecker_wp_result.txt");
					file_put_contents("bchecker_wp_result.txt", $content.$success_recover);
				}
			}
			echo "The results have been saved.";
		}else{
			echo "Cracking was finished: ".$count." hashes were checked. ".$count_found." found, ".$count_not_found." not found. \n";
			echo 'No hashes were found! :(';
		}
	break;
	case '5':
		if(!file_exists("wp-load.php")){
			die("[!] ERROR: WordPress Core file not found.");
		}
		include("wp-load.php"); // includes wp core
		echo "Enter Hashlist path: ";
		$input_hashlist = fopen("php://stdin","r"); $hashlist = trim(fgets($input_hashlist));
		echo "\n";
		if(!file_exists($hashlist)){
			die("File does not exist! \n Aborting... \n");
		}else{
			$allowed = ['txt'];
			if(!in_array(pathinfo($hashlist, PATHINFO_EXTENSION),$allowed)){
				die("This is not a valid hashlist. \n Aborting... \n");
			}
		}
		$hashes = file_get_contents($hashlist);
		$unique_hash = explode("\n",$hashes);
		echo "Enter string:";
		$input_string = fopen("php://stdin","r"); $string = trim(fgets($input_string));
		if(empty($string)){
			die("Empty string detected. \n Aborting... \n");
		}
		echo "Starting... \n";
		$count = 0;
		$count_found = 0;
		$count_not_found = 0;
		foreach($unique_hash as $hash_atp){
			if(wp_check_password(trim($string), trim($hash_atp))){
				$count++;
				echo "".$count." Success! \n";
				$count_found++;
				$found_hashes[] = "Password Matches! ".$hash_atp." = ".$string." \n";
				$job_success = true;
			}else{
				$count++;
				$count_not_found++;
				echo "".$count." Fail! \n";
			}
		}
		if(isset($job_success)){
			echo "Cracking was finished: ".$count." hashes were checked. ".$count_found." found, ".$count_not_found." not found. \n";
			$i = 0;
			foreach($found_hashes as $success_recover){
				if($i === 0){
					file_put_contents("bchecker_wp_result.txt", $success_recover);
					$i++;
				}else{
					$content = file_get_contents("bchecker_wp_result.txt");
					file_put_contents("bchecker_wp_result.txt", $content.$success_recover);
				}
			}
			echo "The results have been saved.";
		}else{
			echo "Cracking was finished: ".$count." hashes were checked. ".$count_found." found, ".$count_not_found." not found. \n";
			echo 'No hashes were found! :(';
		}
	break;
	default:
		die('Invalid option. Aborting...');
}
