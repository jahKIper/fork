<?php
	if(!function_exists('pcntl_fork'))die("В PHP не подключен модуль PCNTL\n");
	if(!function_exists('posix_getpid'))die("В PHP не подключен модуль POSIX но есть PCNTL\n");

/**
 * Example of function that calls in parallel process
 * @param string/array $dopParam additional parametrs
 */
	function doTest($dopParam = ''){
		global $masSharedMemory;

		$myPid = posix_getpid();

		echo "Start $myPid\n";
		var_dump($dopParam);
		echo "\n";
		print_r($masSharedMemory[0]);
		echo "--\n";
		if($masSharedMemory[0] !== 'Nothing2Do'){
			sleep(rand(1,10));
		}
		echo "End $myPid\n";
	}

/**
 * Function that creates parallel processes/forks
 * @param string 	$handler 		Name of function to run in parallel processes
 * @param array 	$handlerOptions	Operation options
 * @param string/array 	$dopParam 	additional parametrs
 * @param integer 	$PROCESSES_NUM 	Maximum count of parallel processes/forks
 */
	function doParallel($handler, $handlerOptions, $dopParam = '', $PROCESSES_NUM = 5){
		global $masSharedMemory;
		$masQueue = array();

		$handlerOptions = (array)$handlerOptions;
		if(count($handlerOptions) > $PROCESSES_NUM){
			for($i = $PROCESSES_NUM; $i < count($handlerOptions); $i++){
				$masQueue[] = $handlerOptions[$i];
			}
		}

		for ($proc_num = 0; $proc_num < $PROCESSES_NUM; $proc_num++) {
		if($proc_num >= count($handlerOptions)) break;
			$pid = pcntl_fork();
			$masSharedMemory[$pid] = $handlerOptions[$proc_num];
			if ($pid < 0) {
				fwrite(STDERR, "Cannot fork\n");
				exit(1);
			}
			if ($pid == 0) {
				break;
			}
		}

		if ($pid) {
			for ($i = 0; $i < $PROCESSES_NUM; $i++) {
				pcntl_wait($status);
				$exitcode = pcntl_wexitstatus($status);
				if ($exitcode) exit(1);
			}
			if(count($masQueue) > 0 ){
				doParallel($handler,$masQueue, $dopParam, $PROCESSES_NUM);
			}
			return;
		}

		call_user_func($handler, $dopParam);
		exit(0);
	}

/**
 * Function terminates current child fork
 */
	function _exit(){
		posix_kill(posix_getpid(), SIGTERM);
	}

/**
 * Shared Memory for all procs
 */
	$masSharedMemory = array();

//----------------------------------


/**
*example
*/
$masParamz = array(
	'0' => 'id1',
	'1' => 'id2',
	'2' => 'id3',
	'3' => 'id4',
	'4' => 'id5',
	'5' => 'id6',
	'6' => 'id7',
	'7' => 'id8',
	'8' => 'id9',
	'9' => 'id10',
	'10' => 'id11',
	'11' => 'id12',
	'12' => 'id13',
	'13' => 'id14',
	'14' => 'id15',
	'15' => 'id16',
	'16' => 'id17',
	'17' => 'id18',
);

doParallel('doTest', $masParamz, 'DOP_PARAMETR');

echo "---\n";

?>