<?php
$configApp = parse_ini_file(APPLICATION_PATH ."/secrets.ini");
$this->_email->IsHTML(true);

$this->_email->WordWrap = 50;                                                   // nastavime word wrap

$this->_email->isSMTP();                                                        // Set mailer to use SMTP
$this->_email->Host         = $configApp['email.Host'];                           // Specify main and backup SMTP servers
$this->_email->SMTPAuth     = true;                                             // Enable SMTP authentication
$this->_email->Username     = $configApp['email.Username'];               // SMTP username
$this->_email->Password     = $configApp['email.Password'];                                      // SMTP password
$this->_email->SMTPSecure   = 'tls';                                            // Enable TLS encryption, `ssl` also accepted
$this->_email->Port         = $configApp['email.Port'];                                              // TCP port to connect to
?>
