<?php
/*
** Titanfall.Pug Bot Created by tHe sR5 **
* Special thanks to the below for providing base functions
* This bot was built from the ground up no commands were retained from
* Bidhas version of the bot.
*
* PHP IRC Bot Original base
* Customized by Bibhas.
* Get in touch at @pythonerd and @thesr5 in twitter
*/

//Setting time limit off. We need to run the script until We ask it to exit.
set_time_limit(0);
//Open socket to server,port
//My Config
include 'config.php';
/*Set Bot color Scheme
====[Color Codes]=====================================================================
||		00 - White			05 - Dark Red		10 - Teal		15 - Light Grey		||
||		01 - Black			06 - Purple			11 - Turquoise						||
||		02 - Dark Blue		07 - Orange			12 - Blue							||
||		03 - Dark Green		08 - Yellow			13 - Pink							||
||		04 - Red			09 - Bright Green	14 - Dark Grey 						||
======================================================================================
*/

$afcolor = "04";	// Main accent color default: 04
$bcolor = "";		// Background color default: 01
$scolor = "";		// Standard text color default: 00
$team1 = "04";		// Team 1 accent color default: 04
$team2 = "12";		// Team 2 accent color default: 12

$scolor = "$scolor";
$afcolor = "$afcolor";
$team1 = "$team1";
$team2 = "$team2";

//End Bot color scheme

//Sends USER HOSTNAME IDENT :REAL NAME Change it as you wish.
fputs($socket,"USER TFBot TFBot.PugBot TFBot :The TitanFall.Pug Bot\n");

//Sends the NICK to server. Choose a unique one or the script will fail.
fputs($socket,"NICK $bnick\n");

//Array of commands you can use. If you create a new command,
//it must be enlisted here before it can be used.
$commands = array (
"${cmdsym}commands",
"${cmdsym}add",
"${cmdsym}join",
"${cmdsym}last",
"${cmdsym}coms",
"${cmdsym}voip",
"${cmdsym}promote",
"${cmdsym}list",
"${cmdsym}pick",
"${cmdsym}remove",
);
$acommands = array (
"${cmdsym}commands",
"${cmdsym}cmd",
"${cmdsym}clearlist",
"${cmdsym}clearpugfiles",
"${cmdsym}addadmin",
"${cmdsym}setmax",
"${cmdsym}remove",
);

