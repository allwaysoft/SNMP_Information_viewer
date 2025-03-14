<?php
    class Installed_Information
    {
        public function __construct($operating_system, $host_address)
        {
            $this->operating_system = $operating_system;
            $this->host_address     = $host_address;
            if($this->operating_system == 'windows')
            {
                $this->create_data_array();
            }
            if($this->operating_system == 'linux')
            {
                $this->create_data_array();
            }
        }
        private function create_data_array()
        {
        
        $soft = array();
		$result = snmpwalk($this->host_address, "public", '.iso.org.dod.internet.mgmt.mib-2.host.hrSWInstalled.hrSWInstalledTable.hrSWInstalledEntry');
        $soft = array_chunk($result,count($result)/5);
//            $hrSWInstalledIndex = array();
//            $hrSWInstalledIndex = snmpwalkoid($this->host_address, "public", ".iso.org.dod.internet.mgmt.mib-2.host.hrSWInstalled.hrSWInstalledTable.hrSWInstalledEntry.hrSWInstalledIndex");
            if($soft != FALSE)
            {
                if(count($soft) > 0)
                {
                    $this->data_fetched = 'yes';
                    
                    $installed_type = array();
                    $installed_name  = array();
                    $installed_date  = array();
                    for($i=0; $i<count($soft[3]); $i++)
                    {
                        $installed_type[$i] = str_replace('INTEGER: ', '', $soft[3][$i]);
                    }

                    for($i=0; $i<count($soft[1]); $i++)
                    {
                        $data = str_replace('STRING: ', '', $soft[1][$i]);
                        $data = str_replace('"', '', $data);
                        $installed_name[$i] = $data;
                    }
                    for($i=0; $i<count($soft[4]); $i++)
                    {
                        $data = str_replace('STRING: ', '', $soft[4][$i]);
                        $installed_date[$i] = $data;
                    }
                    $this->data_array[0] = $installed_type;
                    $this->data_array[1] = $installed_name;
                    $this->data_array[2] = $installed_date;
                }
                else
                {
                    $this->data_fetched = 'no';
                }
            }
            else
            {
                
            }
        }
        public function get_data_array()
        {
            return $this->data_array;
        }
        public function __destruct()
        {
            /*
             * No code needed here
             */
        }
        public $data_fetched;
        private $data_array = array();
        private $operating_system;
        private $host_address;
    }
?>
