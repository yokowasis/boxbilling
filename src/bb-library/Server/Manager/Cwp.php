<?php
$servername = "localhost";
$username = "root";
$password = "yP9QsMlSQisU";
$dbname = "root_cwp";

// Create connection
global $conn;
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

function savelog($log){
    global $conn;
    $sql = "INSERT INTO `log` (`_log`) VALUES ('$log')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
        }
    } else {
    }
}

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class Server_Manager_Cwp extends Server_Manager
{
    public static function getForm()
    {
        return array(
            'label'     =>  'Centos Web Panel',
        );
    }

    public function getLoginUrl()
    {
        $host     = $this->_config['host'];

        return 'https://'.$host.':2031';
    }

    public function getResellerLoginUrl()
    {
        $host     = $this->_config['host'];
        return 'https://'.$host.':2031';
    }

    public function testConnection()
    {
        return TRUE;
    }

    public function synchronizeAccount(Server_Account $a)
    {
        $this->getLog()->info('Synchronizing account with server '.$a->getUsername());
        return $a;
    }

    public function createAccount(Server_Account $a)
    {

        $client = $a->getClient();

        $port = $this->_config['port'];

        if($this->_config['secure']) {
            $protocol = 'https';
            if (!$port) {
                $port = '2031';
            }
        } else {
            $protocol = 'http';
            if (!$port) {    
                $port = '2030';                
            }
        }

        $thePackage = $protocol."://".$this->_config["host"].":".$port."/api/index2.php?key=".$this->_config['accesshash']."&api=package&getName=".$a->getPackage()->getName()."&getQuota=".$a->getPackage()->getQuota()."&getBandwidth=".$a->getPackage()->getBandwidth()."&getMaxFtp=".$a->getPackage()->getMaxFtp()."&getMaxPop=".$a->getPackage()->getMaxPop()."&getMaxSql=".$a->getPackage()->getMaxSql()."&getMaxSubdomains=".$a->getPackage()->getMaxSubdomains()."&getMaxParkedDomains=".$a->getPackage()->getMaxParkedDomains()."&getMaxDomains=".$a->getPackage()->getMaxDomains()."";
        $thePackage = str_ireplace(" ", "%20", $thePackage);

        $package_id = file_get_contents($thePackage);

        $url = $protocol.'://'.$this->_config['host'].':'.$port.'/api/?key='.$this->_config['accesshash'].'&api=account_new&domain='.$a->getDomain().'&username='.$a->getUsername().'&password='.$a->getPassword().'&package='.$package_id.'&email='.$client->getEmail().'&inode=10000&nofile=100&nproc=25';

        //savelog($thePackage);
        //savelog($url);
        //savelog("packid = $package_id");

        $payload = file_get_contents($url);
        //savelog($payload);
        return $payload;
    }

    public function suspendAccount(Server_Account $a)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Suspending reseller hosting account');
        } else {
            $this->getLog()->info('Suspending shared hosting account');
            $client = $a->getClient();

            $port = $this->_config['port'];

            if($this->_config['secure']) {
                $protocol = 'https';
                if (!$port) {
                    $port = '2031';
                }
            } else {
                $protocol = 'http';
                if (!$port) {    
                    $port = '2030';                
                }
            }

            $url = $protocol.'://'.$this->_config['host'].':'.$port.'/api/?key='.$this->_config['accesshash'].'&api=account_suspend&username='.$a->getUsername();
            $payload = file_get_contents($url);
            return $payload;
        }
    }

    public function unsuspendAccount(Server_Account $a)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Unsuspending reseller hosting account');
        } else {
            $this->getLog()->info('Unsuspending shared hosting account');
            $client = $a->getClient();

            $port = $this->_config['port'];

            if($this->_config['secure']) {
                $protocol = 'https';
                if (!$port) {
                    $port = '2031';
                }
            } else {
                $protocol = 'http';
                if (!$port) {    
                    $port = '2030';                
                }
            }

            $url = $protocol.'://'.$this->_config['host'].':'.$port.'/api/?key='.$this->_config['accesshash'].'&api=account_unsuspend&username='.$a->getUsername();

            $payload = file_get_contents($url);
            return $payload;
        }
    }

    public function cancelAccount(Server_Account $a)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Canceling reseller hosting account');
        } else {
            $this->getLog()->info('Canceling shared hosting account');
            $client = $a->getClient();

            $port = $this->_config['port'];


            if($this->_config['secure']) {
                $protocol = 'https';
                if (!$port) {
                    $port = '2031';
                }
            } else {
                $protocol = 'http';
                if (!$port) {    
                    $port = '2030';                
                }
            }

            $url = $protocol.'://'.$this->_config['host'].':'.$port.'/api/?key='.$this->_config['accesshash'].'&api=account_remove&username='.$a->getUsername();

            $payload = file_get_contents($url);
            return $payload;
        }
    }

    public function changeAccountPackage(Server_Account $a, Server_Package $p)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Updating reseller hosting account');
        } else {
            $this->getLog()->info('Updating shared hosting account');
        }

        $p->getName();
        $p->getQuota();
        $p->getBandwidth();
        $p->getMaxSubdomains();
        $p->getMaxParkedDomains();
        $p->getMaxDomains();
        $p->getMaxFtp();
        $p->getMaxSql();
        $p->getMaxPop();

        $p->getCustomValue('param_name');
    }

    public function changeAccountUsername(Server_Account $a, $new)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Changing reseller hosting account username');
        } else {
            $this->getLog()->info('Changing shared hosting account username');
        }
    }

    public function changeAccountDomain(Server_Account $a, $new)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Changing reseller hosting account domain');
        } else {
            $this->getLog()->info('Changing shared hosting account domain');
        }
    }

    public function changeAccountPassword(Server_Account $a, $new)
    {
        if($a->getReseller()) {
        } else {
            $port = $this->_config['port'];

            if($this->_config['secure']) {
                $protocol = 'https';
                if (!$port) {
                    $port = '2031';
                }
            } else {
                $protocol = 'http';
                if (!$port) {    
                    $port = '2030';                
                }
            }

            $payload = $protocol."://".$this->_config["host"].":".$port."/api/index2.php?key=".$this->_config['accesshash']."&api=changepass&account=".$a->getUsername()."&password=".$new."";
            $payload = file_get_contents($payload);
        }
        return TRUE;
    }

    public function changeAccountIp(Server_Account $a, $new)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Changing reseller hosting account ip');
        } else {
            $this->getLog()->info('Changing shared hosting account ip');
        }
    }
}
