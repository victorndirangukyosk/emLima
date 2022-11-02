<?php

require_once DIR_SYSTEM . '/vendor/kra/fp.php';
require_once DIR_SYSTEM . '/vendor/kra/FP_Core.php';

class ControllerKraKra extends Controller {

    function alert_msg($msg) {
        $msg = str_replace("'", '"', $msg);
        $msg = json_encode($msg);
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }

    function handleException($ex) {
        $msg = "Error!  " . $ex->getMessage();
        if ($ex instanceof \Tremol\SException) {
            $code = $ex->getCode();
            if ($ex->isFpException()) {
                $ste1 = $ex->getSte1();
                $ste2 = $ex->getSte2();

                /**
                 *   Possible reasons:  
                 * ste1 =                                              ste2 =
                 *  0x30 OK                                                   0x30 OK                                 
                 *  0x31 Out of paper, printer failure                        0x31 Invalid command
                 *  0x32 Registers overflow                                   0x32 Illegal command
                 *  0x33 Clock failure or incorrect date&time                 0x33 Z daily report is not zero
                 *  0x34 Opened fiscal receipt                                0x34 Syntax error
                 *  0x35 Payment residue account                              0x35 Input registers overflow
                 *  0x36 Opened non-fiscal receipt                            0x36 Zero input registers
                 *  0x37 Registered payment but receipt is not closed         0x37 Unavailable transaction for correction
                 *  0x38 Fiscal memory failure                                0x38 Insufficient amount on hand
                 *  0x39 Incorrect password                                   0x3A No access
                 *  0x3a Missing external display
                 *  0x3b 24hours block – missing Z report
                 *  0x3c Overheated printer thermal head.
                 *  0x3d Interrupt power supply in fiscal receipt (one time until status is read)
                 *  0x3e Overflow EJ
                 *  0x3f Insufficient conditions
                 */
                if ($ste1 == 0x30 && $ste2 == 0x32) {
                    $this->alert_msg("ste1 == 0x30 - command is OK and ste2 == 0x32 - command is illegal in current context");
                } else if ($ste1 == 0x30 && $ste2 == 0x33) {
                    $this->alert_msg("ste1 == 0x30 - command is OK and ste2 == 0x32 == 0x33 - make Z report");
                } else if ($ste1 == 0x34 && $ste2 == 0x32) {
                    $this->alert_msg("ste1 == 0x34 - opened fiscal receipt and ste2 == 0x32 - command is illegal in current context");
                } else if ($ste1 == 0x39 && $ste2 == 0x32) {
                    $this->alert_msg("ste1 == 0x39 - Wrong password and ste2 == 0x32 - command is illegal in current context");
                } else {
                    $this->alert_msg($msg . "\nste1=" . $ste1 . ", ste2=" . $ste2);
                }
            } else if ($code == \Tremol\ServerErrorType::ServerDefsMismatch) {
                $this->alert_msg("The current library version and server definitions version do not match");
            } else if ($code == \Tremol\ServerErrorType::ServMismatchBetweenDefinitionAndFPResult) {
                $this->alert_msg("The current library version and the fiscal device firmware is not matching");
            } else if ($code == \Tremol\ServerErrorType::ServerAddressNotSet) {
                $this->alert_msg("Specify server ServerAddress property");
            } else if ($code == \Tremol\ServerErrorType::ServerConnectionError) {
                $this->alert_msg("Connection from this app to the server is not established");
            } else if ($code == \Tremol\ServerErrorType::ServSockConnectionFailed) {
                $this->alert_msg("When the server can not connect to the fiscal device");
            } else if ($code == \Tremol\ServerErrorType::ServTCPAuth) {
                $this->alert_msg("Wrong device ?CP password");
            } else if ($code == \Tremol\ServerErrorType::ServWaitOtherClientCmdProcessingTimeOut) {
                $this->alert_msg("Proccessing of other clients command is taking too long");
            } else {
                $this->alert_msg($msg);
            }
        } else {
            $this->alert_msg($msg);
        }
    }

    function fpGetLibraryVersions(\Tremol\FP $fp) {
        try {
            $msg = "Core version: " . $fp->GetVersionCore() . "\nLibrary definitions: " . strval($fp->GetVersionDefinitions());
            $this->alert_msg($msg);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpServerSetSettings(\Tremol\FP $fp, $ServerAddress, $ServerPort) {
        try {
            $fp->ServerSetSettings($ServerAddress, $ServerPort);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpServerSetDeviceTcpSettings($DevDetails) {
        $DevIp = $DevDetails['DevIp'];
        $DevTcpPort = $DevDetails['DevTcpPort'];
        $DevTcpPassword = $DevDetails['DevTcpPassword'];

        $fp = new \Tremol\FP();

        try {
            $fp->ServerSetDeviceTcpSettings($DevIp, $DevTcpPort, $DevTcpPassword);
            if (!$fp->IsCompatible()) {
                throw new Exception("Server and client versions are different!");
            }
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpServerSetDeviceSerialSettings(\Tremol\FP $fp, $DevSerialPort, $DevBaudRate) {
        try {
            $fp->ServerSetDeviceSerialSettings($DevSerialPort, $DevBaudRate, TRUE);
            if (!$fp->IsCompatible()) {
                throw new Exception("Server and client versions are different!");
            }
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpServerFindDevice(\Tremol\FP $fp) {
        try {
            $dev = $fp->ServerFindDevice(false);
            if (!$dev) {
                $this->alert_msg("FD not found!");
            } else {
                $this->alert_msg("FD found on " . $dev->SerialPort . " baud " . $dev->BaudRate);
            }
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpReadDeviceSerialNumber(\Tremol\FP $fp) {
        try {
            $this->alert_msg($fp->ReadSerialAndFiscalNums()->SerialNumber);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpDiagnostics(\Tremol\FP $fp) {
        try {
            $fp->PrintDiagnostics();
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpGSReadDeviceInfo(\Tremol\FP $fp) {
        try {
            $fp->RawWrite(array(0x1D, 0x49));
            $res = trim($fp->RawRead(0, "\n"));
            $this->alert_msg($res);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpReadVersion(\Tremol\FP $fp) {
        try {
            $v = $fp->ReadVersion();
            $this->alert_msg("Model: " . $v);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpXReport(\Tremol\FP $fp) {
        try {
            $fp->PrintDailyReport(Tremol\OptionZeroing::Not_zeroing);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpZReport(\Tremol\FP $fp) {
        try {
            $fp->PrintDailyReport(Tremol\OptionZeroing::Zeroing);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpOpenFiscalReceipt(\Tremol\FP $fp, $Operator1password) {
        try {
            $fp->OpenReceipt(1, $Operator1password, Tremol\OptionReceiptFormat::Brief, \Tremol\OptionPrintVAT::No, Tremol\OptionFiscalReceiptPrintType::Step_by_step_printing);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpSellPLU(\Tremol\FP $fp, $ArtName, $ArtPrice, $ArtQty) {
        try {
            $fp->SellPLUwithSpecifiedVAT($ArtName, Tremol\OptionVATClass::VAT_Class_B, $ArtPrice, $ArtQty);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpCloseReceiptInCash(\Tremol\FP $fp) {
        try {
            $fp->CashPayCloseReceipt();
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpPrintFreeText(\Tremol\FP $fp, $FreeText) {
        try {
            $fp->PrintText($FreeText);
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

    function fpCancelFiscalReceipt(\Tremol\FP $fp) {
        try {
            $fp->CancelReceipt();
        } catch (Exception $ex) {
            $this->handleException($ex);
        }
    }

}
