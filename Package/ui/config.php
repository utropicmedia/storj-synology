<?php

# ------------------------------------------------------------------------
#  Set environment variables and paths	(Specific to Synology)
# ------------------------------------------------------------------------
$filename = "config.json";
#$platformBase   = $_SERVER['DOCUMENT_ROOT'];
#$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
#$scriptsBase    = $moduleBase . '/scripts' ;

$platformBase	= '/volume1/@appstore/' ;
$moduleBase	= $platformBase . "StorJ/" ;
$uiBase		= $moduleBase . "ui/" ;
$scriptsBase	= $moduleBase . "scripts/" ;



$file           = $uiBase      . $filename  ;
$startScript    = $scriptsBase . 'storagenodestart.sh' ;
$stopScript     = $scriptsBase . 'storagenodestop.sh' ;
$updateScript	= $scriptsBase . 'storagenodeupdate.sh' ;
$checkScript    = $scriptsBase . 'checkStorj.sh' ;
#$storageBinary  = $scriptsBase . 'storagenode' ;
#$yamlPath	= $scriptsBase . 'docker-compose_base.yml' ;
logMessage("------------------------------------------------------------------------------");
logMessage("Platform Base($platformBase), ModuleBase($moduleBase) scriptBase($scriptsBase)\n");
# ------------------------------------------------------------------------																						 




