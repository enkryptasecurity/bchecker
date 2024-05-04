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
		echo "Starting... \n";
		$strings = file_get_contents($wordlist);
		$unique_string = explode("\n",$strings);
		$count = 0;
		$count_found = 0;
		$count_not_found = 0;
		foreach($unique_string as $string_atp){
			foreach($unique_hash as $hash_atp){
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
		$count = 0;
		$count_found = 0;
		$count_not_found = 0;
		foreach($unique_string as $string_atp){
			foreach($unique_hash as $hash_atp){
				if(wp_check_password(trim($string_atp), trim($hash_atp))){
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