//Sends the script into an infinite loop
while (1) {

    //Recieves the data into $data in 128 bytes.
    //The bot actually does nothing but receive data users enter in the channel and
    //respond to it. So, we'll fetch all the texts entered in the channel and
    //fetch our required data and respond to it.
    while($data=fgets($socket,128)) {
        //puts the data in an array by word
        //this helps us to identify commands and parameters
        $get = explode(' ', $data);
		
		if (stripos( $get[1], 'NICK' ) !== false) {
			$nick = explode(':',$get[0]);
			$nick = explode('!',$nick[1]);
			$nick = $nick[0]; //User who entered the command
			$nuser = $nick;
			$wnuser = preg_replace('/^\\:/','', $get[2]);
			$tnuser = rtrim($wnuser);

				if (chnickchange("tready.txt",$nuser)) {
					fputs($socket,"NOTICE $tnuser : Your name was updated from: $nuser to: $tnuser on the PUG List. - $chan.\n");
				nickchange("tready.txt",$nuser,$tnuser);	
				nickchange("pready.txt",$nuser,$tnuser);
				nickchange("militia.txt",$nuser,$tnuser);
				nickchange("imc.txt",$nuser,$tnuser);
				nickchange("captains.txt",$nuser,$tnuser);
				nickchange("pick.txt",$nuser,$tnuser);
				}
			}
			if (stripos( $get[1], 'QUIT' ) !== false) {
					$nick = explode(':',$get[0]);
					$nick = explode('!',$nick[1]);
					$nick = $nick[0]; //User who entered the command
					$partuser = $nick;
					$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						if (array_search($partuser, $line) !== FALSE) {
							fputs($socket,"PRIVMSG $chan : $nick was removed from the PUG List. - QUIT.\n");
								$DELETE = $partuser; 
								$datap = file("tready.txt"); 
								$out = array(); 

								foreach($datap as $line) { 
									if(trim($line) != $DELETE) { 
										$out[] = $line; 
									} 
								} 

								$fp = fopen("tready.txt", "w+"); 
								flock($fp, LOCK_EX); 
								foreach($out as $line) { 
									fwrite($fp, $line); 
								} 
								flock($fp, LOCK_UN); 
								fclose($fp);  
				}
			}
		
        //Server Pinged us lets reply!
        if ($get[0] == "PING") {
            fputs ($socket, "PONG ".$get[1]."\n");
        }
		if (stripos( $data, 'Nickname is already in use.' ) !== false) {
		$bnick = $bnick . "|2";
		fputs($socket,"NICK $bnick\n");
		}
		if (stripos( $data, 'Welcome' ) !== false) {
		
		fputs($socket,"PRIVMSG Q@CServe.quakenet.org : AUTH $authname $authpass\n");
		sleep(1);
		fputs($socket,"MODE $bnick +x\n");
		//Enter the channel you want to use your bot on.
		sleep(2);
		fputs($socket,"JOIN $jchan\n");
		}
		
        //When someone says somethign in the channel, its fetched in the $get array in the following format
        //0=>:Zhe_Viking!~chatzilla@117.145.203.189 1=>PRIVMSG 2=>#test_field 3=>:!say 4=>Hello 5=>World.

        //The following code sets $nick and $chan variables from the text last entered in the channel
        if (substr_count($get[2],"#")) {
            $nick = explode(':',$get[0]);
            $nick = explode('!',$nick[1]);
            $nick = $nick[0]; //User who entered the command
			$nhost = explode('!', $get[0]); //User Hostname for private commands
			$nhost = $nhost[1];
            $chan = $get[2]; //the channel the bot is in
            $num = 3; //If you observe the array format, actually text starts from 3rd index.
            if ($num == 3) {
                $split = explode(':',$get[3],2);
                $text = rtrim($split[1]); //trimming is important. never forget.
			// Bot Public Commands
			/* if (stripos( $get[1], 'PART' ) !== false) {
					$partuser = $nick;
					removeuser($partuser,"tready.txt","0");
					removeuser($partuser,"pready.txt","1");
					removeuser($partuser,"militia.txt","1");
					removeuser($partuser,"imc.txt","1");
					removeuser($partuser,"captains.txt","2");
					removeuser($partuser,"pick.txt","2");
			} */
			if (stripos( $get[1], 'PART' ) !== false) {
					$partuser = $nick;
					$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						if (array_search($partuser, $line) !== FALSE) {
							fputs($socket,"NOTICE $partuser : You were removed from the PUG List. - $chan.\n");
							fputs($socket,"PRIVMSG $chan : ${afcolor}$partuser ${scolor}was removed from the pug list${afcolor}. - ${scolor}Reason${afcolor}: PART.\n");
							$DELETE = $partuser; 
								$datap = file("tready.txt"); 
								$out = array(); 

								foreach($datap as $line) { 
									if(trim($line) != $DELETE) { 
										$out[] = $line; 
									} 
								} 

								$fp = fopen("tready.txt", "w+"); 
								flock($fp, LOCK_EX); 
								foreach($out as $line) { 
									fwrite($fp, $line); 
								} 
								flock($fp, LOCK_UN); 
								fclose($fp);
				}
			}
			if (stripos( $get[1], 'JOIN' ) !== false) {
				foreach($commands as $key=>$value){
				$command[$key]=str_replace($cmdsym,"${afcolor}${cmdsym}${scolor}",$value);
				}
				$cmdlist = implode("${afcolor},${scolor} ", $command); 
				fputs($socket,"CNOTICE $nick $jchan : ${afcolor}#${scolor}Titanfall.Pug Commands${afcolor}:${scolor} $cmdlist \n");
			}
                //This is where we start processing the commands we entered in earlier
                if (in_array($text,$commands)) {
                    //switch-case structure, each case sorresponds to each enlisted commands.
                    switch(rtrim($text)) {
					//check if pug is in progress

				//PUG BOT CODE---------------------------------------------------------
					case "${cmdsym}commands":
						fputs($socket,"CNOTICE $nick $chan : ${afcolor}#${scolor}Titanfall.Pug Commands${afcolor}:${scolor} $cmdlist \n");
						break;
					case "${cmdsym}promote":
					$pinp = puglock($pugmax);
						if ($pinp !== TRUE) {
					$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$needed = $pugmax - count($line);
						fputs($socket,"NOTICE $jchan : ${afcolor}#${scolor}Titanfall${afcolor}.${scolor}Pug ${afcolor}-${scolor} ${scolor}Need ${afcolor}(${scolor}${needed}${afcolor})${scolor} to begin${afcolor}.\n");
						} else {
						fputs($socket,"CNOTICE $nick $chan : ${afcolor}#${scolor}Titanfall${afcolor}.${scolor}Pug ${afcolor}-${scolor} ${scolor}PUG LIST FULL${afcolor}:${scolor} Captains are choosing teams now${afcolor}.\n");
						}
						unset($needed);
						break;
					case "${cmdsym}join":
					case "${cmdsym}add":
						$tuser = $nick;
						$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						if (array_search($tuser, $line) !== FALSE) {
						fputs($socket,"CNOTICE $nick $chan : ${scolor}Your already on the list${afcolor}!\n");
						}
						else {
						$tuser = $nick . "\n";
						$pinp = puglock($pugmax);
						if ($pinp !== TRUE) {
						file_put_contents("tready.txt", $tuser, FILE_APPEND);
						$listnp = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$nplayers = $pugmax - count($listnp);								
						fputs($socket,"CNOTICE $nick $chan : ${scolor}You've been added to the list${afcolor}:${scolor} $tuser\n");
						if ($nplayers !== 0) {
							fputs($socket,"PRIVMSG $chan : ${afcolor}(${scolor}$nplayers needed to begin${afcolor})\n");
						}
						unset($line);
						sleep(1);
						//START PUG SCRIPT
						$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
								if (count($line) == "$pugmax") {
									fputs($socket,"NOTICE $jchan : ${afcolor}#${scolor}Titanfall${afcolor}.${scolor}Pug ${afcolor}-${scolor} ${scolor}PUG LIST FULL${afcolor}:${scolor} Choosing Random Captains!\n");
									   // choose random captians
									   $players = $line;
									   $random_capt = array_rand($players,2);
									   $imc_capt = $players[$random_capt[0]];
									   $militia_capt = $players[$random_capt[1]];
									   $captains = $militia_capt . "\n" . $imc_capt;
									file_put_contents("captains.txt", $captains);
									file_put_contents("militia.txt", $militia_capt . "\n");
									file_put_contents("imc.txt", $imc_capt . "\n");
									fputs($socket,"PRIVMSG $chan : ${team2}IMC Captain:${scolor} $imc_capt\n");
									fputs($socket,"PRIVMSG $chan : ${team1}Militia Captain${team1}:${scolor} $militia_capt\n");
											//remove Captains from pug list and Create Pug List
												$DELETE1 = $militia_capt;
												$DELETE2 = $imc_capt;
												$datap = file("tready.txt"); 
												$out = array(); 
												//Militia Captain Removal
												foreach($datap as $line) { 
													if(trim($line) != $DELETE1) { 
														$out[] = $line; 
													} 
												}
												
												$fp = fopen("pready.txt", "w+"); 
												flock($fp, LOCK_EX); 
												foreach($out as $line) { 
													fwrite($fp, $line); 
												} 
												flock($fp, LOCK_UN); 
												fclose($fp);
												unset ($datap);
												sleep(1);
												//IMC Captain Removal
												$datap = file("pready.txt"); 
												$out = array(); 
												foreach($datap as $line) { 
													if(trim($line) != $DELETE2) { 
														$out[] = $line; 
													} 
												} 

												$fp = fopen("pready.txt", "w+"); 
												flock($fp, LOCK_EX); 
												foreach($out as $line) { 
													fwrite($fp, $line); 
												} 
												flock($fp, LOCK_UN); 
												fclose($fp);
								fputs($socket,"PRIVMSG $chan : ${scolor}Captains removed from Pug choices${afcolor}. ${scolor}Captains coin toss now${afcolor}......\n");	
									sleep(1);
									unset($line);
									$line = file("captains.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
										$players = $line;
										$random_capt = array_rand($players,1);
										$winner = $players[$random_capt];
										$militia_team = file("militia.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
									file_put_contents("pick.txt", $winner);
								if (array_search($winner, $militia_team) !== FALSE) {
									$team = "${team1}Militia";
									$ttheme = "${team1}";
									} else { 
									$team = "${team2}IMC";
									$ttheme = "${team2}";
									}
									sleep(3);
									fputs($socket,"PRIVMSG $chan : ${scolor}First to choose${ttheme}: $winner - $team\n");
									$listlft = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
											foreach($listlft as $index => $entry)
												{
													$index = $index+1;
													$listnlft .= "${ttheme}[${scolor}" . $index . "${ttheme}]${scolor} " . $entry . " ";
												}
											$listnlft = substr($listnlft, 0 , -1);
											fputs($socket,"CNOTICE $winner $jchan : Picks${ttheme}: $listnlft\n");
								}
								//END PUG SCRIPT
						}
						else { 
						$tuser = $nick;
						$slist = file("sready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$line = file("sready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$slistc = $slist;
						$slist = implode("${afcolor},${scolor} ", $slist);
						if (array_search($tuser, $line) !== FALSE) {
							fputs($socket,"CNOTICE $nick $chan : ${scolor}Your already on the side list${afcolor}!\n");
							fputs($socket,"CNOTICE $nick $chan : ${scolor}Side List${afcolor}: $slist\n"); 
						}
						else {
							$line = file("sready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
							$pinp = puglock($pugmax, "sready.txt");
						if ($pinp !== TRUE) {
											$tuser = $nick . "\n";
											file_put_contents("sready.txt", $tuser, FILE_APPEND);
											sleep(1);
									unset($slist);
											$slist = file("sready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
											$slistc = $slist;
											$slist = implode("${afcolor},${scolor} ", $slist);
												fputs($socket,"CNOTICE $nick $chan : ${scolor}There is currently a PUG in progress your name has been added to the side list${afcolor}.\n"); 
												fputs($socket,"CNOTICE $nick $chan : ${scolor}Side List${afcolor}:${scolor} $slist\n");
								} 
								else {
								unset($slist);
								$slist = file("sready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
								$slistc = $slist;
								$slist = implode("${afcolor},${scolor} ", $slist);
									fputs($socket,"CNOTICE $nick $chan : ${scolor}There is currently a PUG in progress and the side list is full... Please wait${afcolor}.\n"); 
									fputs($socket,"CNOTICE $nick $chan : ${scolor}Side List${afcolor}:${scolor} $slist\n"); }
						  }
						} 
					}
                        unset($tuser, $slist, $listnlft, $listlft, $slist);
                        break;
					case "${cmdsym}coms":
					case "${cmdsym}voip":
						$list = file("coms.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$list = implode("${afcolor}||${scolor} ", $list); 
						fputs($socket,"PRIVMSG $chan : ${scolor}VOIP${afcolor}:${scolor} $list\n"); 
						unset($list);
						break;
					case "${cmdsym}last":
						$militia_last = file("militia-last.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$militia_last = implode("${scolor} | ${team1}", $militia_last); 
						$imc_last = file("imc-last.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$imc_last = implode("${scolor} | ${team2}", $imc_last); 
						fputs($socket,"PRIVMSG $chan : ${team1}Militia Team -- ${scolor}[${team1}C${scolor}]${team1} $militia_last\n");
						fputs($socket,"PRIVMSG $chan : ${team2}IMC Team -- ${scolor}[${team2}C${scolor}]${team2} $imc_last\n");
						unset($militia_last, $imc_last);
						break;
					case "${cmdsym}list":
					$pinp = puglock($pugmax);
					if ($pinp !== TRUE) {
						$list = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$listn = implode("${afcolor} | ${scolor}", $list); 
						if (empty($list)) { 
						fputs($socket,"PRIVMSG $chan : ${scolor}Currently no players are waiting${afcolor}! -- ${scolor}To add yourself to the list use the following commands${afcolor}: $cmdsym${scolor}add \n");
						}
						else {
							$nplayers = $pugmax - count($list);
						fputs($socket,"PRIVMSG $chan : ${scolor}Currently waiting ${afcolor}(${scolor}$nplayers needed to begin${afcolor}):${scolor} $listn\n");
						}
						} else {
						$tuser = $nick;
						$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);	
						if (array_search($tuser, $line) !== FALSE) {
							
						$list = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						
						foreach($list as $index => $entry)
							{
								$index = $index+1;
								$listn .= "${afcolor}[${scolor}" . $index . "${afcolor}]${scolor} " . $entry . " ";
							}

							$listn = substr($listn, 0 , -1);

						fputs($socket,"CNOTICE $nick $chan : ${scolor}PUG - picks left${afcolor}:${scolor} $listn\n"); 
						} else {
						$list = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$listn = implode("${afcolor} |${scolor} ", $list); 
						fputs($socket,"PRIVMSG $chan : ${scolor}Players in current PUG${afcolor}:${scolor} $listn\n");
							}
						}
						unset($list, $listn);
						break;
					case "${cmdsym}pick":
					$pinp = puglock($pugmax);
					$puser = rtrim($get[4])-1;
					$pusergt = rtrim($get[4])-1;					
					$listgt = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
							$listgt1 = implode(", ", $listgt );
							$getname = explode(", ", $listgt1);
							$puser = rtrim($getname[$puser]);
							$apuser = $puser . "\n";
					$pline = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
					if ($pinp) {
						//Check picker for right picker
						$picker = $nick;
						$checkp = file("pick.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$apicker = implode(" ", $checkp);
						if (array_search($picker, $checkp) !== FALSE) {
							//Check picker team
							$team = file("militia.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
							if (array_search($picker, $team) !== FALSE) {
								$team = "${afcolor}Militia";
								$teamfile = "militia.txt"; 
								$ttheme = "${team1} |${scolor} ";
								$tcolor = "${team1}";
								}
								else
								{
								$team = "${team2}IMC";
								$teamfile = "imc.txt";
								$ttheme = "${team2} |${scolor} ";
								$tcolor = "${team2}";
								}
							if (!empty($puser) && is_numeric($pusergt)) {
								if (array_search($puser, $pline) !== FALSE) {
									file_put_contents($teamfile, $apuser, FILE_APPEND);
									//Remove user from pug list
												$DELETE1 = $puser;
												$datap = file("pready.txt"); 
												$out = array(); 

												foreach($datap as $line) { 
													if(trim($line) != $DELETE1) { 
														$out[] = $line; 
													} 
												}
												
												$fp = fopen("pready.txt", "w+"); 
												flock($fp, LOCK_EX); 
												foreach($out as $line) { 
													fwrite($fp, $line); 
												} 
												flock($fp, LOCK_UN); 
												fclose($fp);
												
									$tlist = file($teamfile, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
									$tlistn = implode($ttheme, $tlist); 
										fputs($socket,"CNOTICE $nick $chan : ${scolor}$puser has been added to $team ${scolor}team.\n");
										fputs($socket,"CNOTICE $puser $chan : ${scolor}$nick has picked you for $team ${scolor}team.\n");
										fputs($socket,"PRIVMSG $chan : ${scolor}Currently on $team:${scolor} [${tcolor}C${scolor}] $tlistn\n");
										unset($teamfile, $ttheme);
								//Next Picker
									$pick = file("pick.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
									if (array_search($picker, $pick) !== FALSE) {
												$datap = file("captains.txt"); 
												$out = array(); 
												foreach($datap as $line) { 
													if(trim($line) != $picker) { 
														$out[] = $line; 
													} 
												} 

												$fp = fopen("pick.txt", "w+"); 
												flock($fp, LOCK_EX); 
												foreach($out as $line) { 
													fwrite($fp, $line); 
												} 
												flock($fp, LOCK_UN); 
												fclose($fp);
												sleep(1);
										unset($pick);
										$pick = file("pick.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
										$npicker = implode(" ", $pick);
										//Check team of new picker
										$team = file("militia.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
										if (array_search($npicker, $team) !== FALSE) {
												$team = "${team1}Militia";
												$teamc = "${team1}";
												}
												else
												{
												$team = "${team2}IMC";
												$teamc = "${team2}";
												}
										$pready = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
										if (count($pready) !== 1) {
										sleep(1);
										fputs($socket,"PRIVMSG $chan : ${teamc}$npicker ${scolor}is now choosing for${teamc}: $team\n");
											$listlft = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
											foreach($listlft as $index => $entry)
												{
													$index = $index+1;
													$listnlft .= "${teamc}[${scolor}" . $index . "${teamc}]${scolor} " . $entry . " ";
												}
											$listnlft = substr($listnlft, 0 , -1);
											fputs($socket,"CNOTICE $npicker $chan : Picks${ttheme}: $listnlft\n");
											unset($listnlft, $listlft);
										} else {
										//Last to be picked
										$lplist = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
										$ltbp = implode(" ", $lplist); 
									    $team = file("militia.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
											if (array_search($npicker, $team) !== FALSE) {
												$team = "${team1}Militia";
												$teamfile = "militia.txt"; 
												$ttheme = "${team1} |${scolor} ";
												$tcolor = "${team1}";
												}
												else
												{
												$team = "${team2}IMC";
												$teamfile = "imc.txt";
												$ttheme = "${team2} |${scolor} ";
												$tcolor = "${team2}";
												}
										//put last pick on right team
										$altbp = $ltbp . "\n";
										file_put_contents($teamfile, $altbp, FILE_APPEND);
											fputs($socket,"CNOTICE $ltbp $chan : ${tcolor}$ltbp${scolor} you've been AUTO added to $team${scolor}.\n");
											fputs($socket,"CNOTICE $npicker $chan : ${scolor}$ltbp has been AUTO added to $team${scolor} as last pick.\n");
											
										//POST TEAMS
										sleep(1);
										//Finish up and clean files
										copy("militia.txt", "militia-last.txt");
										copy("imc.txt", "imc-last.txt");
										
										$militia_last = file("militia-last.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
										$militia_last = implode("${scolor} |${team1} ", $militia_last); 
										$imc_last = file("imc-last.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
										$imc_last = implode("${scolor} |${team2} ", $imc_last); 
										fputs($socket,"PRIVMSG $chan : ${team1}Militia Team -- ${scolor}[${team1}C${scolor}]${team1} $militia_last\n");
										fputs($socket,"PRIVMSG $chan : ${team2}IMC Team -- ${scolor}[${team2}C${scolor}]${team2} $imc_last\n");
										sleep(5);
										unset($pinp, $altbp, $team, $teamfile, $ttheme, $tcolor, $militia_last, $imc_last, $pusergt, $listnlft, $listlft, $index);
										//clear files for next match
											file_put_contents("tready.txt", "");
											sleep(1);
											copy("sready.txt", "tready.txt");
											sleep(1);
											file_put_contents("sready.txt", "");
											file_put_contents("pready.txt", "");
											file_put_contents("militia.txt", "");
											file_put_contents("imc.txt", "");
											file_put_contents("captains.txt", "");
											file_put_contents("pick.txt", "");
											
										sleep(2);
										$pinp = puglock($pugmax,"tready.txt");
										if ($pinp == TRUE) {
												
												fputs($socket,"PRIVMSG $chan : ${scolor}Players from waiting list was full${afcolor}:${scolor} PUG START IN 10sec.\n");
													sleep(8);
													
													//START PUG SCRIPT
													$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
															if (count($line) == "$pugmax") { 
																fputs($socket,"PRIVMSG $chan : ${scolor}PUG LIST FULL${afcolor}:${scolor} Choosing Random Captains!\n");
																   // choose random captians
																   $players = $line;
																   $random_capt = array_rand($players,2);
																   $imc_capt = $players[$random_capt[0]];
																   $militia_capt = $players[$random_capt[1]];
																   $captains = $militia_capt . "\n" . $imc_capt;
																file_put_contents("captains.txt", $captains);
																file_put_contents("militia.txt", $militia_capt . "\n");
																file_put_contents("imc.txt", $imc_capt . "\n");
																fputs($socket,"PRIVMSG $chan : ${team2}IMC Captain:${scolor} $imc_capt\n");
																fputs($socket,"PRIVMSG $chan : ${team1}Militia Captain${team1}:${scolor} $militia_capt\n");
																		//remove Captains from pug list and Create Pug List
																			$DELETE1 = $militia_capt;
																			$DELETE2 = $imc_capt;
																			$datap = file("tready.txt"); 
																			$out = array(); 
																			//Militia Captain Removal
																			foreach($datap as $line) { 
																				if(trim($line) != $DELETE1) { 
																					$out[] = $line; 
																				} 
																			}
																			
																			$fp = fopen("pready.txt", "w+"); 
																			flock($fp, LOCK_EX); 
																			foreach($out as $line) { 
																				fwrite($fp, $line); 
																			} 
																			flock($fp, LOCK_UN); 
																			fclose($fp);
																			unset ($datap);
																			sleep(1);
																			//IMC Captain Removal
																			$datap = file("pready.txt"); 
																			$out = array(); 
																			foreach($datap as $line) { 
																				if(trim($line) != $DELETE2) { 
																					$out[] = $line; 
																				} 
																			} 

																			$fp = fopen("pready.txt", "w+"); 
																			flock($fp, LOCK_EX); 
																			foreach($out as $line) { 
																				fwrite($fp, $line); 
																			} 
																			flock($fp, LOCK_UN); 
																			fclose($fp);
															fputs($socket,"PRIVMSG $chan : ${scolor}Captains removed from Pug choices${afcolor}, ${scolor}Captains coin toss now${afcolor}......\n");	
																sleep(1);
																unset($line);
																$line = file("captains.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
																	$players = $line;
																	$random_capt = array_rand($players,1);
																	$winner = $players[$random_capt];
																	$militia_team = file("militia.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
																file_put_contents("pick.txt", $winner);
															if (array_search($winner, $militia_team) !== FALSE) {
																$team = "${team1}Militia";
																} else { 
																$team = "${team2}IMC";
																}
																sleep(5);
																fputs($socket,"PRIVMSG $chan : ${scolor}First to choose${afcolor}: ${scolor}$winner - $team\n");
																$listlft = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
																		foreach($listlft as $index => $entry)
																			{
																				$index = $index+1;
																				$listnlft .= "${afcolor}[${scolor}" . $index . "${afcolor}]${scolor} " . $entry . " ";
																			}
																		$listnlft = substr($listnlft, 0 , -1);
																		fputs($socket,"CNOTICE $winner $jchan : ${scolor}Picks${afcolor}:${scolor} $listnlft\n");
															}
															//END PUG SCRIPT
													}
													else {		
																fputs($socket,"PRIVMSG $chan : ${scolor}PUG BOT RESET FOR NEW MATCH${afcolor}!\n");
																fputs($socket,"PRIVMSG $chan : ${scolor}Use ${afcolor}$cmdsym${scolor}add to add yourself to the waiting list${afcolor}.\n");
															} 
									}
										
								
										}
								}
								else {
								fputs($socket,"CNOTICE $nick $chan : ${scolor}The Number you have chosen is no longer available${afcolor}:${scolor} $pusergt ${afcolor}|| ${scolor} ${cmdsym}pick <NUMBER from ${cmdsym}list>\n");
								} 
							
							}
							else {
								fputs($socket,"CNOTICE $nick $chan : ${scolor}To use this command follow this syntax${afcolor}:${scolor} ${cmdsym}pick <NUMBER from ${cmdsym}list> (ex: ${cmdsym}pick 1)\n");
								}
							} 
							else 
							{ fputs($socket,"CNOTICE $nick $chan : ${scolor}Your not the current Captain picking${afcolor}:${scolor} $apicker\n"); 
								}
					
					} 
					else { 
						fputs($socket,"CNOTICE $nick $chan : ${scolor}Still waiting for players to start!${afcolor}.\n"); 
						}
                        unset($puser, $picker, $team);
                        break;
					case "${cmdsym}remove":
						$auser = $nick;    
						$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						if (array_search($auser, $line) !== FALSE) {
							fputs($socket,"CNOTICE $auser $chan : You have been removed from the list!\n");
								$DELETE = $auser; 
								$datap = file("tready.txt"); 
								$out = array(); 

								foreach($datap as $line) { 
									if(trim($line) != $DELETE) { 
										$out[] = $line; 
									} 
								} 

								$fp = fopen("tready.txt", "w+"); 
								flock($fp, LOCK_EX); 
								foreach($out as $line) { 
									fwrite($fp, $line); 
								} 
								flock($fp, LOCK_UN); 
								fclose($fp);
						$list = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$nplayers = $pugmax - count($list);								
						fputs($socket,"PRIVMSG $chan : ${afcolor}(${scolor}$nplayers needed to begin${afcolor})\n");								
							}
						else {
							fputs($socket,"CNOTICE $nick $chan : ${scolor}Your not on the list${afcolor}:${scolor} $auser\n");
						}
                        unset($auser);
                        break;
				//END PUG BOT CODE
                    }
                }
            }
        } else 
		if (substr_count($get[2],$bnick)) { //This is like the admin panel. You can /msg the bot to command it to exit.
				$nick = explode(':',$get[0]);
				$nick = explode('!',$nick[1]);
				$nick = $nick[0]; //User who entered the command
				$nhost = explode('!', $get[0]); //User Hostname for private commands
				$nhost = $nhost[1];
				$chan = $get[2]; //the channel the bot is in
				$num = 3; //If you observe the array format, actually text starts from 3rd index.
				if ($num == 3) {
					$split = explode(':',$get[3],2);
					$text = rtrim($split[1]); //trimming is important. never forget.		
                if (in_array($text,$acommands)) {
                    //switch-case structure, each case sorresponds to each enlisted commands.
                    switch(rtrim($text)) {
				case "${cmdsym}clearlist":
					if (admincheck($nhost)) {
						file_put_contents("tready.txt", "");
						fputs($socket,"PRIVMSG $nick :All players cleared!\n");
					} else { fputs($socket,"PRIVMSG $nick : You do not have sufficient access!\n"); }
						//IMPORANT TO HAVE THE DIE(), this throws the script out of the infinite while loop!
					break;
				case "${cmdsym}remove":
				if (admincheck($nhost)) {
					$auser = rtrim($get[4]);
					$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
					if (array_search($auser, $line) !== FALSE) {
						fputs($socket,"PRIVMSG $nick : $auser have been removed from the list!\n");
							$DELETE = $auser; 
							$datap = file("tready.txt"); 
							$out = array(); 

							foreach($datap as $line) { 
								if(trim($line) != $DELETE) { 
									$out[] = $line; 
								} 
							} 

							$fp = fopen("tready.txt", "w+"); 
							flock($fp, LOCK_EX); 
							foreach($out as $line) { 
								fwrite($fp, $line); 
							} 
							flock($fp, LOCK_UN); 
							fclose($fp);  
						}
					else {
						fputs($socket,"PRIVMSG $nick : ${scolor}$auser is not on the list.\n");
					}
					} else { fputs($socket,"PRIVMSG $nick : You do not have sufficient access!\n"); }
					unset($auser);
					break;
				//Setmax Player script
				case "${cmdsym}setmax":
				if (admincheck($nhost)) {
					$setmax = rtrim($get[4]);
					if (!empty($setmax) && is_numeric($setmax) && isEven($setmax)) {
					if (checkplayercnt($setmax) == "START") {
						unset($pugmax);
						$pugmax = $setmax;
						$vs = checkvs($pugmax);
						fputs($socket,"PRIVMSG $nick : ${scolor}Max players to start PUG set to${afcolor}:${scolor} $setmax - $vs\n");
						fputs($socket,"PRIVMSG $jchan : ${scolor}Max players to start PUG set to${afcolor}:${scolor} $setmax - $vs\n");
							$chan2 = $jchan;
							$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
									   // choose random captians
									   $players = $line;
									   $random_capt = array_rand($players,2);
									   $imc_capt = $players[$random_capt[0]];
									   $militia_capt = $players[$random_capt[1]];
									   $captains = $militia_capt . "\n" . $imc_capt;
									file_put_contents("captains.txt", $captains);
									file_put_contents("militia.txt", $militia_capt . "\n");
									file_put_contents("imc.txt", $imc_capt . "\n");
									fputs($socket,"PRIVMSG $chan2 : ${team2}IMC Captain:${scolor} $imc_capt\n");
									fputs($socket,"PRIVMSG $chan2 : ${team1}Militia Captain${team1}:${scolor} $militia_capt\n");
											//remove Captains from pug list and Create Pug List
												$DELETE1 = $militia_capt;
												$DELETE2 = $imc_capt;
												$datap = file("tready.txt"); 
												$out = array(); 
												//Militia Captain Removal
												foreach($datap as $line) { 
													if(trim($line) != $DELETE1) { 
														$out[] = $line; 
													} 
												}
												
												$fp = fopen("pready.txt", "w+"); 
												flock($fp, LOCK_EX); 
												foreach($out as $line) { 
													fwrite($fp, $line); 
												} 
												flock($fp, LOCK_UN); 
												fclose($fp);
												unset ($datap);
												sleep(1);
												//IMC Captain Removal
												$datap = file("pready.txt"); 
												$out = array(); 
												foreach($datap as $line) { 
													if(trim($line) != $DELETE2) { 
														$out[] = $line; 
													} 
												} 

												$fp = fopen("pready.txt", "w+"); 
												flock($fp, LOCK_EX); 
												foreach($out as $line) { 
													fwrite($fp, $line); 
												} 
												flock($fp, LOCK_UN); 
												fclose($fp);
										fputs($socket,"PRIVMSG $chan2 : ${scolor}Captains removed from Pug choices${afcolor}, ${scolor}Captains coin toss now${afcolor}......\n");	
											sleep(1);
											unset($line);
											$line = file("captains.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
												$players = $line;
												$random_capt = array_rand($players,1);
												$winner = $players[$random_capt];
												$militia_team = file("militia.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
											file_put_contents("pick.txt", $winner);
										if (array_search($winner, $militia_team) !== FALSE) {
											$team = "${team1}Militia";
											} else { 
											$team = "${team2}IMC";
									}
									sleep(5);
									fputs($socket,"PRIVMSG $chan2 : ${scolor}First to choose${afcolor}: ${scolor}$winner - $team\n");
									$listlft = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
											foreach($listlft as $index => $entry)
												{
													$index = $index+1;
													$listnlft .= "${afcolor}[${scolor}" . $index . "${afcolor}]${scolor} " . $entry . "${afcolor},${scolor} ";
												}
											$listnlft = substr($listnlft, 0 , -11);
											fputs($socket,"PRIVMSG $chan2 : ${scolor}Currently on the PUG List${afcolor}:${scolor} $listnlft\n");
								 
									}
								elseif (checkplayercnt($setmax) == "MORE") {
									$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
									   $currentcnt = count($line);
									   $vs = checkvs($pugmax);
									fputs($socket,"PRIVMSG $nick : ${scolor}Cannot set max players to ${afcolor}$setmax${scolor} because there are currently${afcolor} $currentcnt ${scolor}waiting on the list ${afcolor}!${scolor}setmax stays at${afcolor}:${scolor} $pugmax - $vs\n");
									}
								else {
									unset($pugmax);
									$pugmax = $setmax;
									$vs = checkvs($pugmax);
									fputs($socket,"PRIVMSG $nick : ${scolor}Max players to start PUG set to${afcolor}:${scolor} $setmax - $vs\n");
									fputs($socket,"PRIVMSG $jchan : ${scolor}Max players to start PUG set to${afcolor}:${scolor} $setmax - $vs\n");
								}
									
					} else { fputs($socket,"PRIVMSG $nick : ${scolor}Incorect syntac${afcolor}: $cmdsym${scolor}setmax ${afcolor}<${scolor}EVEN-NUMBER${afcolor}> (${scolor}number cannot be blank${afcolor})${scolor} Current setting${afcolor}:${scolor} $pugmax \n"); 
						}
					}
					else { fputs($socket,"PRIVMSG $nick : You do not have sufficient access!\n"); }
					unset($pugmaxo,$setmax);
					break;
				case "${cmdsym}clearpugfiles":
				if (admincheck($nhost)) {
							file_put_contents("tready.txt", "");
							file_put_contents("pready.txt", "");
							file_put_contents("militia.txt", "");
							file_put_contents("imc.txt", "");
							file_put_contents("captains.txt", "");
							file_put_contents("pick.txt", "");
							fputs($socket,"PRIVMSG $nick : ${scolor}All PUG FILES CLEARED!${afcolor}!\n");
					} 
					else { fputs($socket,"PRIVMSG $nick : You do not have sufficient access!\n"); }
					break;
				case "${cmdsym}cmd":
				if (admincheck($nhost)) {
						$inputcmd = rtrim($get[4]);
						$input = rtrim($get[5]);
						fputs($socket,"$inputcmd $input\n");
							}
					else { fputs($socket,"PRIVMSG $nick : You do not have sufficient access!\n"); }
						unset($input, $inputcmd);
						break;
				case "${cmdsym}addadmin":
				if (admincheck($nhost)) {
						$aduser = rtrim($get[4]);
					if (!empty($aduser)) {
							$admin = file("admins.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
							if (array_search($aduser, $admin) !== FALSE) {
							fputs($socket,"CNOTICE $nick $jchan : ${scolor} $aduser is already on the admin list${afcolor}!\n");
							}
							else {
							$aduser = $aduser . "\n";
							file_put_contents("admins.txt", $aduser, FILE_APPEND);
							fputs($socket,"CNOTICE $nick $jchan : ${scolor}Added new admin${afcolor}:${scolor} $aduser\n");
							} 
						} else 
						{ fputs($socket,"PRIVMSG $nick : ${scolor}Incorect syntac${afcolor}: $cmdsym${scolor}addadmin ${afcolor}<${scolor}HOSTNAME${afcolor}> (${scolor}ex${afcolor}: $cmdsym${scolor}addadmin $nhost${afcolor})\n"); 
						}
						} else { fputs($socket,"PRIVMSG $nick : You do not have sufficient access!\n"); }
                      unset($aduser);
                      break;
			case "${cmdsym}commands":
				if (admincheck($nhost)) {
						$acmdlist = implode("${afcolor},${scolor} ", $acommands);
						fputs($socket,"PRIVMSG $nick : ${scolor}Private Commands: $acmdlist\n");
						} else { fputs($socket,"PRIVMSG $nick : You do not have sufficient access!\n"); }
                        unset($aduser);
                      break;
            }
        } } }
        //Shows the text in the browser as Time - Text
        //this command logs the channel.
        echo date('G:i:s')."-".$data;

        //Flush it out to the browser
        flush();
    }
}

//PUG Bot Functions
function checkvs($plcount) {
		$vs = $plcount / 2;
		$vs = "${vs}v${vs}";
		
	return $vs;
}	
function chnickchange($line = "tready.txt", $nuser) {
	$line = file($line, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
			if (array_search($nuser, $line) !== FALSE) {
			return true;
			}
}

function nickchange($file, $nuser, $tnuser) {
	$file_r = file($file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
			if (array_search($nuser, $file_r) !== FALSE) {
						$atnuser = $tnuser . "\n";
						file_put_contents($file, $atnuser, FILE_APPEND);
					
						$DELETE = $nuser; 
						$datap = file($file); 
						$out = array(); 

						foreach($datap as $lines) { 
							if(trim($lines) != $DELETE) { 
								$out[] = $lines; 
							} 
						} 

						$fp = fopen($file, "w+"); 
						flock($fp, LOCK_EX); 
						foreach($out as $lines) { 
							fwrite($fp, $lines); 
						} 
						flock($fp, LOCK_UN); 
						fclose($fp); 
				}
	}
	
function checkplayercnt($pugmax) {
$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
if (count($line) == "$pugmax") { 
					$result = "START";
						}
elseif (count($line) > "$pugmax") {
					$result = "MORE";
				}
else { 				$result = false; 
}
		return $result;
				
}

function removeuser($user,$file,$level = 0)
//null function at the moment not being used in the base script anywhere
{ 
if ($level == 0) {
	$file_r = file($file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
			if (array_search($user, $file_r) !== FALSE) {
								$DELETE = $user; 
								$datap = file($file_r); 
								$out = array(); 

								foreach($datap as $line) { 
									if(trim($line) != $DELETE) { 
										$out[] = $line; 
									} 
								} 

								$fp = fopen($file_r, "w+"); 
								flock($fp, LOCK_EX); 
								foreach($out as $line) { 
									fwrite($fp, $line); 
								} 
								flock($fp, LOCK_UN); 
								fclose($fp);  }
	} elseif($level > 0) {
			 if($level == 1) {
								$DELETE = $partuser; 
								$datap = file($file_r); 
								$out = array(); 

								foreach($datap as $line) { 
									if(trim($line) != $DELETE) { 
										$out[] = $line; 
									} 
								} 

								$fp = fopen($file_r, "w+"); 
								flock($fp, LOCK_EX); 
								foreach($out as $line) { 
									fwrite($fp, $line); 
								} 
								flock($fp, LOCK_UN); 
								fclose($fp);
							    $userstatus = 1;
								$anuto = $file;
			
				}
				if($level == 2) {
								$DELETE = $partuser; 
								$datap = file($file_r); 
								$out = array(); 

								foreach($datap as $line) { 
									if(trim($line) != $DELETE) { 
										$out[] = $line; 
									} 
								} 

								$fp = fopen($file_r, "w+"); 
								flock($fp, LOCK_EX); 
								foreach($out as $line) { 
									fwrite($fp, $line); 
								} 
								flock($fp, LOCK_UN); 
								fclose($fp);
							    $userstatus = 2;
			
				}
		}
		unset($user, $file, $file_r, $datap, $out, $fp, $line);
}
	
function puglock($pugmax, $file="tready.txt") 
{
	echo "$pugmax";
		$line = file($file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		if (count($line) == "$pugmax") {
		$locked = TRUE;
		}
	return $locked;
}
function admincheck($nhost) {
$admin = file("admins.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
$nhost = str_replace("~", "", $nhost);
if (array_search(strtolower($nhost), str_replace("~", "", array_map('strtolower', $admin))) !== FALSE) {
return true;
	}
}
function object_to_array($data) // Converts a Nested stdObject to a full associative Array
{ // Not used everywhere, because found this solution much later
    if(is_array($data) || is_object($data)) //
    {
        $result = array();
        foreach($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}
function isEven($number) {
    $isEven = false;
    if (is_numeric ($number)) {
        if ( $number % 2 == 0) $isEven = true;
    }
    return $isEven;
}

function startpugck() {
$line = file("tready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
								if (count($line) == "$pugmax") { 
									fputs($socket,"PRIVMSG $chan : ${scolor}PUG LIST FULL${afcolor}:${scolor} Choosing Random Captains!\n");
									   // choose random captians
									   $players = $line;
									   $random_capt = array_rand($players,2);
									   $imc_capt = $players[$random_capt[0]];
									   $militia_capt = $players[$random_capt[1]];
									   $captains = $militia_capt . "\n" . $imc_capt;
									file_put_contents("captains.txt", $captains);
									file_put_contents("militia.txt", $militia_capt . "\n");
									file_put_contents("imc.txt", $imc_capt . "\n");
									fputs($socket,"PRIVMSG $chan : ${team2}IMC Captain:${scolor} $imc_capt\n");
									fputs($socket,"PRIVMSG $chan : ${team1}Militia Captain${team1}:${scolor} $militia_capt\n");
											//remove Captains from pug list and Create Pug List
												$DELETE1 = $militia_capt;
												$DELETE2 = $imc_capt;
												$datap = file("tready.txt"); 
												$out = array(); 
												//Militia Captain Removal
												foreach($datap as $line) { 
													if(trim($line) != $DELETE1) { 
														$out[] = $line; 
													} 
												}
												
												$fp = fopen("pready.txt", "w+"); 
												flock($fp, LOCK_EX); 
												foreach($out as $line) { 
													fwrite($fp, $line); 
												} 
												flock($fp, LOCK_UN); 
												fclose($fp);
												unset ($datap);
												sleep(1);
												//IMC Captain Removal
												$datap = file("pready.txt"); 
												$out = array(); 
												foreach($datap as $line) { 
													if(trim($line) != $DELETE2) { 
														$out[] = $line; 
													} 
												} 

												$fp = fopen("pready.txt", "w+"); 
												flock($fp, LOCK_EX); 
												foreach($out as $line) { 
													fwrite($fp, $line); 
												} 
												flock($fp, LOCK_UN); 
												fclose($fp);
								fputs($socket,"PRIVMSG $chan : ${scolor}Captains removed from Pug choices${afcolor}, ${scolor}Captains coin toss now${afcolor}......\n");	
									sleep(1);
									unset($line);
									$line = file("captains.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
										$players = $line;
										$random_capt = array_rand($players,1);
										$winner = $players[$random_capt];
										$militia_team = file("militia.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
									file_put_contents("pick.txt", $winner);
								if (array_search($winner, $militia_team) !== FALSE) {
									$team = "${team1}Militia";
									} else { 
									$team = "${team2}IMC";
									}
									sleep(5);
									fputs($socket,"PRIVMSG $chan : ${scolor}First to choose${afcolor}: ${scolor}$winner - $team\n");
									$listlft = file("pready.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
											foreach($listlft as $index => $entry)
												{
													$index = $index+1;
													$listnlft .= "${afcolor}[${scolor}" . $index . "${afcolor}]${scolor} " . $entry . "${afcolor},${scolor} ";
												}
											$listnlft = substr($listnlft, 0 , -11);
											fputs($socket,"CNOTICE $winner $jchan : ${scolor}Picks${afcolor}:${scolor} $listnlft\n");
								}
	}

?>