if(isset($_POST['isajax']) && ($_POST['isajax'] == 1)) {
    logMessage("config called up with isajax 1 ");
    logEnvironment() ;


    $_address  = $_POST["address"];
    $_wallet   = $_POST["wallet"];
    $_storage  = $_POST["storage"];
    $_bandwidth      = $_POST["bandwidth"];
    $_emailId      = $_POST["email_val"];
    $_directory      = $_POST["directory"];
    $_identity_directory = $_POST['identityDirectory'];
   














//Changing permissions of the shell script
    shell_exec("chmod 777 $startScript 2>&1");
    shell_exec("chmod 777 $stopScript 2>&1");
	shell_exec("chmod 666 $file 2>&1");
    
    // set_time_limit(300);
	$properties = array(
	    'Identity'	=> "$_identity_directory",
	    'Port'	=> $_address,
	    'Wallet'	=> $_wallet,
	    'Allocation'=> $_storage,
	    'Bandwidth'	=> $_bandwidth,
	    'Email'	=> $_emailId,
	    'Directory' => "$_directory"
	    );
    file_put_contents($file, json_encode($properties));
	$servername = preg_replace( '/:.*$/', '', $_SERVER['HTTP_HOST']);																 
	$output = shell_exec("/bin/bash $startScript $_address $_wallet $_emailId $_bandwidth $_storage $_identity_directory $_directory 2>&1 ");

	/* Update File again with Log value as well */
    $properties['last_log'] = $output ;
    file_put_contents($file, json_encode($properties));					  
  }else if(isset($_POST['isstopAjax']) && ($_POST['isstopAjax'] == 1)){
	 $content = file_get_contents($file);
    $properties = json_decode($content, true);									
    logMessage("config called up with isStopAjax 1 ");
    $output = shell_exec("bash $stopScript 2>&1 ");
	/* Update File again with Log value as well */
    $properties['last_log'] = $output ;
    file_put_contents($file, json_encode($properties));											  
  }else if(isset($_POST['isUpdateAjax']) && ($_POST['isUpdateAjax'] == 1)){
    $content = file_get_contents($file);
    $properties = json_decode($content, true);							
    logMessage("config called up with isUpdateAjax 1 ");
    $server_address = $_SERVER['SERVER_ADDR'] ;
    $output = shell_exec("/bin/bash $updateScript $file $_address $_wallet $_emailId $_bandwidth $_storage $_identity_directory $_directory $server_address 2>&1");
	/* Update File again with Log value as well */
    $properties['last_log'] = $output ;
    file_put_contents($file, json_encode($properties));											  
  } else if(isset($_POST['isstartajax']) && ($_POST['isstartajax'] == 1)) {
    logMessage("config called up with isstartajax 1 ");
	
	$content = file_get_contents($file);
    $prop = json_decode($content, true);	
	
    $output = "While enabling storagestart\n" . shell_exec("/bin/bash $checkScript 2>&1 ");
    $output = "<br><b>LATEST LOG :</b> <br><code>" . $prop['last_log'] . "</code>";
    $output = preg_replace('/\n/m', '<br>', $output);																  												 
    if (!trim($output) == "") {
    echo $output;
  } else {
	echo $output;
    }
 } else {
  // DEFAULT : Load contents at start		  									 
    logMessage("config called up with for loading ");
// checking if file exists.
  if(file_exists($file)){
	$content = file_get_contents($file);
	$prop = json_decode($content, true);
	logMessage("Loaded properties : " . print_r($prop, true));
	$data = array_values($prop);
  }							 
{
?>
<?php include 'header.php';?>
<style>
code {
        white-space: pre-wrap; /* preserve WS, wrap as necessary, preserve LB */
        /* white-space: pre-line; /* collapse WS, preserve LB */
}
</style>
<link href="./resources/css/config.css" type="text/css" rel="stylesheet">
  <div>
    <nav class="navbar">
      <a class="navbar-brand" href="index.php"><img src="./resources/img/logo.svg" /></a>
    </nav>
    <div class="row">
      <?php include 'menu.php'; ?>
          <?php


          if ( $output ){

          } else {

          ?>
          <div class="col-10 config-page">
            <div class="container-fluid">
              <h2>Setup</h2>
              <a href="https://documentation.storj.io/" target="_blank"><p class="header-link">Documentation ></p></a>
                <div class="row segment" id="identityrow">
                  <div class="column col-md-2"><div class="segment-icon identity_icon"></div>

                  </div>
                  <div class="column col-md-10">
                    <h4 class="segment-title">Identity</h4>
                    <p class="segment-msg">Every node is required to have a unique identifier on the network. If you haven't already, get an authorization token. Please get the authorization token and create identity on host machine other than NAS.</p>
                    <span id="idetityval"></span><span style="display:none;" id="editidentitybtn"><button class="segment-btn" data-toggle="modal" data-target="#identity">
                      Edit Identity Path
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#identity" id="identitybtn">
                    Set Identity Path
                    </button>
                    <div class="modal fade" id="identity" tabindex="-1" role="dialog" aria-labelledby="identity" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                          <h5 class="modal-title">Identity Folder path</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Identity Path</p>
                            <input class="modal-input" type="text" id="identity_token" name="identity_token" placeholder="/path/to/identity" value="<?php if(isset($data[0])) echo $data[0] ?>"/>
                            <p class="identity_token_msg msg" style="display:none;">This is required Field</p>
                            <span class="identity_note"><span>Note:</span> Creating identity can take several hours or even days, depending on your machines processing power & luck.</span>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_identity"> Set Identity Path</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div style="display:none" id="storjrows"> -->
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon port-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Port Forwarding</h4>
                    <p class="segment-msg">How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected.</p>
                    <span id="externalAddressval"></span><span style="display:none;" id="editexternalAddressbtn"><button class="segment-btn" data-toggle="modal" data-target="#externalAddress">
                      Edit External Address
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#externalAddress" id="externalAddressbtn">
                      Add External Address
                    </button>
                    <div class="modal fade" id="externalAddress" tabindex="-1" role="dialog" aria-labelledby="externalAddress" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Port Forwarding</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Host Address</p>
                          <input class="modal-input" id="host_address" name="host_address" type="number" step="1" min="1"  value="28967" class="quantity" placeholder="domain.ddns.net: 28967" value="<?php if(isset($data[1])) echo $data[1] ?>"/>
                            <p class="host_token_msg msg" style="display:none;">Enter Valid Host Address</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_address">Set External Address</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon wallet-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Ethereum Wallet Address</h4>
                    <p class="segment-msg">In order to recieve and hold your STORJ token payouts, you need an ERC-20 compatible wallet address.</p>
                    <span id="wallettbtnval"></span><span style="display:none;" id="editwallettbtn"><button class="segment-btn" data-toggle="modal" data-target="#walletAddress">
                        Edit Wallet Address
                      </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#walletAddress" id="addwallettbtn">
                      Add Wallet Address
                    </button>
                    <div class="modal fade" id="walletAddress" tabindex="-1" role="dialog" aria-labelledby="walletAddress" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Ethereum Wallet Address</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Wallet Address</p>
                            <input class="modal-input" name="Wallet Address" id="wallet_address" placeholder="Enter Wallet Address" value="<?php if(isset($data[2])) echo $data[2] ?>"/>
                            <p class="wallet_token_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_wallet">Set Wallet Address</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon storage-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Storage Allocation</h4>
                    <p class="segment-msg">How much disk space you want to allocate to the Storj network</p>
                    <span id="storagebtnval"></span><span style="display:none;" id="editstoragebtn"><button class="segment-btn" data-toggle="modal" data-target="#storageAllocation">
                      Edit Storage Capacity
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#storageAllocation" id="addstoragebtn">
                      Set Storage Capacity
                    </button>
                    <div class="modal fade" id="storageAllocation" tabindex="-1" role="dialog" aria-labelledby="storageAllocation" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Storage Allocation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Storage Allocation</p>
                            <input class="modal-input shorter" id="storage_allocate" name="storage_allocate" type="number" step="1" min="1" class="quantity" placeholder="Please enter only valid number" value="<?php if(isset($data[3])) echo $data[3] ?>"/>
                            <p class="modal-input-metric">GB</p>
                          <p class="storage_token_msg msg" style="display:none;">Minimum 500 GB is required</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="allocate_storage">Set Storage Capacity</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon bandwidth-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Bandwidth Allocation</h4>
                    <p class="segment-msg">How much bandwidth can you allocate to the Storj network.</p>
                      <span id="bandwidthbtnval"></span><span style="display:none;" id="editbandwidthbtn"><button class="segment-btn" data-toggle="modal" data-target="#bandwidth">
                      Edit Bandwidth Allocation
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#bandwidth" id="addbandwidthbtn">
                      Set Bandwidth Allocation
                    </button>
                    <div class="modal fade" id="bandwidth" tabindex="-1" role="dialog" aria-labelledby="bandwidth" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Bandwidth Allocation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Bandwidth Allocation</p>
                          <input style="width: 280px" class="modal-input shorter" id="bandwidth_allocation" name="bandwidth_allocation" type="number" step="1" min="1" class="quantity" placeholder="Please enter only valid number" value="<?php if(isset($data[4])) echo $data[4] ?>" />
                            <p class="modal-input-metric">TB</p>
                            <p class="bandwidth_token_msg msg" style="display:none;">Minimum 1 TB is required</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_bandwidth">Set Bandwidth Allocation</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon email-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Email Address</h4>
                    <p class="segment-msg">How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected.</p>
                    <span id="emailAddressval"></span><span style="display:none;" id="editemailAddressbtn"><button class="segment-btn" data-toggle="modal" data-target="#emailAddress">
                      Edit Email Address
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#emailAddress" id="emailAddressbtn">
                      Add Email Address
                    </button>
                    <div class="modal fade" id="emailAddress" tabindex="-1" role="dialog" aria-labelledby="email_address" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Email Address</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Email Address</p>
                            <input class="modal-input" id="email_address" name="email_address" type="email" placeholder="Email Address" value="<?php if(isset($data[5])) echo $data[5] ?>"/>
                            <p class="email_token_msg msg" style="display:none;">Enter a Valid Email address</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_emailaddress">Set Email Address</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon directory-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Storage Directory</h4>
                    <p class="segment-msg">The local directory where you want files to be stored on your hard drive for the network</p>
                      <span id="directorybtnval"></span><span style="display:none;" id="editdirectorybtn"><button class="segment-btn" data-toggle="modal" data-target="#directory">
                      Edit Storage Directory
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#directory" id="adddirectorybtn">
                      Set Storage Directory
                    </button>
                    <div class="modal fade" id="directory" tabindex="-1" role="dialog" aria-labelledby="directory" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="identity">Storage Directory</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Storage Directory</p>
                          <input class="modal-input" id="storage_directory" name="storage_directory" placeholder="/path/to/folder_to_share" value="<?php if(isset($data[6])) echo $data[6] ?>"  />
                            <p class="directory_token_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_directory">Set Directory</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="bottom-buttons">
                  <button type="button" class="start-button" id="updatebtn">Update My Storage Node</button>
                  <div style="position: absolute;display: inline-block;left: 40%;">
                    <button type="button" disabled class="stop-button" id="stopbtn">Stop My Storage Node</button>&nbsp;&nbsp;
                  <button type="button" class="start-button" id="startbtn">Start My Storage Node</button></d>
                </div>
              <!-- log message -->
              <iframe>
                <p  id="msg"></p>
              </iframe>  
            </div>
          </div>
          <?php }
        } ?>
  </div>
<?php include 'footer.php';?>
<!--<script src="./resources/js/jquery-3.1.1.min.js"></script>-->
<script type="text/javascript" src="./resources/js/config.js"></script>
<?php

}

function logEnvironment() {
	logMessage(
		"\n----------------------------------------------\n"
		. "ENV is : " . print_r($_ENV, true)
		. "POST is : " . print_r($_POST, true)
		. "SERVER is : " . print_r($_SERVER, true)
		. "----------------------------------------------\n"
	);
}

function logMessage($message) {
    $file = "/var/log/StorJ" ;
    $message = preg_replace('/\n$/', '', $message);
    $date = `date` ; $timestamp = str_replace("\n", " ", $date);										   															
    file_put_contents($file, $timestamp . $message . "\n", FILE_APPEND);
}

?>
