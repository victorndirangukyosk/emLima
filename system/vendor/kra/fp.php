<?php
namespace Tremol {
  require('FP_Core.php');
  class OptionTaxable extends EnumType {
    const Exempted = '1';
    const Taxable = '0';
  }
  
  class OptionReceiptFormat extends EnumType {
    const Brief = '0';
    const Detailed = '1';
  }
  
  class OptionIsReceiptOpened extends EnumType {
    const No = '0';
    const Yes = '1';
  }
  
  class OptionClientReceipt extends EnumType {
    const invoice_client_receipt = '1';
    const standard_receipt = '0';
  }
  
  class OptionPowerDownInReceipt extends EnumType {
    const No = '0';
    const Yes = '1';
  }
  
  class OptionLAN extends EnumType {
    const No = '0';
    const Yes = '1';
  }
  
  class OptionWiFi extends EnumType {
    const No = '0';
    const Yes = '1';
  }
  
  class OptionGPRS extends EnumType {
    const No = '0';
    const Yes = '1';
  }
  
  class OptionBT extends EnumType {
    const No = '0';
    const Yes = '1';
  }
  
  class OptionAddressType extends EnumType {
    const DNS_address = '5';
    const Gateway_address = '4';
    const IP_address = '2';
    const Subnet_Mask = '3';
  }
  
  class OptionDHCPEnabled extends EnumType {
    const Disabled = '0';
    const Enabled = '1';
  }
  
  class OptionDeviceType extends EnumType {
    const A_Type = '1';
    const B_Type = '2';
  }
  
  class OptionReadEJStorage extends EnumType {
    const Reading_to_PC = 'J0';
    const Reading_to_PC_for_JSON = 'JY';
  }
  
  class OptionAuthenticationType extends EnumType {
    const CHAP = '2';
    const None = '0';
    const PAP = '1';
    const PAP_or_CHAP = '3';
  }
  
  class OptionServerResponse extends EnumType {
    const At_send_EOD = 'Z';
    const At_send_receipt = 'R';
  }
  
  class OptionTransactionType extends EnumType {
    const Error_Code = 'c';
    const Error_Message = 'm';
    const Exception_Message = 'e';
    const Status = 's';
  }
  
  class OptionInvoiceCopy extends EnumType {
    const Reading = 'J0';
    const Storage_in_External_SD_card_memory = 'J4';
    const Storage_in_External_USB_Flash_memory = 'J2';
  }
  
  class OptionModule extends EnumType {
    const GSM = '0';
    const LANWiFi = '1';
  }
  
  class OptionTCPAutoStart extends EnumType {
    const No = '0';
    const Yes = '1';
  }
  
  class OptionUsedModule extends EnumType {
    const LAN_module = '1';
    const WiFi_module = '2';
  }
  
  class OptionVATClass extends EnumType {
    const VAT_Class_A = 'A';
    const VAT_Class_B = 'B';
    const VAT_Class_C = 'C';
    const VAT_Class_D = 'D';
    const VAT_Class_E = 'E';
  }
  
  class OptionReportStorage extends EnumType {
    const Storage_in_External_SD_card_memory = 'J4';
    const Storage_in_External_SD_card_memory_for_JSON = 'JX';
    const Storage_in_External_USB_Flash_memory = 'J2';
    const Storage_in_External_USB_Flash_memory_for_JSON = 'Jx';
  }
  
  class OptionDisplay extends EnumType {
    const No = '0';
    const Yes = '1';
  }
  
  class CloseReceiptRes extends BaseResClass {
  protected $InvoiceNum;
  protected $QRcode;
}

class CUnumbersRes extends BaseResClass {
  protected $SerialNumber;
  protected $PINnumber;
}

class CurrentReceiptInfoRes extends BaseResClass {
  protected $OptionIsReceiptOpened;
  protected $SalesNumber;
  protected $SubtotalAmountVATGA;
  protected $SubtotalAmountVATGB;
  protected $SubtotalAmountVATGC;
  protected $SubtotalAmountVATGD;
  protected $SubtotalAmountVATGE;
  protected $OptionReceiptFormat;
  protected $OptionClientReceipt;
  protected $OptionPowerDownInReceipt;
  protected $reserved5;
}

class DailyAmountsByVATRes extends BaseResClass {
  protected $SaleAmountVATGrA;
  protected $SaleAmountVATGrB;
  protected $SaleAmountVATGrC;
  protected $SaleAmountVATGrD;
  protected $SaleAmountVATGrE;
  protected $TurnoverAmountVAT;
  protected $RefundAmountVATGrA;
  protected $RefundAmountVATGrB;
  protected $RefundAmountVATGrC;
  protected $RefundAmountVATGrD;
  protected $RefundAmountVATGrE;
  protected $TurnoverRefAmountVAT;
}

class DeviceModuleSupportRes extends BaseResClass {
  protected $OptionLAN;
  protected $OptionWiFi;
  protected $OptionGPRS;
  protected $OptionBT;
}

class DeviceModuleSupportByFirmwareRes extends BaseResClass {
  protected $OptionLAN;
  protected $OptionWiFi;
  protected $OptionGPRS;
  protected $OptionBT;
}

class DeviceTCP_AddressesRes extends BaseResClass {
  protected $OptionAddressType;
  protected $DeviceAddress;
}

class DiagnosticsRes extends BaseResClass {
  protected $OptionDeviceType;
  protected $SDIdxPos;
  protected $LastInvoiceCUNum;
  protected $LastInvoiceDate;
  protected $LastEODDate;
  protected $InvoicesSent;
}

class EODAmountsRes extends BaseResClass {
  protected $EOD_sale_turnover;
  protected $EOD_credit_turnover;
  protected $EOD_saleVAT;
  protected $EOD_creditVAT;
}

class GPRS_APNRes extends BaseResClass {
  protected $gprsAPNlength;
  protected $APN;
}

class GPRS_PasswordRes extends BaseResClass {
  protected $PassLength;
  protected $Password;
}

class GPRS_UsernameRes extends BaseResClass {
  protected $gprsUserNameLength;
  protected $Username;
}

class HScodeRes extends BaseResClass {
  protected $HS_Number;
  protected $HS_Code;
  protected $HS_Name;
  protected $OptionTaxable;
  protected $MesureUnit;
  protected $VAT_Rate;
}

class HTTPS_ServerRes extends BaseResClass {
  protected $ParamLength;
  protected $Address;
}

class InfoFromLastServerCommunicationRes extends BaseResClass {
  protected $OptionServerResponse;
  protected $OptionTransactionType;
  protected $Message;
}

class LastAndTotalReceiptNumRes extends BaseResClass {
  protected $LastCUInvoiceNum;
  protected $LastReceiptNum;
}

class NTP_AddressRes extends BaseResClass {
  protected $AddressLen;
  protected $NTPAddress;
}

class SpecificMessageRes extends BaseResClass {
  protected $MessageNum;
  protected $DateTime;
  protected $Type;
  protected $Code;
  protected $MessageText;
}

class StatusRes extends BaseResClass {
  protected $Power_down_in_opened_fiscal_receipt;
  protected $DateTime_not_set;
  protected $DateTime_wrong;
  protected $RAM_reset;
  protected $Hardware_clock_error;
  protected $Reports_registers_Overflow;
  protected $Opened_Fiscal_Receipt;
  protected $Receipt_Invoice_Type;
  protected $SD_card_near_full;
  protected $SD_card_full;
  protected $CU_fiscalized;
  protected $CU_produced;
  protected $Paired_with_TIMS;
  protected $Unsent_receipts;
  protected $No_Sec_IC;
  protected $No_certificates;
  protected $Service_jumper;
  protected $Missing_SD_card;
  protected $Wrong_SD_card;
}

class TCP_PasswordRes extends BaseResClass {
  protected $PassLength;
  protected $Password;
}

class VATratesRes extends BaseResClass {
  protected $VATrateA;
  protected $VATrateB;
  protected $VATrateC;
  protected $VATrateD;
  protected $VATrateE;
}

class WiFi_NetworkNameRes extends BaseResClass {
  protected $WiFiNameLength;
  protected $WiFiNetworkName;
}

class WiFi_PasswordRes extends BaseResClass {
  protected $PassLength;
  protected $Password;
}

class FP extends FP_Core {
  function __construct() {
    $this->timeStamp = 2204120945;
  }
  /**
   * Programs the customer DB for special customer receipt issuing.
   * @param string $HS_Code 10 symbols for HS code
   * @param string $HS_Name 20 symbols for name of HS group
   * @param OptionTaxable $OptionTaxable 1 symbol for parameter: 
   * - '1' - Exempted 
   * - '0' - Taxable
   * @param string $MesureUnit 3 symbols for mesure unit of item's code
   * @param double $VAT_rate Value of VAT rate from 2 to 5 symbols with format ##.##
   */
  public function AddNewHScode($HS_Code,$HS_Name,$OptionTaxable,$MesureUnit,$VAT_rate) {
    $this->execute("AddNewHScode", "HS_Code", $HS_Code, "HS_Name", $HS_Name, "OptionTaxable", $OptionTaxable, "MesureUnit", $MesureUnit, "VAT_rate", $VAT_rate);
  }
  
  /**
   * Available only if the receipt is not closed. Cancel all sales in the receipt and close it .
   */
  public function CancelReceipt() {
    $this->execute("CancelReceipt");
  }
  
  /**
   * Clears the external display.
   */
  public function ClearDisplay() {
    $this->execute("ClearDisplay");
  }
  
  /**
   * Closes the opened fiscal receipt and returns receipt info.
   * @return CloseReceiptRes
   */
  public function CloseReceipt() {
    return new \Tremol\CloseReceiptRes($this->execute("CloseReceipt"));
  }
  
  /**
   * Confirm PIN number.
   * @param string $Password 6-symbols string
   */
  public function ConfirmFiscalization($Password) {
    $this->execute("ConfirmFiscalization", "Password", $Password);
  }
  
  /**
   * Provides information for the daily fiscal report  with zeroing and fiscal memory record, preceded by Electronic Journal report.
   */
  public function DailyReport() {
    $this->execute("DailyReport");
  }
  
  /**
   * Executes the direct command .
   * @param string $Input Raw request to FP
   * @return string FP raw response
   */
  public function DirectCommand($Input) {
    return $this->execute("DirectCommand", "Input", $Input);
  }
  
  /**
   * Shows the current date and time on the external display.
   */
  public function DisplayDateTime() {
    $this->execute("DisplayDateTime");
  }
  
  /**
   * Shows a 20-symbols text in the upper external display line.
   * @param string $Text 16 symbols text
   */
  public function DisplayTextLine1($Text) {
    $this->execute("DisplayTextLine1", "Text", $Text);
  }
  
  /**
   * Shows a 16-symbols text in the lower external display line.
   * @param string $Text 16 symbols text
   */
  public function DisplayTextLine2($Text) {
    $this->execute("DisplayTextLine2", "Text", $Text);
  }
  
  /**
   * Shows a 16-symbols text in the first line and last 16-symbols text in the second line of the external display lines.
   * @param string $Text 32 symbols text
   */
  public function DisplayTextLines1and2($Text) {
    $this->execute("DisplayTextLines1and2", "Text", $Text);
  }
  
  /**
   * Erase HS codes.
   * @param string $Password 6 symbols for password
   */
  public function EraseHScodes($Password) {
    $this->execute("EraseHScodes", "Password", $Password);
  }
  
  /**
   * Informs about the issued document
   */
  public function InfoLastReceiptDuplicate() {
    $this->execute("InfoLastReceiptDuplicate");
  }
  
  /**
   * Opens a fiscal invoice credit note receipt assigned to the specified operator number and operator password with free info for customer data. The Invoice receipt can be issued only if the invoice range (start and end numbers) is set.
   * @param string $CompanyName 30 symbols for Invoice company name
   * @param string $ClientPINnum 14 symbols for client PIN number
   * @param string $HeadQuarters 30 symbols for customer headquarters
   * @param string $Address 30 symbols for Address
   * @param string $PostalCodeAndCity 30 symbols for postal code and city
   * @param string $ExemptionNum 30 symbols for exemption number
   * @param string $RelatedInvoiceNum 19 symbols for the related invoice number in format 
   * ###################
   * @param string $TraderSystemInvNum 15 symbols for trader system invoice number
   */
  public function OpenCreditNoteWithFreeCustomerData($CompanyName,$ClientPINnum,$HeadQuarters,$Address,$PostalCodeAndCity,$ExemptionNum,$RelatedInvoiceNum,$TraderSystemInvNum) {
    $this->execute("OpenCreditNoteWithFreeCustomerData", "CompanyName", $CompanyName, "ClientPINnum", $ClientPINnum, "HeadQuarters", $HeadQuarters, "Address", $Address, "PostalCodeAndCity", $PostalCodeAndCity, "ExemptionNum", $ExemptionNum, "RelatedInvoiceNum", $RelatedInvoiceNum, "TraderSystemInvNum", $TraderSystemInvNum);
  }
  
  /**
   * Opens a fiscal invoice debit note receipt assigned to the specified operator number and operator password with free info for customer data. The Invoice receipt can be issued only if the invoice range (start and end numbers) is set.
   * @param string $CompanyName 30 symbols for Invoice company name
   * @param string $ClientPINnum 14 symbols for client PIN number
   * @param string $HeadQuarters 30 symbols for customer headquarters
   * @param string $Address 30 symbols for Address
   * @param string $PostalCodeAndCity 30 symbols for postal code and city
   * @param string $ExemptionNum 30 symbols for exemption number
   * @param string $RelatedInvoiceNum 19 symbols for the related invoice number in format 
   * ###################
   * @param string $TraderSystemInvNum 15 symbols for trader system invoice number
   */
  public function OpenDebitNoteWithFreeCustomerData($CompanyName,$ClientPINnum,$HeadQuarters,$Address,$PostalCodeAndCity,$ExemptionNum,$RelatedInvoiceNum,$TraderSystemInvNum) {
    $this->execute("OpenDebitNoteWithFreeCustomerData", "CompanyName", $CompanyName, "ClientPINnum", $ClientPINnum, "HeadQuarters", $HeadQuarters, "Address", $Address, "PostalCodeAndCity", $PostalCodeAndCity, "ExemptionNum", $ExemptionNum, "RelatedInvoiceNum", $RelatedInvoiceNum, "TraderSystemInvNum", $TraderSystemInvNum);
  }
  
  /**
   * Opens a fiscal invoice receipt assigned to the specified operator number and operator password with free info for customer data. The Invoice receipt can be issued only if the invoice range (start and end numbers) is set.
   * @param string $CompanyName 30 symbols for Invoice company name
   * @param string $ClientPINnum 14 symbols for client PIN number
   * @param string $HeadQuarters 30 symbols for customer headquarters
   * @param string $Address 30 symbols for Address
   * @param string $PostalCodeAndCity 30 symbols for postal code and city
   * @param string $ExemptionNum 30 symbols for exemption number
   * @param string $TraderSystemInvNum 15 symbols for trader system invoice number
   */
  public function OpenInvoiceWithFreeCustomerData($CompanyName,$ClientPINnum,$HeadQuarters,$Address,$PostalCodeAndCity,$ExemptionNum,$TraderSystemInvNum) {
    $this->execute("OpenInvoiceWithFreeCustomerData", "CompanyName", $CompanyName, "ClientPINnum", $ClientPINnum, "HeadQuarters", $HeadQuarters, "Address", $Address, "PostalCodeAndCity", $PostalCodeAndCity, "ExemptionNum", $ExemptionNum, "TraderSystemInvNum", $TraderSystemInvNum);
  }
  
  /**
   * Opens a fiscal receipt assigned to the specified operator number and operator password, parameters for receipt format and VAT type.
   * @param OptionReceiptFormat $OptionReceiptFormat 1 symbol with value: 
   *  - '1' - Detailed 
   *  - '0' - Brief
   * @param string $TraderSystemInvNum 15 symbols for trader system invoice number
   */
  public function OpenReceipt($OptionReceiptFormat,$TraderSystemInvNum) {
    $this->execute("OpenReceipt", "OptionReceiptFormat", $OptionReceiptFormat, "TraderSystemInvNum", $TraderSystemInvNum);
  }
  
  /**
   * Programs HS code at a given position (HS number in order).
   * @param double $HS_Number 4 symbols for HS number in order in format ####
   * @param string $HS_Code 10 symbols for HS code
   * @param string $HS_Name 20 symbols for name of HS group
   * @param OptionTaxable $OptionTaxable 1 symbol for parameter: 
   * - '1' - Exempted 
   * - '0' - Taxable
   * @param string $MesureUnit 3 symbols for mesure unit of item's code
   * @param double $VAT_Rate Value of VAT rate from 2 to 5 symbols with format ##.##
   */
  public function ProgHScode($HS_Number,$HS_Code,$HS_Name,$OptionTaxable,$MesureUnit,$VAT_Rate) {
    $this->execute("ProgHScode", "HS_Number", $HS_Number, "HS_Code", $HS_Code, "HS_Name", $HS_Name, "OptionTaxable", $OptionTaxable, "MesureUnit", $MesureUnit, "VAT_Rate", $VAT_Rate);
  }
  
  /**
   * Stores a block containing the values of the VAT rates into the CU
   * @param string $Password 6-symbols string
   * @param double $VATrateA Value of VAT rate A from 2 to 6 symbols with format ##.##
   * @param double $VATrateB Value of VAT rate B from 2 to 6 symbols with format ##.##
   * @param double $VATrateC Value of VAT rate C from 2 to 6 symbols with format ##.##
   * @param double $VATrateD Value of VAT rate D from 2 to 6 symbols with format ##.##
   * @param double $VATrateE Value of VAT rate E from 2 to 6 symbols with format ##.##
   */
  public function ProgVATrates($Password,$VATrateA,$VATrateB,$VATrateC,$VATrateD,$VATrateE) {
    $this->execute("ProgVATrates", "Password", $Password, "VATrateA", $VATrateA, "VATrateB", $VATrateB, "VATrateC", $VATrateC, "VATrateD", $VATrateD, "VATrateE", $VATrateE);
  }
  
  /**
   *  Reads raw bytes from FP.
   * @param double $Count How many bytes to read if EndChar is not specified
   * @param string $EndChar The character marking the end of the data. If present Count parameter is ignored.
   * @return array FP raw response in BASE64 encoded string
   */
  public function RawRead($Count,$EndChar) {
    return $this->execute("RawRead", "Count", $Count, "EndChar", $EndChar);
  }
  
  /**
   *  Writes raw bytes to FP 
   * @param array $Bytes The bytes in BASE64 ecoded string to be written to FP
   */
  public function RawWrite($Bytes) {
    $this->execute("RawWrite", "Bytes", $Bytes);
  }
  
  /**
   * Provides information about the manufacturing number of the CU and PIN number.
   * @return CUnumbersRes
   */
  public function ReadCUnumbers() {
    return new \Tremol\CUnumbersRes($this->execute("ReadCUnumbers"));
  }
  
  /**
   * Read the current status of the receipt.
   * @return CurrentReceiptInfoRes
   */
  public function ReadCurrentReceiptInfo() {
    return new \Tremol\CurrentReceiptInfoRes($this->execute("ReadCurrentReceiptInfo"));
  }
  
  /**
   * Provides information about the accumulated amounts and refunded amounts by VAT class in case that CU regularly informs about the Z report(7C)
   * @return DailyAmountsByVATRes
   */
  public function ReadDailyAmountsByVAT() {
    return new \Tremol\DailyAmountsByVATRes($this->execute("ReadDailyAmountsByVAT"));
  }
  
  /**
   * Provides information about the current date and time.
   * @return DateTime Date Time parameter in format: DD-MM-YY [Space] hh:mm
   */
  public function ReadDateTime() {
    return $this->execute("ReadDateTime");
  }
  
  /**
   * FlagsModule is a char with bits representing modules supported by the device.
   * @return DeviceModuleSupportRes
   */
  public function ReadDeviceModuleSupport() {
    return new \Tremol\DeviceModuleSupportRes($this->execute("ReadDeviceModuleSupport"));
  }
  
  /**
   * FlagsModule is a char with bits representing modules supported by the firmware
   * @return DeviceModuleSupportByFirmwareRes
   */
  public function ReadDeviceModuleSupportByFirmware() {
    return new \Tremol\DeviceModuleSupportByFirmwareRes($this->execute("ReadDeviceModuleSupportByFirmware"));
  }
  
  /**
   * Provides information about device's network IP address, subnet mask, gateway address, DNS address.
   * @param OptionAddressType $OptionAddressType 1 symbol with value: 
   *  - '2' - IP address 
   *  - '3' - Subnet Mask 
   *  - '4' - Gateway address 
   *  - '5' - DNS address
   * @return DeviceTCP_AddressesRes
   */
  public function ReadDeviceTCP_Addresses($OptionAddressType) {
    return new \Tremol\DeviceTCP_AddressesRes($this->execute("ReadDeviceTCP_Addresses", "OptionAddressType", $OptionAddressType));
  }
  
  /**
   * Provides information about device's DHCP status
   * @return OptionDHCPEnabled (Status) 1 symbols for device's DHCP status 
   * - '0' - Disabled 
   *  - '1' - Enabled
   */
  public function ReadDHCP_Status() {
    return $this->execute("ReadDHCP_Status");
  }
  
  /**
   * Provides information about documents sending functions .
   * @return DiagnosticsRes
   */
  public function ReadDiagnostics() {
    return new \Tremol\DiagnosticsRes($this->execute("ReadDiagnostics"));
  }
  
  /**
   * Read whole Electronic Journal report from beginning to the end.
   * @param OptionReadEJStorage $OptionReadEJStorage 2 symbols for destination: 
   *  - 'J0' - Reading to PC 
   *  - 'JY' - Reading to PC for JSON
   */
  public function ReadEJ($OptionReadEJStorage) {
    $this->execute("ReadEJ", "OptionReadEJStorage", $OptionReadEJStorage);
  }
  
  /**
   * Read Electronic Journal Report initial date to report end date.
   * @param OptionReadEJStorage $OptionReadEJStorage 2 symbols for destination: 
   *  - 'J0' - Reading to PC 
   *  - 'JY' - Reading to PC for JSON
   * @param DateTime $StartRepFromDate 6 symbols for initial date in the DDMMYY format
   * @param DateTime $EndRepFromDate 6 symbols for final date in the DDMMYY format
   */
  public function ReadEJByDate($OptionReadEJStorage,$StartRepFromDate,$EndRepFromDate) {
    $this->execute("ReadEJByDate", "OptionReadEJStorage", $OptionReadEJStorage, "StartRepFromDate", $StartRepFromDate, "EndRepFromDate", $EndRepFromDate);
  }
  
  /**
   * Provides information about the accumulated EOD turnover and VAT
   * @return EODAmountsRes
   */
  public function ReadEODAmounts() {
    return new \Tremol\EODAmountsRes($this->execute("ReadEODAmounts"));
  }
  
  /**
   * Provides information about device's GRPS APN.
   * @return GPRS_APNRes
   */
  public function ReadGPRS_APN() {
    return new \Tremol\GPRS_APNRes($this->execute("ReadGPRS_APN"));
  }
  
  /**
   * Read GPRS APN authentication type
   * @return OptionAuthenticationType 1 symbol with value: 
   * - '0' - None 
   * - '1' - PAP 
   * - '2' - CHAP 
   * - '3' - PAP or CHAP
   */
  public function ReadGPRS_AuthenticationType() {
    return $this->execute("ReadGPRS_AuthenticationType");
  }
  
  /**
   * Provides information about device's GPRS password.
   * @return GPRS_PasswordRes
   */
  public function ReadGPRS_Password() {
    return new \Tremol\GPRS_PasswordRes($this->execute("ReadGPRS_Password"));
  }
  
  /**
   * Providing information about device's GPRS user name.
   * @return GPRS_UsernameRes
   */
  public function ReadGPRS_Username() {
    return new \Tremol\GPRS_UsernameRes($this->execute("ReadGPRS_Username"));
  }
  
  /**
   * Programs HS code at a given position (HS number in order).
   * @param double $HS_Number 4 symbols for HS number in order in format ####
   * @return HScodeRes
   */
  public function ReadHScode($HS_Number) {
    return new \Tremol\HScodeRes($this->execute("ReadHScode", "HS_Number", $HS_Number));
  }
  
  /**
   * Read the number of HS codes.
   * @return double 4 symbols for HS codes number in format ####
   */
  public function ReadHScodeNumber() {
    return $this->execute("ReadHScodeNumber");
  }
  
  /**
   * Providing information about server HTTPS address.
   * @return HTTPS_ServerRes
   */
  public function ReadHTTPS_Server() {
    return new \Tremol\HTTPS_ServerRes($this->execute("ReadHTTPS_Server"));
  }
  
  /**
   * Provide information from the last communication with the server.
   * @param OptionServerResponse $OptionServerResponse 1 symbol with value 
   * - 'R' - At send receipt 
   * - 'Z' - At send EOD
   * @param OptionTransactionType $OptionTransactionType 1 symbol with value 
   * - 'c' - Error Code 
   * - 'm' - Error Message 
   * - 's' - Status 
   * - 'e' - Exception Message
   * @return InfoFromLastServerCommunicationRes
   */
  public function ReadInfoFromLastServerCommunication($OptionServerResponse,$OptionTransactionType) {
    return new \Tremol\InfoFromLastServerCommunicationRes($this->execute("ReadInfoFromLastServerCommunication", "OptionServerResponse", $OptionServerResponse, "OptionTransactionType", $OptionTransactionType));
  }
  
  /**
   * Read invoice threshold count
   * @return double Up to 5 symbols for value
   */
  public function ReadInvoice_Threshold() {
    return $this->execute("ReadInvoice_Threshold");
  }
  
  /**
   * Provides information about the number of the last issued receipt.
   * @return LastAndTotalReceiptNumRes
   */
  public function ReadLastAndTotalReceiptNum() {
    return new \Tremol\LastAndTotalReceiptNumRes($this->execute("ReadLastAndTotalReceiptNum"));
  }
  
  /**
   * Provides information about device's NTP address.
   * @return NTP_AddressRes
   */
  public function ReadNTP_Address() {
    return new \Tremol\NTP_AddressRes($this->execute("ReadNTP_Address"));
  }
  
  /**
   * Read/Store Invoice receipt copy to External USB Flash memory, External SD card.
   * @param OptionInvoiceCopy $OptionInvoiceCopy 2 symbols for destination: 
   *  - 'J0' - Reading  
   *  - 'J2' - Storage in External USB Flash memory. 
   *  - 'J4' - Storage in External SD card memory
   * @param string $CUInvoiceNum 10 symbols for Invoice receipt Number.
   */
  public function ReadOrStoreInvoiceCopy($OptionInvoiceCopy,$CUInvoiceNum) {
    $this->execute("ReadOrStoreInvoiceCopy", "OptionInvoiceCopy", $OptionInvoiceCopy, "CUInvoiceNum", $CUInvoiceNum);
  }
  
  /**
   * Read device communication usage with server
   * @return OptionModule 1 symbol with value: 
   *  - '0' - GSM 
   *  - '1' - LAN/WiFi
   */
  public function ReadServer_UsedComModule() {
    return $this->execute("ReadServer_UsedComModule");
  }
  
  /**
   * Reads specific message number
   * @param string $MessageNum 2 symbols for total number of messages
   * @return SpecificMessageRes
   */
  public function ReadSpecificMessage($MessageNum) {
    return new \Tremol\SpecificMessageRes($this->execute("ReadSpecificMessage", "MessageNum", $MessageNum));
  }
  
  /**
   * Provides detailed 6-byte information about the current status of the CU.
   * @return StatusRes
   */
  public function ReadStatus() {
    return new \Tremol\StatusRes($this->execute("ReadStatus"));
  }
  
  /**
   * Provides information about if the TCP connection autostart when the device enter in Line/Sale mode.
   * @return OptionTCPAutoStart 1 symbol for TCP auto start option 
   * - '0' - No 
   *  - '1' - Yes
   */
  public function ReadTCP_AutoStartStatus() {
    return $this->execute("ReadTCP_AutoStartStatus");
  }
  
  /**
   * Provides information about device's MAC address.
   * @return string 12 symbols for the device's MAC address
   */
  public function ReadTCP_MACAddress() {
    return $this->execute("ReadTCP_MACAddress");
  }
  
  /**
   * Provides information about device's TCP password.
   * @return TCP_PasswordRes
   */
  public function ReadTCP_Password() {
    return new \Tremol\TCP_PasswordRes($this->execute("ReadTCP_Password"));
  }
  
  /**
   * Provides information about which module the device is in use: LAN or WiFi module. This information can be provided if the device has mounted both modules.
   * @return OptionUsedModule 1 symbol with value: 
   *  - '1' - LAN module 
   *  - '2' - WiFi module
   */
  public function ReadTCP_UsedModule() {
    return $this->execute("ReadTCP_UsedModule");
  }
  
  /**
   * Read time threshold minutes
   * @return double Up to 5 symbols for value
   */
  public function ReadTimeThreshold_Minutes() {
    return $this->execute("ReadTimeThreshold_Minutes");
  }
  
  /**
   * Reads all messages from log
   * @return string 3 symbols for the messages count
   */
  public function ReadTotalMessagesCount() {
    return $this->execute("ReadTotalMessagesCount");
  }
  
  /**
   * Provides information about the current VAT rates (the last value stored in FM).
   * @return VATratesRes
   */
  public function ReadVATrates() {
    return new \Tremol\VATratesRes($this->execute("ReadVATrates"));
  }
  
  /**
   * Provides information about the device version.
   * @return string Up to 30 symbols for Version name and Check sum
   */
  public function ReadVersion() {
    return $this->execute("ReadVersion");
  }
  
  /**
   * Provides information about WiFi network name where the device is connected.
   * @return WiFi_NetworkNameRes
   */
  public function ReadWiFi_NetworkName() {
    return new \Tremol\WiFi_NetworkNameRes($this->execute("ReadWiFi_NetworkName"));
  }
  
  /**
   * Providing information about WiFi password where the device is connected.
   * @return WiFi_PasswordRes
   */
  public function ReadWiFi_Password() {
    return new \Tremol\WiFi_PasswordRes($this->execute("ReadWiFi_Password"));
  }
  
  /**
   * Provides information about device's idle timeout. This timeout is seconds in which the connection will be closed when there is an inactivity. This information is available if the device has LAN or WiFi. Maximal value - 7200, minimal value 1. 0 is for never close the connection.
   * @return double 4 symbols for password in format ####
   */
  public function Read_IdleTimeout() {
    return $this->execute("Read_IdleTimeout");
  }
  
  /**
   * After every change on Idle timeout, LAN/WiFi/GPRS usage, LAN/WiFi/TCP/GPRS password or TCP auto start networks settings this Save command needs to be execute.
   */
  public function SaveNetworkSettings() {
    $this->execute("SaveNetworkSettings");
  }
  
  /**
   * Scan and print available wifi networks
   */
  public function ScanAndPrintWifiNetworks() {
    $this->execute("ScanAndPrintWifiNetworks");
  }
  
  /**
   * The device scan out the list of available WiFi networks.
   */
  public function ScanWiFiNetworks() {
    $this->execute("ScanWiFiNetworks");
  }
  
  /**
   * Register the sell (for correction use minus sign in the price field) of article with specified name, price, quantity, VAT class and/or discount/addition on the transaction.
   * @param string $NamePLU 36 symbols for article's name
   * @param OptionVATClass $OptionVATClass 1 symbol for article's VAT class with optional values:" 
   *  - 'A' - VAT Class A 
   *  - 'B' - VAT Class B 
   *  - 'C' - VAT Class C 
   *  - 'D' - VAT Class D 
   *  - 'E' - VAT Class E
   * @param double $Price Up to 10 symbols for article's price
   * @param string $MeasureUnit 3 symbols for measure unit
   * @param string $HSCode 10 symbols for HS Code in format XXXX.XX.XX
   * @param string $HSName 20 symbols for HS Name
   * @param double $VATGrRate Up to 5 symbols for programmable VAT rate
   * @param double $Quantity 1 to 10 symbols for quantity
   * @param double $DiscAddP 1 to 7 for percentage of discount/addition
   */
  public function SellPLUfromExtDB($NamePLU,$OptionVATClass,$Price,$MeasureUnit,$HSCode,$HSName,$VATGrRate,$Quantity=NULL,$DiscAddP=NULL) {
    $this->execute("SellPLUfromExtDB", "NamePLU", $NamePLU, "OptionVATClass", $OptionVATClass, "Price", $Price, "MeasureUnit", $MeasureUnit, "HSCode", $HSCode, "HSName", $HSName, "VATGrRate", $VATGrRate, "Quantity", $Quantity, "DiscAddP", $DiscAddP);
  }
  
  /**
   * Register the sell (for correction use minus sign in the price field) of article with specified name, price, quantity, VAT class and/or discount/addition on the transaction.
   * @param string $NamePLU 36 symbols for article's name
   * @param double $Price Up to 10 symbols for article's price
   * @param string $HSCode 10 symbols for HS Code in format XXXX.XX.XX
   * @param double $Quantity 1 to 10 symbols for quantity
   * @param double $DiscAddP 1 to 7 for percentage of discount/addition
   */
  public function SellPLUfromExtDB_HS($NamePLU,$Price,$HSCode,$Quantity=NULL,$DiscAddP=NULL) {
    $this->execute("SellPLUfromExtDB_HS", "NamePLU", $NamePLU, "Price", $Price, "HSCode", $HSCode, "Quantity", $Quantity, "DiscAddP", $DiscAddP);
  }
  
  /**
   * Sets the date and time and current values.
   * @param DateTime $DateTime Date Time parameter in format: DD-MM-YY HH:MM
   */
  public function SetDateTime($DateTime) {
    $this->execute("SetDateTime", "DateTime", $DateTime);
  }
  
  /**
   * Program device's NTP address . To apply use - SaveNetworkSettings()
   * @param double $AddressLen Up to 3 symbols for the address length
   * @param string $NTPAddress 50 symbols for the device's NTP address
   */
  public function SetDeviceNTP_Address($AddressLen,$NTPAddress) {
    $this->execute("SetDeviceNTP_Address", "AddressLen", $AddressLen, "NTPAddress", $NTPAddress);
  }
  
  /**
   * Program device's network IP address, subnet mask, gateway address, DNS address. To apply use -SaveNetworkSettings()
   * @param OptionAddressType $OptionAddressType 1 symbol with value: 
   *  - '2' - IP address 
   *  - '3' - Subnet Mask 
   *  - '4' - Gateway address 
   *  - '5' - DNS address
   * @param string $DeviceAddress 15 symbols for the selected address
   */
  public function SetDeviceTCP_Addresses($OptionAddressType,$DeviceAddress) {
    $this->execute("SetDeviceTCP_Addresses", "OptionAddressType", $OptionAddressType, "DeviceAddress", $DeviceAddress);
  }
  
  /**
   * Program device's MAC address . To apply use - SaveNetworkSettings()
   * @param string $MACAddress 12 symbols for the MAC address
   */
  public function SetDeviceTCP_MACAddress($MACAddress) {
    $this->execute("SetDeviceTCP_MACAddress", "MACAddress", $MACAddress);
  }
  
  /**
   * Program device's TCP network DHCP enabled or disabled. To apply use -SaveNetworkSettings()
   * @param OptionDHCPEnabled $OptionDHCPEnabled 1 symbol with value: 
   *  - '0' - Disabled 
   *  - '1' - Enabled
   */
  public function SetDHCP_Enabled($OptionDHCPEnabled) {
    $this->execute("SetDHCP_Enabled", "OptionDHCPEnabled", $OptionDHCPEnabled);
  }
  
  /**
   * Program device's GPRS APN. To apply use -SaveNetworkSettings()
   * @param double $gprsAPNlength Up to 3 symbols for the APN len
   * @param string $APN Up to 100 symbols for the device's GPRS APN
   */
  public function SetGPRS_APN($gprsAPNlength,$APN) {
    $this->execute("SetGPRS_APN", "gprsAPNlength", $gprsAPNlength, "APN", $APN);
  }
  
  /**
   * Programs GPRS APN authentication type
   * @param OptionAuthenticationType $OptionAuthenticationType 1 symbol with value: 
   * - '0' - None 
   * - '1' - PAP 
   * - '2' - CHAP 
   * - '3' - PAP or CHAP
   */
  public function SetGPRS_AuthenticationType($OptionAuthenticationType) {
    $this->execute("SetGPRS_AuthenticationType", "OptionAuthenticationType", $OptionAuthenticationType);
  }
  
  /**
   * Program device's GPRS password. To apply use - SaveNetworkSettings()
   * @param double $PassLength Up to 3 symbols for the GPRS password len
   * @param string $Password Up to 100 symbols for the device's GPRS password
   */
  public function SetGPRS_Password($PassLength,$Password) {
    $this->execute("SetGPRS_Password", "PassLength", $PassLength, "Password", $Password);
  }
  
  /**
   * Programs server HTTPS address.
   * @param double $ParamLength Up to 3 symbols for parameter length
   * @param string $Address 50 symbols for address
   */
  public function SetHTTPS_Address($ParamLength,$Address) {
    $this->execute("SetHTTPS_Address", "ParamLength", $ParamLength, "Address", $Address);
  }
  
  /**
   * Program device's idle timeout setting. Set timeout for closing the connection if there is an inactivity. Maximal value - 7200, minimal value 1. 0 is for never close the connection. This option can be used only if the device has LAN or WiFi. To apply use - SaveNetworkSettings()
   * @param double $IdleTimeout 4 symbols for Idle timeout in format ####
   */
  public function SetIdle_Timeout($IdleTimeout) {
    $this->execute("SetIdle_Timeout", "IdleTimeout", $IdleTimeout);
  }
  
  /**
   * Programs invoice threshold count
   * @param double $Value Up to 5 symbols for value
   */
  public function SetInvoice_ThresholdCount($Value) {
    $this->execute("SetInvoice_ThresholdCount", "Value", $Value);
  }
  
  /**
   * Stores PIN number in operative memory.
   * @param string $Password 6-symbols string
   * @param string $PINnum 11 symbols for PIN registration number
   */
  public function SetPINnumber($Password,$PINnum) {
    $this->execute("SetPINnumber", "Password", $Password, "PINnum", $PINnum);
  }
  
  /**
   * Stores the Manufacturing number into the operative memory.
   * @param string $Password 6-symbols string
   * @param string $SerialNum 20 symbols Manufacturing number
   */
  public function SetSerialNum($Password,$SerialNum) {
    $this->execute("SetSerialNum", "Password", $Password, "SerialNum", $SerialNum);
  }
  
  /**
   * Program device used to talk with the server . To apply use - SaveNetworkSettings()
   * @param OptionModule $OptionModule 1 symbol with value: 
   *  - '0' - GSM 
   *  - '1' - LAN/WiFi
   */
  public function SetServer_UsedComModule($OptionModule) {
    $this->execute("SetServer_UsedComModule", "OptionModule", $OptionModule);
  }
  
  /**
   * Selects the active communication module - LAN or WiFi. This option can be set only if the device has both modules at the same time. To apply use - SaveNetworkSettings()
   * @param OptionUsedModule $OptionUsedModule 1 symbol with value: 
   *  - '1' - LAN module 
   *  - '2' - WiFi module
   */
  public function SetTCP_ActiveModule($OptionUsedModule) {
    $this->execute("SetTCP_ActiveModule", "OptionUsedModule", $OptionUsedModule);
  }
  
  /**
   * Program device's autostart TCP conection in sale/line mode. To apply use -SaveNetworkSettings()
   * @param OptionTCPAutoStart $OptionTCPAutoStart 1 symbol with value: 
   *  - '0' - No 
   *  - '1' - Yes
   */
  public function SetTCP_AutoStart($OptionTCPAutoStart) {
    $this->execute("SetTCP_AutoStart", "OptionTCPAutoStart", $OptionTCPAutoStart);
  }
  
  /**
   * Program device's TCP password. To apply use - SaveNetworkSettings()
   * @param double $PassLength Up to 3 symbols for the password len
   * @param string $Password Up to 100 symbols for the TCP password
   */
  public function SetTCP_Password($PassLength,$Password) {
    $this->execute("SetTCP_Password", "PassLength", $PassLength, "Password", $Password);
  }
  
  /**
   * Programs time threshold minutes
   * @param double $Value Up to 5 symbols for value
   */
  public function SetTime_ThresholdMinutes($Value) {
    $this->execute("SetTime_ThresholdMinutes", "Value", $Value);
  }
  
  /**
   * Program device's TCP WiFi network name where it will be connected. To apply use -SaveNetworkSettings()
   * @param double $WiFiNameLength Up to 3 symbols for the WiFi network name len
   * @param string $WiFiNetworkName Up to 100 symbols for the device's WiFi ssid network name
   */
  public function SetWiFi_NetworkName($WiFiNameLength,$WiFiNetworkName) {
    $this->execute("SetWiFi_NetworkName", "WiFiNameLength", $WiFiNameLength, "WiFiNetworkName", $WiFiNetworkName);
  }
  
  /**
   * Program device's TCP WiFi password where it will be connected. To apply use -SaveNetworkSettings()
   * @param double $PassLength Up to 3 symbols for the WiFi password len
   * @param string $Password Up to 100 symbols for the device's WiFi password
   */
  public function SetWiFi_Password($PassLength,$Password) {
    $this->execute("SetWiFi_Password", "PassLength", $PassLength, "Password", $Password);
  }
  
  /**
   * Restore default parameters of the device.
   * @param string $Password 6-symbols string
   */
  public function SoftwareReset($Password) {
    $this->execute("SoftwareReset", "Password", $Password);
  }
  
  /**
   * Start GPRS test on the device the result
   */
  public function StartGPRStest() {
    $this->execute("StartGPRStest");
  }
  
  /**
   * Start LAN test on the device the result
   */
  public function StartLANtest() {
    $this->execute("StartLANtest");
  }
  
  /**
   * Start WiFi test on the device the result
   */
  public function StartWiFiTest() {
    $this->execute("StartWiFiTest");
  }
  
  /**
   * Store whole Electronic Journal report to External USB Flash memory, External SD card.
   * @param OptionReportStorage $OptionReportStorage 2 symbols for destination: 
   *  - 'J2' - Storage in External USB Flash memory 
   *  - 'J4' - Storage in External SD card memory 
   *  - 'Jx' - Storage in External USB Flash memory for JSON 
   *  - 'JX' - Storage in External SD card memory for JSON
   */
  public function StoreEJ($OptionReportStorage) {
    $this->execute("StoreEJ", "OptionReportStorage", $OptionReportStorage);
  }
  
  /**
   * Store Electronic Journal Report from report from date to date to External USB Flash memory, External SD card.
   * @param OptionReportStorage $OptionReportStorage 2 symbols for destination: 
   *  - 'J2' - Storage in External USB Flash memory 
   *  - 'J4' - Storage in External SD card memory 
   *  - 'Jx' - Storage in External USB Flash memory for JSON 
   *  - 'JX' - Storage in External SD card memory for JSON
   * @param DateTime $StartRepFromDate 6 symbols for initial date in the DDMMYY format
   * @param DateTime $EndRepFromDate 6 symbols for final date in the DDMMYY format
   */
  public function StoreEJByDate($OptionReportStorage,$StartRepFromDate,$EndRepFromDate) {
    $this->execute("StoreEJByDate", "OptionReportStorage", $OptionReportStorage, "StartRepFromDate", $StartRepFromDate, "EndRepFromDate", $EndRepFromDate);
  }
  
  /**
   * Calculate the subtotal amount with printing and display visualization options. Provide information about values of the calculated amounts. If a percent or value discount/addition has been specified the subtotal and the discount/addition value will be printed regardless the parameter for printing.
   * @param OptionDisplay $OptionDisplay 1 symbol with value: 
   *  - '1' - Yes 
   *  - '0' - No
   * @param double $DiscAddV Up to 8 symbols for the value of the 
   * discount/addition. Use minus sign '-' for discount
   * @param double $DiscAddP Up to 7 symbols for the percentage value of the 
   * discount/addition. Use minus sign '-' for discount
   * @return double Up to 10 symbols for the value of the subtotal amount
   */
  public function Subtotal($OptionDisplay,$DiscAddV=NULL,$DiscAddP=NULL) {
    return $this->execute("Subtotal", "OptionDisplay", $OptionDisplay, "DiscAddV", $DiscAddV, "DiscAddP", $DiscAddP);
  }
  
  /**
   * Applying client library definitions to ZFPLabServer for compatibility.
   */
  public function ApplyClientLibraryDefinitions() {
    $defs = "<Defs><ServerStartupSettings>  <Encoding CodePage=\"1252\" EncodingName=\"Western European (Windows)\" />  <GenerationTimeStamp>2204120945</GenerationTimeStamp>  <SignalFD>0</SignalFD>  <SilentFindDevice>0</SilentFindDevice>  <EM>0</EM> </ServerStartupSettings><Command Name=\"AddNewHScode\" CmdByte=\"0x4F\"><FPOperation>Programs the customer DB for special customer receipt issuing.</FPOperation><Args><Arg Name=\"Option\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionW\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"reserved\" Value=\"0000\" Type=\"OptionHardcoded\" MaxLen=\"4\" /><Arg Name=\"HS_Code\" Value=\"\" Type=\"Text\" MaxLen=\"10\"><Desc>10 symbols for HS code</Desc></Arg><Arg Name=\"HS_Name\" Value=\"\" Type=\"Text\" MaxLen=\"20\"><Desc>20 symbols for name of HS group</Desc></Arg><Arg Name=\"OptionTaxable\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Exempted\" Value=\"1\" /><Option Name=\"Taxable\" Value=\"0\" /></Options><Desc>1 symbol for parameter: - '1' - Exempted - '0' - Taxable</Desc></Arg><Arg Name=\"MesureUnit\" Value=\"\" Type=\"Text\" MaxLen=\"3\"><Desc>3 symbols for mesure unit of item's code</Desc></Arg><Arg Name=\"VAT_rate\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"6\" Format=\"00.00\"><Desc>Value of VAT rate from 2 to 5 symbols with format ##.##</Desc></Arg><ArgsFormatRaw><![CDATA[ <Option['Z']> <;>< OptionW['W']><;><reserved['0000']> <;> <HS_Code[10]> <;> <HS_Name[20]><;><OptionTaxable[1]><;><MesureUnit[3]><;><VAT_rate[2..6]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"CancelReceipt\" CmdByte=\"0x39\"><FPOperation>Available only if the receipt is not closed. Cancel all sales in the receipt and close it .</FPOperation></Command><Command Name=\"ClearDisplay\" CmdByte=\"0x24\"><FPOperation>Clears the external display.</FPOperation></Command><Command Name=\"CloseReceipt\" CmdByte=\"0x38\"><FPOperation>Closes the opened fiscal receipt and returns receipt info.</FPOperation><Response ACK=\"false\"><Res Name=\"InvoiceNum\" Value=\"\" Type=\"Text\" MaxLen=\"19\"><Desc>19 symbols for CU invoice number</Desc></Res><Res Name=\"QRcode\" Value=\"\" Type=\"Text\" MaxLen=\"128\"><Desc>128 symbols for QR code</Desc></Res><ResFormatRaw><![CDATA[<InvoiceNum[19]<;><QRcode[128]>]]></ResFormatRaw></Response></Command><Command Name=\"ConfirmFiscalization\" CmdByte=\"0x41\"><FPOperation>Confirm PIN number.</FPOperation><Args><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"6\"><Desc>6-symbols string</Desc></Arg><Arg Name=\"\" Value=\"2\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <Password[6]> <;> <'2'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"DailyReport\" CmdByte=\"0x7C\"><FPOperation>Provides information for the daily fiscal report with zeroing and fiscal memory record, preceded by Electronic Journal report.</FPOperation><Args><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'Z'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"DirectCommand\" CmdByte=\"0xF1\"><FPOperation>Executes the direct command .</FPOperation><Args><Arg Name=\"Input\" Value=\"\" Type=\"Text\" MaxLen=\"200\"><Desc>Raw request to FP</Desc></Arg></Args><Response ACK=\"false\"><Res Name=\"Output\" Value=\"\" Type=\"Text\" MaxLen=\"200\"><Desc>FP raw response</Desc></Res></Response></Command><Command Name=\"DisplayDateTime\" CmdByte=\"0x28\"><FPOperation>Shows the current date and time on the external display.</FPOperation></Command><Command Name=\"DisplayTextLine1\" CmdByte=\"0x25\"><FPOperation>Shows a 20-symbols text in the upper external display line.</FPOperation><Args><Arg Name=\"Text\" Value=\"\" Type=\"Text\" MaxLen=\"16\"><Desc>16 symbols text</Desc></Arg><ArgsFormatRaw><![CDATA[ <Text[16]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"DisplayTextLine2\" CmdByte=\"0x26\"><FPOperation>Shows a 16-symbols text in the lower external display line.</FPOperation><Args><Arg Name=\"Text\" Value=\"\" Type=\"Text\" MaxLen=\"16\"><Desc>16 symbols text</Desc></Arg><ArgsFormatRaw><![CDATA[ <Text[16]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"DisplayTextLines1and2\" CmdByte=\"0x27\"><FPOperation>Shows a 16-symbols text in the first line and last 16-symbols text in the second line of the external display lines.</FPOperation><Args><Arg Name=\"Text\" Value=\"\" Type=\"Text\" MaxLen=\"32\"><Desc>32 symbols text</Desc></Arg><ArgsFormatRaw><![CDATA[ <Text[32]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"EraseHScodes\" CmdByte=\"0x4F\"><FPOperation>Erase HS codes.</FPOperation><Args><Arg Name=\"Option\" Value=\"z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionR\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"6\"><Desc>6 symbols for password</Desc></Arg><ArgsFormatRaw><![CDATA[ <Option['z']><;><OptionR['D']><;><Password[6]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"InfoLastReceiptDuplicate\" CmdByte=\"0x3A\"><FPOperation>Informs about the issued document</FPOperation></Command><Command Name=\"OpenCreditNoteWithFreeCustomerData\" CmdByte=\"0x30\"><FPOperation>Opens a fiscal invoice credit note receipt assigned to the specified operator number and operator password with free info for customer data. The Invoice receipt can be issued only if the invoice range (start and end numbers) is set.</FPOperation><Args><Arg Name=\"reserved\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"reserved\" Value=\"   0\" Type=\"OptionHardcoded\" MaxLen=\"6\" /><Arg Name=\"reserved\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"InvoiceDebitNoteType\" Value=\"A\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"CompanyName\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for Invoice company name</Desc></Arg><Arg Name=\"ClientPINnum\" Value=\"\" Type=\"Text\" MaxLen=\"14\"><Desc>14 symbols for client PIN number</Desc></Arg><Arg Name=\"HeadQuarters\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for customer headquarters</Desc></Arg><Arg Name=\"Address\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for Address</Desc></Arg><Arg Name=\"PostalCodeAndCity\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for postal code and city</Desc></Arg><Arg Name=\"ExemptionNum\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for exemption number</Desc></Arg><Arg Name=\"RelatedInvoiceNum\" Value=\"\" Type=\"Text\" MaxLen=\"19\"><Desc>19 symbols for the related invoice number in format ###################</Desc></Arg><Arg Name=\"TraderSystemInvNum\" Value=\"\" Type=\"Text\" MaxLen=\"15\"><Desc>15 symbols for trader system invoice number</Desc></Arg><ArgsFormatRaw><![CDATA[ <reserved['1']> <;> <reserved['   0']> <;> <reserved['0']> <;> <InvoiceDebitNoteType['A']> <;> <CompanyName[30]> <;> <ClientPINnum[14]> <;> <HeadQuarters[30]> <;> <Address[30]> <;> <PostalCodeAndCity[30]> <;> <ExemptionNum[30]> <;> <RelatedInvoiceNum[19]><;><TraderSystemInvNum[15]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"OpenDebitNoteWithFreeCustomerData\" CmdByte=\"0x30\"><FPOperation>Opens a fiscal invoice debit note receipt assigned to the specified operator number and operator password with free info for customer data. The Invoice receipt can be issued only if the invoice range (start and end numbers) is set.</FPOperation><Args><Arg Name=\"reserved\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"reserved\" Value=\"   0\" Type=\"OptionHardcoded\" MaxLen=\"6\" /><Arg Name=\"reserved\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"InvoiceDebitNoteType\" Value=\"@\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"CompanyName\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for Invoice company name</Desc></Arg><Arg Name=\"ClientPINnum\" Value=\"\" Type=\"Text\" MaxLen=\"14\"><Desc>14 symbols for client PIN number</Desc></Arg><Arg Name=\"HeadQuarters\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for customer headquarters</Desc></Arg><Arg Name=\"Address\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for Address</Desc></Arg><Arg Name=\"PostalCodeAndCity\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for postal code and city</Desc></Arg><Arg Name=\"ExemptionNum\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for exemption number</Desc></Arg><Arg Name=\"RelatedInvoiceNum\" Value=\"\" Type=\"Text\" MaxLen=\"19\"><Desc>19 symbols for the related invoice number in format ###################</Desc></Arg><Arg Name=\"TraderSystemInvNum\" Value=\"\" Type=\"Text\" MaxLen=\"15\"><Desc>15 symbols for trader system invoice number</Desc></Arg><ArgsFormatRaw><![CDATA[ <reserved['1']> <;> <reserved['   0']> <;> <reserved['0']> <;> <InvoiceDebitNoteType['@']> <;> <CompanyName[30]> <;> <ClientPINnum[14]> <;> <HeadQuarters[30]> <;> <Address[30]> <;> <PostalCodeAndCity[30]> <;> <ExemptionNum[30]> <;> <RelatedInvoiceNum[19]><;><TraderSystemInvNum[15]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"OpenInvoiceWithFreeCustomerData\" CmdByte=\"0x30\"><FPOperation>Opens a fiscal invoice receipt assigned to the specified operator number and operator password with free info for customer data. The Invoice receipt can be issued only if the invoice range (start and end numbers) is set.</FPOperation><Args><Arg Name=\"reserved\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"reserved\" Value=\"   0\" Type=\"OptionHardcoded\" MaxLen=\"6\" /><Arg Name=\"reserved\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"InvoiceType\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"CompanyName\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for Invoice company name</Desc></Arg><Arg Name=\"ClientPINnum\" Value=\"\" Type=\"Text\" MaxLen=\"14\"><Desc>14 symbols for client PIN number</Desc></Arg><Arg Name=\"HeadQuarters\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for customer headquarters</Desc></Arg><Arg Name=\"Address\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for Address</Desc></Arg><Arg Name=\"PostalCodeAndCity\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for postal code and city</Desc></Arg><Arg Name=\"ExemptionNum\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>30 symbols for exemption number</Desc></Arg><Arg Name=\"TraderSystemInvNum\" Value=\"\" Type=\"Text\" MaxLen=\"15\"><Desc>15 symbols for trader system invoice number</Desc></Arg><ArgsFormatRaw><![CDATA[ <reserved['1']> <;> <reserved['   0']> <;> <reserved['0']> <;> <InvoiceType['1']> <;> <CompanyName[30]> <;> <ClientPINnum[14]> <;> <HeadQuarters[30]> <;> <Address[30]> <;> <PostalCodeAndCity[30]> <;> <ExemptionNum[30]> <;><TraderSystemInvNum[15]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"OpenReceipt\" CmdByte=\"0x30\"><FPOperation>Opens a fiscal receipt assigned to the specified operator number and operator password, parameters for receipt format and VAT type.</FPOperation><Args><Arg Name=\"reserved\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"reserved\" Value=\"   0\" Type=\"OptionHardcoded\" MaxLen=\"6\" /><Arg Name=\"OptionReceiptFormat\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Brief\" Value=\"0\" /><Option Name=\"Detailed\" Value=\"1\" /></Options><Desc>1 symbol with value:  - '1' - Detailed  - '0' - Brief</Desc></Arg><Arg Name=\"ReceiptType\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"TraderSystemInvNum\" Value=\"\" Type=\"Text\" MaxLen=\"15\"><Desc>15 symbols for trader system invoice number</Desc></Arg><ArgsFormatRaw><![CDATA[<reserved['1']> <;> <reserved['   0']> <;> < ReceiptFormat [1]> <;> <ReceiptType['0']><;><TraderSystemInvNum[15]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"ProgHScode\" CmdByte=\"0x4F\"><FPOperation>Programs HS code at a given position (HS number in order).</FPOperation><Args><Arg Name=\"Option\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionW\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"HS_Number\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"4\" Format=\"0000\"><Desc>4 symbols for HS number in order in format ####</Desc></Arg><Arg Name=\"HS_Code\" Value=\"\" Type=\"Text\" MaxLen=\"10\"><Desc>10 symbols for HS code</Desc></Arg><Arg Name=\"HS_Name\" Value=\"\" Type=\"Text\" MaxLen=\"20\"><Desc>20 symbols for name of HS group</Desc></Arg><Arg Name=\"OptionTaxable\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Exempted\" Value=\"1\" /><Option Name=\"Taxable\" Value=\"0\" /></Options><Desc>1 symbol for parameter: - '1' - Exempted - '0' - Taxable</Desc></Arg><Arg Name=\"MesureUnit\" Value=\"\" Type=\"Text\" MaxLen=\"3\"><Desc>3 symbols for mesure unit of item's code</Desc></Arg><Arg Name=\"VAT_Rate\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"6\" Format=\"00.00\"><Desc>Value of VAT rate from 2 to 5 symbols with format ##.##</Desc></Arg><ArgsFormatRaw><![CDATA[ <Option['Z']> <;><OptionW['W']><;><HS_Number[4]> <;> <HS_Code[10]> <;> <HS_Name[20]><;><OptionTaxable[1]> <;> <MesureUnit[3]> <;> < VAT_Rate[2..6]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"ProgVATrates\" CmdByte=\"0x42\"><FPOperation>Stores a block containing the values of the VAT rates into the CU</FPOperation><Args><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"6\"><Desc>6-symbols string</Desc></Arg><Arg Name=\"VATrateA\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"6\" Format=\"00.00\"><Desc>Value of VAT rate A from 2 to 6 symbols with format ##.##</Desc></Arg><Arg Name=\"VATrateB\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"6\" Format=\"00.00\"><Desc>Value of VAT rate B from 2 to 6 symbols with format ##.##</Desc></Arg><Arg Name=\"VATrateC\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"6\" Format=\"00.00\"><Desc>Value of VAT rate C from 2 to 6 symbols with format ##.##</Desc></Arg><Arg Name=\"VATrateD\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"6\" Format=\"00.00\"><Desc>Value of VAT rate D from 2 to 6 symbols with format ##.##</Desc></Arg><Arg Name=\"VATrateE\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"6\" Format=\"00.00\"><Desc>Value of VAT rate E from 2 to 6 symbols with format ##.##</Desc></Arg><ArgsFormatRaw><![CDATA[ <Password[6]> <;> <VATrateA[1..6]> <;> <VATrateB[1..6]> <;> <VATrateC[1..6]> <;> <VATrateD[1..6]> <;><VATrateE[1..6]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"RawRead\" CmdByte=\"0xFF\"><FPOperation> Reads raw bytes from FP.</FPOperation><Args><Arg Name=\"Count\" Value=\"\" Type=\"Decimal\" MaxLen=\"5\"><Desc>How many bytes to read if EndChar is not specified</Desc></Arg><Arg Name=\"EndChar\" Value=\"\" Type=\"Text\" MaxLen=\"1\"><Desc>The character marking the end of the data. If present Count parameter is ignored.</Desc></Arg></Args><Response ACK=\"false\"><Res Name=\"Bytes\" Value=\"\" Type=\"Base64\" MaxLen=\"100000\"><Desc>FP raw response in BASE64 encoded string</Desc></Res></Response></Command><Command Name=\"RawWrite\" CmdByte=\"0xFE\"><FPOperation> Writes raw bytes to FP </FPOperation><Args><Arg Name=\"Bytes\" Value=\"\" Type=\"Base64\" MaxLen=\"5000\"><Desc>The bytes in BASE64 ecoded string to be written to FP</Desc></Arg></Args></Command><Command Name=\"ReadCUnumbers\" CmdByte=\"0x60\"><FPOperation>Provides information about the manufacturing number of the CU and PIN number.</FPOperation><Response ACK=\"false\"><Res Name=\"SerialNumber\" Value=\"\" Type=\"Text\" MaxLen=\"20\"><Desc>20 symbols for individual number of the CU</Desc></Res><Res Name=\"PINnumber\" Value=\"\" Type=\"Text\" MaxLen=\"11\"><Desc>11 symbols for pin number</Desc></Res><ResFormatRaw><![CDATA[<SerialNumber[20]><;><PINnumber[11]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadCurrentReceiptInfo\" CmdByte=\"0x72\"><FPOperation>Read the current status of the receipt.</FPOperation><Response ACK=\"false\"><Res Name=\"OptionIsReceiptOpened\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol with value:  - '0' - No  - '1' - Yes</Desc></Res><Res Name=\"SalesNumber\" Value=\"\" Type=\"Text\" MaxLen=\"3\"><Desc>3 symbols for number of sales</Desc></Res><Res Name=\"SubtotalAmountVATGA\" Value=\"\" Type=\"Decimal\" MaxLen=\"11\"><Desc>Up to 11 symbols for subtotal by VAT group A</Desc></Res><Res Name=\"SubtotalAmountVATGB\" Value=\"\" Type=\"Decimal\" MaxLen=\"11\"><Desc>Up to 11 symbols for subtotal by VAT group B</Desc></Res><Res Name=\"SubtotalAmountVATGC\" Value=\"\" Type=\"Decimal\" MaxLen=\"11\"><Desc>Up to 11 symbols for subtotal by VAT group C</Desc></Res><Res Name=\"SubtotalAmountVATGD\" Value=\"\" Type=\"Decimal\" MaxLen=\"11\"><Desc>Up to 11 symbols for subtotal by VAT group D</Desc></Res><Res Name=\"SubtotalAmountVATGE\" Value=\"\" Type=\"Decimal\" MaxLen=\"11\"><Desc>Up to 11 symbols for subtotal by VAT group E</Desc></Res><Res Name=\"reserved1\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"reserved2\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionReceiptFormat\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Brief\" Value=\"0\" /><Option Name=\"Detailed\" Value=\"1\" /></Options><Desc>(Format) 1 symbol with value:  - '1' - Detailed  - '0' - Brief</Desc></Res><Res Name=\"reserved3\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"reserved4\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionClientReceipt\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"invoice (client) receipt\" Value=\"1\" /><Option Name=\"standard receipt\" Value=\"0\" /></Options><Desc>1 symbol with value:  - '1' - invoice (client) receipt  - '0' - standard receipt</Desc></Res><Res Name=\"OptionPowerDownInReceipt\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol with value: - '0' - No - '1' - Yes</Desc></Res><Res Name=\"reserved5\" Value=\"\" Type=\"Decimal\" MaxLen=\"11\"><Desc>Up to 11 symbols</Desc></Res><ResFormatRaw><![CDATA[<IsReceiptOpened[1]> <;> <SalesNumber[3]> <;> <SubtotalAmountVATGA[1..11]> <;> <SubtotalAmountVATGB[1..11]> <;> <SubtotalAmountVATGC[1..11]> <;> <SubtotalAmountVATGD[1..11]> <;> <SubtotalAmountVATGE[1..11]> <;> <reserved1['0']> <;><reserved2['0']> <;> <ReceiptFormat[1]> <;> <reserved3['0']> <;> <reserved4['0']> <;> <ClientReceipt[1]> <;> <PowerDownInReceipt[1]> <;> <reserved5[1..11]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadDailyAmountsByVAT\" CmdByte=\"0x6D\"><FPOperation>Provides information about the accumulated amounts and refunded amounts by VAT class in case that CU regularly informs about the Z report(7C)</FPOperation><Response ACK=\"false\"><Res Name=\"SaleAmountVATGrA\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the amount accumulated in the VAT group A</Desc></Res><Res Name=\"SaleAmountVATGrB\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the amount accumulated in the VAT group B</Desc></Res><Res Name=\"SaleAmountVATGrC\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the amount accumulated in the VAT group C</Desc></Res><Res Name=\"SaleAmountVATGrD\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the amount accumulated in the VAT group D</Desc></Res><Res Name=\"SaleAmountVATGrE\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the amount accumulated in the VAT group E</Desc></Res><Res Name=\"TurnoverAmountVAT\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the turnover amount for VATs A, B, C, D</Desc></Res><Res Name=\"RefundAmountVATGrA\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the refund amount accumulated in the VAT group A</Desc></Res><Res Name=\"RefundAmountVATGrB\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the refund amount accumulated in the VAT group B</Desc></Res><Res Name=\"RefundAmountVATGrC\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the refund amount accumulated in the VAT group C</Desc></Res><Res Name=\"RefundAmountVATGrD\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the refund amount accumulated in the VAT group D</Desc></Res><Res Name=\"RefundAmountVATGrE\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the refund amount accumulated in the VAT group E</Desc></Res><Res Name=\"TurnoverRefAmountVAT\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the refund turnover amount for VATs A, B, C, D</Desc></Res><ResFormatRaw><![CDATA[<SaleAmountVATGrA[1..13]> <;> <SaleAmountVATGrB[1..13]> <;> <SaleAmountVATGrC[1..13]> <;><SaleAmountVATGrD[1..13]> <;><SaleAmountVATGrE[1..13]> <;> <TurnoverAmountVAT[1..13]> <;> <RefundAmountVATGrA[1..13]> <;> <RefundAmountVATGrB[1..13]> <;> <RefundAmountVATGrC[1..13]> <;> <RefundAmountVATGrD[1..13]> <;> <RefundAmountVATGrE[1..13]> <;> <TurnoverRefAmountVAT[1..13]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadDateTime\" CmdByte=\"0x68\"><FPOperation>Provides information about the current date and time.</FPOperation><Response ACK=\"false\"><Res Name=\"DateTime\" Value=\"\" Type=\"DateTime\" MaxLen=\"10\" Format=\"dd-MM-yyyy HH:mm\"><Desc>Date Time parameter in format: DD-MM-YY [Space] hh:mm</Desc></Res><ResFormatRaw><![CDATA[<DateTime \"DD-MM-YYYY HH:MM\">]]></ResFormatRaw></Response></Command><Command Name=\"ReadDeviceModuleSupport\" CmdByte=\"0x4E\"><FPOperation>FlagsModule is a char with bits representing modules supported by the device.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'D'><;><'D'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionLAN\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol for LAN suppor - '0' - No  - '1' - Yes</Desc></Res><Res Name=\"OptionWiFi\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol for WiFi support - '0' - No  - '1' - Yes</Desc></Res><Res Name=\"OptionGPRS\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol for GPRS support - '0' - No  - '1' - Yes BT (Bluetooth) 1 symbol for Bluetooth support - '0' - No  - '1' - Yes</Desc></Res><Res Name=\"OptionBT\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>(Bluetooth) 1 symbol for Bluetooth support - '0' - No  - '1' - Yes</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'D'><;><'D'><;><LAN[1]><;><WiFi>[1]><;><GPRS>[1]><;><BT[1]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadDeviceModuleSupportByFirmware\" CmdByte=\"0x4E\"><FPOperation>FlagsModule is a char with bits representing modules supported by the firmware</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'D'><;><'S'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionLAN\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol for LAN suppor - '0' - No  - '1' - Yes</Desc></Res><Res Name=\"OptionWiFi\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol for WiFi support - '0' - No  - '1' - Yes</Desc></Res><Res Name=\"OptionGPRS\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol for GPRS support - '0' - No  - '1' - Yes BT (Bluetooth) 1 symbol for Bluetooth support - '0' - No  - '1' - Yes</Desc></Res><Res Name=\"OptionBT\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>(Bluetooth) 1 symbol for Bluetooth support - '0' - No  - '1' - Yes</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'D'><;><'S'><;><LAN[1]><;><WiFi>[1]><;><GPRS>[1]><;><BT[1]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadDeviceTCP_Addresses\" CmdByte=\"0x4E\"><FPOperation>Provides information about device's network IP address, subnet mask, gateway address, DNS address.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionAddressType\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"DNS address\" Value=\"5\" /><Option Name=\"Gateway address\" Value=\"4\" /><Option Name=\"IP address\" Value=\"2\" /><Option Name=\"Subnet Mask\" Value=\"3\" /></Options><Desc>1 symbol with value:  - '2' - IP address  - '3' - Subnet Mask  - '4' - Gateway address  - '5' - DNS address</Desc></Arg><ArgsFormatRaw><![CDATA[ <'R'><;><'T'><;><AddressType[1]> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionAddressType\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"DNS address\" Value=\"5\" /><Option Name=\"Gateway address\" Value=\"4\" /><Option Name=\"IP address\" Value=\"2\" /><Option Name=\"Subnet Mask\" Value=\"3\" /></Options><Desc>(Address type) 1 symbol with value:  - '2' - IP address  - '3' - Subnet Mask  - '4' - Gateway address  - '5' - DNS address</Desc></Res><Res Name=\"DeviceAddress\" Value=\"\" Type=\"Text\" MaxLen=\"15\"><Desc>15 symbols for the device's addresses</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'T'><;>< AddressType[1]><;><DeviceAddress[15]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadDHCP_Status\" CmdByte=\"0x4E\"><FPOperation>Provides information about device's DHCP status</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'T'><;><'1'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionDHCPEnabled\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Disabled\" Value=\"0\" /><Option Name=\"Enabled\" Value=\"1\" /></Options><Desc>(Status) 1 symbols for device's DHCP status - '0' - Disabled  - '1' - Enabled</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'T'><;><'1'><;><DHCPEnabled[1]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadDiagnostics\" CmdByte=\"0x22\"><FPOperation>Provides information about documents sending functions .</FPOperation><Response ACK=\"false\"><Res Name=\"OptionDeviceType\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"A Type\" Value=\"1\" /><Option Name=\"B Type\" Value=\"2\" /></Options><Desc>1 symbol for device type:  - '1' - A Type  - '2' - B Type</Desc></Res><Res Name=\"SDIdxPos\" Value=\"\" Type=\"Text\" MaxLen=\"10\"><Desc>10 symbols for current SD index position of last sent receipt</Desc></Res><Res Name=\"LastInvoiceCUNum\" Value=\"\" Type=\"Text\" MaxLen=\"19\"><Desc>19 symbols for number of last invoice according the CU</Desc></Res><Res Name=\"LastInvoiceDate\" Value=\"\" Type=\"Text\" MaxLen=\"6\"><Desc>6 symbols for last invoice date in the DDMMYY format</Desc></Res><Res Name=\"LastEODDate\" Value=\"\" Type=\"Text\" MaxLen=\"6\"><Desc>6 symbols for last sent EOD in the DDMMYY format</Desc></Res><Res Name=\"InvoicesSent\" Value=\"\" Type=\"Text\" MaxLen=\"4\"><Desc>4 symbold for number of invoices sent for the current day</Desc></Res><ResFormatRaw><![CDATA[<DeviceType[1]> <;> <SDIdxPos[10]> <;> <LastInvoiceCUNum[19]> <;> <LastInvoiceDate[6]> <;> <LastEODDate[6]> <;> <InvoicesSent[4]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadEJ\" CmdByte=\"0x7C\"><FPOperation>Read whole Electronic Journal report from beginning to the end.</FPOperation><Args><Arg Name=\"OptionReadEJStorage\" Value=\"\" Type=\"Option\" MaxLen=\"2\"><Options><Option Name=\"Reading to PC\" Value=\"J0\" /><Option Name=\"Reading to PC for JSON\" Value=\"JY\" /></Options><Desc>2 symbols for destination:  - 'J0' - Reading to PC  - 'JY' - Reading to PC for JSON</Desc></Arg><Arg Name=\"\" Value=\"*\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ < ReadEJStorage [2]><;><'*'> ]]></ArgsFormatRaw></Args><Response ACK=\"true\" ACK_PLUS=\"true\" /></Command><Command Name=\"ReadEJByDate\" CmdByte=\"0x7C\"><FPOperation>Read Electronic Journal Report initial date to report end date.</FPOperation><Args><Arg Name=\"OptionReadEJStorage\" Value=\"\" Type=\"Option\" MaxLen=\"2\"><Options><Option Name=\"Reading to PC\" Value=\"J0\" /><Option Name=\"Reading to PC for JSON\" Value=\"JY\" /></Options><Desc>2 symbols for destination:  - 'J0' - Reading to PC  - 'JY' - Reading to PC for JSON</Desc></Arg><Arg Name=\"\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"StartRepFromDate\" Value=\"\" Type=\"DateTime\" MaxLen=\"10\" Format=\"ddMMyy\"><Desc>6 symbols for initial date in the DDMMYY format</Desc></Arg><Arg Name=\"EndRepFromDate\" Value=\"\" Type=\"DateTime\" MaxLen=\"10\" Format=\"ddMMyy\"><Desc>6 symbols for final date in the DDMMYY format</Desc></Arg><ArgsFormatRaw><![CDATA[ < ReadEJStorage [2]><;><'D'><;><StartRepFromDate \"DDMMYY\"><;> <EndRepFromDate \"DDMMYY\"> ]]></ArgsFormatRaw></Args><Response ACK=\"true\" ACK_PLUS=\"true\" /></Command><Command Name=\"ReadEODAmounts\" CmdByte=\"0x6D\"><FPOperation>Provides information about the accumulated EOD turnover and VAT</FPOperation><Args><Arg Name=\"Option\" Value=\"d\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <Option['d']> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"Option\" Value=\"d\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"EOD_sale_turnover\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the EOD sale turnover</Desc></Res><Res Name=\"EOD_credit_turnover\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the EOD credit turnover</Desc></Res><Res Name=\"EOD_saleVAT\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the EOD VAT from sales</Desc></Res><Res Name=\"EOD_creditVAT\" Value=\"\" Type=\"Decimal\" MaxLen=\"13\"><Desc>Up to 13 symbols for the EOD VAT from credit invoices</Desc></Res><ResFormatRaw><![CDATA[<Option['d']> <;> <EOD_sale_turnover[1..13]> <;> <EOD_credit_turnover[1..13]> <;> <EOD_saleVAT [1..13]> <;> <EOD_creditVAT [1..13]> <;>]]></ResFormatRaw></Response></Command><Command Name=\"ReadGPRS_APN\" CmdByte=\"0x4E\"><FPOperation>Provides information about device's GRPS APN.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"A\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'G'><;><'A'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"A\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"gprsAPNlength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the APN length</Desc></Res><Res Name=\"APN\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>(APN) Up to 100 symbols for the device's GPRS APN</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'G'><;><'A'><;><gprsAPNlength[1..3]><;><APN[100]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadGPRS_AuthenticationType\" CmdByte=\"0x4E\"><FPOperation>Read GPRS APN authentication type</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'G'><;><'N'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionAuthenticationType\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"CHAP\" Value=\"2\" /><Option Name=\"None\" Value=\"0\" /><Option Name=\"PAP\" Value=\"1\" /><Option Name=\"PAP or CHAP\" Value=\"3\" /></Options><Desc>1 symbol with value: - '0' - None - '1' - PAP - '2' - CHAP - '3' - PAP or CHAP</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'G'><;><'N'><;><AuthenticationType[1]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadGPRS_Password\" CmdByte=\"0x4E\"><FPOperation>Provides information about device's GPRS password.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'G'><;><'P'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"PassLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the GPRS password length</Desc></Res><Res Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>Up to 100 symbols for the device's GPRS password</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'G'><;><'P'><;><PassLength[1..3]><;><Password[100]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadGPRS_Username\" CmdByte=\"0x4E\"><FPOperation>Providing information about device's GPRS user name.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"U\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'G'><;><'U'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"U\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"gprsUserNameLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the GPRS username length</Desc></Res><Res Name=\"Username\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>Up to 100 symbols for the device's GPRS username</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'G'><;><'U'><;><gprsUserNameLength[1..3]><;><Username[100]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadHScode\" CmdByte=\"0x4F\"><FPOperation>Programs HS code at a given position (HS number in order).</FPOperation><Args><Arg Name=\"Option\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionR\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"HS_Number\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"4\" Format=\"0000\"><Desc>4 symbols for HS number in order in format ####</Desc></Arg><ArgsFormatRaw><![CDATA[ <Option['Z']> <;><OptionR['R']><;><HS_Number[4]> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"Option\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionR\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"HS_Number\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"4\" Format=\"0000\"><Desc>4 symbols for HS number in order in format ####</Desc></Res><Res Name=\"HS_Code\" Value=\"\" Type=\"Text\" MaxLen=\"10\"><Desc>10 symbols for HS code</Desc></Res><Res Name=\"HS_Name\" Value=\"\" Type=\"Text\" MaxLen=\"20\"><Desc>20 symbols for name of HS group</Desc></Res><Res Name=\"OptionTaxable\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Exempted\" Value=\"1\" /><Option Name=\"Taxable\" Value=\"0\" /></Options><Desc>1 symbol for parameter: - '1' - Exempted - '0' - Taxable</Desc></Res><Res Name=\"MesureUnit\" Value=\"\" Type=\"Text\" MaxLen=\"3\"><Desc>3 symbols for mesure unit of item's code</Desc></Res><Res Name=\"VAT_Rate\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"6\" Format=\"00.00\"><Desc>(VAT rate) Value of VAT rate from 2 to 5 symbols with format ##.##</Desc></Res><ResFormatRaw><![CDATA[<Option['Z']> <;><OptionR['R']><;><HS_Number[4]> <;> <HS_Code[10]> <;> <HS_Name[20]> <;><OptionTaxable[1]> <;> <MesureUnit[3]> <;> < VAT_Rate[2..6]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadHScodeNumber\" CmdByte=\"0x4F\"><FPOperation>Read the number of HS codes.</FPOperation><Args><Arg Name=\"Option\" Value=\"z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionR\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <Option['z']> <;><OptionR['R']> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"Option\" Value=\"z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionR\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"HScodesNumber\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"4\" Format=\"0000\"><Desc>4 symbols for HS codes number in format ####</Desc></Res><ResFormatRaw><![CDATA[<Option['z']> <;><OptionR['R']><;>< HScodesNumber [4]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadHTTPS_Server\" CmdByte=\"0x4E\"><FPOperation>Providing information about server HTTPS address.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"H\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'S'><;><'H'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"C\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"ParamLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for parameter length</Desc></Res><Res Name=\"Address\" Value=\"\" Type=\"Text\" MaxLen=\"50\"><Desc>50 symbols for address</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'S'><;><'C'><;><ParamLength[1..3]><;><Address[50]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadInfoFromLastServerCommunication\" CmdByte=\"0x5A\"><FPOperation>Provide information from the last communication with the server.</FPOperation><Args><Arg Name=\"Option\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionServerResponse\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"At send EOD\" Value=\"Z\" /><Option Name=\"At send receipt\" Value=\"R\" /></Options><Desc>1 symbol with value - 'R' - At send receipt - 'Z' - At send EOD</Desc></Arg><Arg Name=\"OptionTransactionType\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Error Code\" Value=\"c\" /><Option Name=\"Error Message\" Value=\"m\" /><Option Name=\"Exception Message\" Value=\"e\" /><Option Name=\"Status\" Value=\"s\" /></Options><Desc>1 symbol with value - 'c' - Error Code - 'm' - Error Message - 's' - Status - 'e' - Exception Message</Desc></Arg><ArgsFormatRaw><![CDATA[ <Option['S']><;> <ServerResponse[1]><;><TransactionType[1]> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"Option\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionServerResponse\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"At send EOD\" Value=\"Z\" /><Option Name=\"At send receipt\" Value=\"R\" /></Options><Desc>1 symbol with value - 'R' - At send receipt - 'Z' - At send EOD</Desc></Res><Res Name=\"OptionTransactionType\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Error Code\" Value=\"c\" /><Option Name=\"Error Message\" Value=\"m\" /><Option Name=\"Exception Message\" Value=\"e\" /><Option Name=\"Status\" Value=\"s\" /></Options><Desc>1 symbol with value - 'c' - Error Code - 'm' - Error Message - 's' - Status - 'e' - Exception Message</Desc></Res><Res Name=\"Message\" Value=\"\" Type=\"Text\" MaxLen=\"200\"><Desc>Up to 200 symbols for the message from the server</Desc></Res><ResFormatRaw><![CDATA[<Option['S']><;> <ServerResponse[1]><;><TransactionType[1]><;><Message[200]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadInvoice_Threshold\" CmdByte=\"0x4E\"><FPOperation>Read invoice threshold count</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"I\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'S'><;><'I'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"I\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"Value\" Value=\"\" Type=\"Decimal\" MaxLen=\"5\"><Desc>Up to 5 symbols for value</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'S'><;><'I'><;><Value[1..5]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadLastAndTotalReceiptNum\" CmdByte=\"0x71\"><FPOperation>Provides information about the number of the last issued receipt.</FPOperation><Response ACK=\"false\"><Res Name=\"LastCUInvoiceNum\" Value=\"\" Type=\"Text\" MaxLen=\"19\"><Desc>19 symbols for the last number of invoice according the middleware, CU, internal invoice counter</Desc></Res><Res Name=\"LastReceiptNum\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"7\" Format=\"0000000\"><Desc>7 symbols for last receipt number in format #######</Desc></Res><ResFormatRaw><![CDATA[<LastCUInvoiceNum[19]> <;> <LastReceiptNum[7]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadNTP_Address\" CmdByte=\"0x4E\"><FPOperation>Provides information about device's NTP address.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'S'><;><'N' > ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"AddressLen\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the address length</Desc></Res><Res Name=\"NTPAddress\" Value=\"\" Type=\"Text\" MaxLen=\"50\"><Desc>(NTP Address)50 symbols for the device's NTP address</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'S'><;><'N'><;><AddressLen[1..3]><;><NTPAddress[50]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadOrStoreInvoiceCopy\" CmdByte=\"0x7C\"><FPOperation>Read/Store Invoice receipt copy to External USB Flash memory, External SD card.</FPOperation><Args><Arg Name=\"OptionInvoiceCopy\" Value=\"\" Type=\"Option\" MaxLen=\"2\"><Options><Option Name=\"Reading\" Value=\"J0\" /><Option Name=\"Storage in External SD card memory\" Value=\"J4\" /><Option Name=\"Storage in External USB Flash memory.\" Value=\"J2\" /></Options><Desc>2 symbols for destination:  - 'J0' - Reading  - 'J2' - Storage in External USB Flash memory.  - 'J4' - Storage in External SD card memory</Desc></Arg><Arg Name=\"\" Value=\"I\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"CUInvoiceNum\" Value=\"\" Type=\"Text\" MaxLen=\"10\"><Desc>10 symbols for Invoice receipt Number.</Desc></Arg><ArgsFormatRaw><![CDATA[ <OptionInvoiceCopy[2]><;><'I'><;> <CUInvoiceNum[10]> ]]></ArgsFormatRaw></Args><Response ACK=\"true\" ACK_PLUS=\"true\" /></Command><Command Name=\"ReadServer_UsedComModule\" CmdByte=\"0x4E\"><FPOperation>Read device communication usage with server</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"E\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'S'><;><'E'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"E\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionModule\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"GSM\" Value=\"0\" /><Option Name=\"LAN/WiFi\" Value=\"1\" /></Options><Desc>1 symbol with value:  - '0' - GSM  - '1' - LAN/WiFi</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'S'><;><'E'><;><Module [1]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadSpecificMessage\" CmdByte=\"0x4E\"><FPOperation>Reads specific message number</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"L\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"MessageNum\" Value=\"\" Type=\"Text\" MaxLen=\"2\"><Desc>2 symbols for total number of messages</Desc></Arg><ArgsFormatRaw><![CDATA[ <'R'><;><'L'><;><MessageNum[2]> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"L\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"MessageNum\" Value=\"\" Type=\"Text\" MaxLen=\"2\"><Desc>2 symbols for total number of messages</Desc></Res><Res Name=\"DateTime\" Value=\"\" Type=\"DateTime\" MaxLen=\"10\" Format=\"dd-MM-yyyy HH:mm\"><Desc>Date Time parameter</Desc></Res><Res Name=\"Type\" Value=\"\" Type=\"Text\" MaxLen=\"1\"><Desc>1 symbol for type</Desc></Res><Res Name=\"Code\" Value=\"\" Type=\"Text\" MaxLen=\"3\"><Desc>3 symbols for code</Desc></Res><Res Name=\"MessageText\" Value=\"\" Type=\"Text\" MaxLen=\"128\"><Desc>Up to 128 symbols for message text</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'L'><;><MessageNum[2]><;> <DateTime \"DD-MM-YYYY HH:MM\"> <;><Type[1]><;><Code[3]> <;><MessageText[128]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadStatus\" CmdByte=\"0x20\"><FPOperation>Provides detailed 6-byte information about the current status of the CU.</FPOperation><Response ACK=\"false\"><Res Name=\"Power_down_in_opened_fiscal_receipt\" Value=\"\" Type=\"Status\" Byte=\"0\" Bit=\"1\"><Desc>Power down in opened fiscal receipt</Desc></Res><Res Name=\"DateTime_not_set\" Value=\"\" Type=\"Status\" Byte=\"0\" Bit=\"3\"><Desc>DateTime not set</Desc></Res><Res Name=\"DateTime_wrong\" Value=\"\" Type=\"Status\" Byte=\"0\" Bit=\"4\"><Desc>DateTime wrong</Desc></Res><Res Name=\"RAM_reset\" Value=\"\" Type=\"Status\" Byte=\"0\" Bit=\"5\"><Desc>RAM reset</Desc></Res><Res Name=\"Hardware_clock_error\" Value=\"\" Type=\"Status\" Byte=\"0\" Bit=\"6\"><Desc>Hardware clock error</Desc></Res><Res Name=\"Reports_registers_Overflow\" Value=\"\" Type=\"Status\" Byte=\"1\" Bit=\"1\"><Desc>Reports registers Overflow</Desc></Res><Res Name=\"Opened_Fiscal_Receipt\" Value=\"\" Type=\"Status\" Byte=\"2\" Bit=\"1\"><Desc>Opened Fiscal Receipt</Desc></Res><Res Name=\"Receipt_Invoice_Type\" Value=\"\" Type=\"Status\" Byte=\"2\" Bit=\"2\"><Desc>Receipt Invoice Type</Desc></Res><Res Name=\"SD_card_near_full\" Value=\"\" Type=\"Status\" Byte=\"2\" Bit=\"5\"><Desc>SD card near full</Desc></Res><Res Name=\"SD_card_full\" Value=\"\" Type=\"Status\" Byte=\"2\" Bit=\"6\"><Desc>SD card full</Desc></Res><Res Name=\"CU_fiscalized\" Value=\"\" Type=\"Status\" Byte=\"3\" Bit=\"5\"><Desc>CU fiscalized</Desc></Res><Res Name=\"CU_produced\" Value=\"\" Type=\"Status\" Byte=\"3\" Bit=\"6\"><Desc>CU produced</Desc></Res><Res Name=\"Paired_with_TIMS\" Value=\"\" Type=\"Status\" Byte=\"4\" Bit=\"0\"><Desc>Paired with TIMS</Desc></Res><Res Name=\"Unsent_receipts\" Value=\"\" Type=\"Status\" Byte=\"4\" Bit=\"1\"><Desc>Unsent receipts</Desc></Res><Res Name=\"No_Sec_IC\" Value=\"\" Type=\"Status\" Byte=\"5\" Bit=\"0\"><Desc>No Sec.IC</Desc></Res><Res Name=\"No_certificates\" Value=\"\" Type=\"Status\" Byte=\"5\" Bit=\"1\"><Desc>No certificates</Desc></Res><Res Name=\"Service_jumper\" Value=\"\" Type=\"Status\" Byte=\"5\" Bit=\"2\"><Desc>Service jumper</Desc></Res><Res Name=\"Missing_SD_card\" Value=\"\" Type=\"Status\" Byte=\"5\" Bit=\"4\"><Desc>Missing SD card</Desc></Res><Res Name=\"Wrong_SD_card\" Value=\"\" Type=\"Status\" Byte=\"5\" Bit=\"5\"><Desc>Wrong SD card</Desc></Res><ResFormatRaw><![CDATA[<StatusBytes[6]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadTCP_AutoStartStatus\" CmdByte=\"0x4E\"><FPOperation>Provides information about if the TCP connection autostart when the device enter in Line/Sale mode.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"2\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'Z'><;><'2'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"2\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionTCPAutoStart\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol for TCP auto start option - '0' - No  - '1' - Yes</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'Z'><;><'2'><;><TCPAutoStart[1]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadTCP_MACAddress\" CmdByte=\"0x4E\"><FPOperation>Provides information about device's MAC address.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"6\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'T'><;><'6' > ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"6\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"MACAddress\" Value=\"\" Type=\"Text\" MaxLen=\"12\"><Desc>12 symbols for the device's MAC address</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'T'><;><'6'><;><MACAddress[12]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadTCP_Password\" CmdByte=\"0x4E\"><FPOperation>Provides information about device's TCP password.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'Z'><;><'1'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"PassLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the password length</Desc></Res><Res Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>(Password) Up to 100 symbols for the TCP password</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'Z'><;><'1'><;><PassLength[1..3]><;><Password[100]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadTCP_UsedModule\" CmdByte=\"0x4E\"><FPOperation>Provides information about which module the device is in use: LAN or WiFi module. This information can be provided if the device has mounted both modules.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"U\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'Z'><;><'U'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"U\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"OptionUsedModule\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"LAN module\" Value=\"1\" /><Option Name=\"WiFi module\" Value=\"2\" /></Options><Desc>1 symbol with value:  - '1' - LAN module  - '2' - WiFi module</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'Z'><;><'U'><;><UsedModule[1]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadTimeThreshold_Minutes\" CmdByte=\"0x4E\"><FPOperation>Read time threshold minutes</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'S'><;><'T'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"Value\" Value=\"\" Type=\"Decimal\" MaxLen=\"5\"><Desc>Up to 5 symbols for value</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'S'><;><'T'><;><Value[1..5]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadTotalMessagesCount\" CmdByte=\"0x4E\"><FPOperation>Reads all messages from log</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"L\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'L'><;><'0'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"L\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"Count\" Value=\"\" Type=\"Text\" MaxLen=\"3\"><Desc>3 symbols for the messages count</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'L'><;><'0'><;><Count[3]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadVATrates\" CmdByte=\"0x62\"><FPOperation>Provides information about the current VAT rates (the last value stored in FM).</FPOperation><Response ACK=\"false\"><Res Name=\"VATrateA\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"7\" Format=\"00.00%\"><Desc>(VAT rate A) Up to 7 symbols for VATrates of VAT class A in format ##.##%</Desc></Res><Res Name=\"VATrateB\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"7\" Format=\"00.00%\"><Desc>(VAT rate B) Up to 7 symbols for VATrates of VAT class B in format ##.##%</Desc></Res><Res Name=\"VATrateC\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"7\" Format=\"00.00%\"><Desc>(VAT rate C) Up to 7 symbols for VATrates of VAT class C in format ##.##%</Desc></Res><Res Name=\"VATrateD\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"7\" Format=\"00.00%\"><Desc>(VAT rate D) Up to 7 symbols for VATrates of VAT class D in format ##.##%</Desc></Res><Res Name=\"VATrateE\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"7\" Format=\"00.00%\"><Desc>(VAT rate E) Up to 7 symbols for VATrates of VAT class E in format ##.##%</Desc></Res><ResFormatRaw><![CDATA[<VATrateA[1..7]> <;> <VATrateB[1..7]> <;> <VATrateC[1..7]> <;> <VATrateD[1..7]> <;> <VATrateE[1..7]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadVersion\" CmdByte=\"0x21\"><FPOperation>Provides information about the device version.</FPOperation><Response ACK=\"false\"><Res Name=\"Version\" Value=\"\" Type=\"Text\" MaxLen=\"30\"><Desc>Up to 30 symbols for Version name and Check sum</Desc></Res><ResFormatRaw><![CDATA[<Version[30]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadWiFi_NetworkName\" CmdByte=\"0x4E\"><FPOperation>Provides information about WiFi network name where the device is connected.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'W'><;><'N'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"WiFiNameLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the WiFi name length</Desc></Res><Res Name=\"WiFiNetworkName\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>(Name) Up to 100 symbols for the device's WiFi network name</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'W'><;><'N'><;><WiFiNameLength[1..3]><;><WiFiNetworkName[100]>]]></ResFormatRaw></Response></Command><Command Name=\"ReadWiFi_Password\" CmdByte=\"0x4E\"><FPOperation>Providing information about WiFi password where the device is connected.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'W'><;><'P'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"PassLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the WiFi password length</Desc></Res><Res Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>Up to 100 symbols for the device's WiFi password</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'W'><;><'P'><;><PassLength[1..3]><;><Password[100]>]]></ResFormatRaw></Response></Command><Command Name=\"Read_IdleTimeout\" CmdByte=\"0x4E\"><FPOperation>Provides information about device's idle timeout. This timeout is seconds in which the connection will be closed when there is an inactivity. This information is available if the device has LAN or WiFi. Maximal value - 7200, minimal value 1. 0 is for never close the connection.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"I\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'Z'><;><'I'> ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"\" Value=\"I\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Res Name=\"IdleTimeout\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"4\" Format=\"0000\"><Desc>4 symbols for password in format ####</Desc></Res><ResFormatRaw><![CDATA[<'R'><;><'Z'><;><'I'><;><IdleTimeout[4]>]]></ResFormatRaw></Response></Command><Command Name=\"SaveNetworkSettings\" CmdByte=\"0x4E\"><FPOperation>After every change on Idle timeout, LAN/WiFi/GPRS usage, LAN/WiFi/TCP/GPRS password or TCP auto start networks settings this Save command needs to be execute.</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"A\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'P'><;><'A'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"ScanAndPrintWifiNetworks\" CmdByte=\"0x4E\"><FPOperation>Scan and print available wifi networks</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'W'><;><'S'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"ScanWiFiNetworks\" CmdByte=\"0x4E\"><FPOperation>The device scan out the list of available WiFi networks.</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'W'><;><'S'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SellPLUfromExtDB\" CmdByte=\"0x31\"><FPOperation>Register the sell (for correction use minus sign in the price field) of article with specified name, price, quantity, VAT class and/or discount/addition on the transaction.</FPOperation><Args><Arg Name=\"NamePLU\" Value=\"\" Type=\"Text\" MaxLen=\"36\"><Desc>36 symbols for article's name</Desc></Arg><Arg Name=\"OptionVATClass\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"VAT Class A\" Value=\"A\" /><Option Name=\"VAT Class B\" Value=\"B\" /><Option Name=\"VAT Class C\" Value=\"C\" /><Option Name=\"VAT Class D\" Value=\"D\" /><Option Name=\"VAT Class E\" Value=\"E\" /></Options><Desc>1 symbol for article's VAT class with optional values:\"  - 'A' - VAT Class A  - 'B' - VAT Class B  - 'C' - VAT Class C  - 'D' - VAT Class D  - 'E' - VAT Class E</Desc></Arg><Arg Name=\"Price\" Value=\"\" Type=\"Decimal\" MaxLen=\"10\"><Desc>Up to 10 symbols for article's price</Desc></Arg><Arg Name=\"MeasureUnit\" Value=\"\" Type=\"Text\" MaxLen=\"3\"><Desc>3 symbols for measure unit</Desc></Arg><Arg Name=\"HSCode\" Value=\"\" Type=\"Text\" MaxLen=\"10\"><Desc>10 symbols for HS Code in format XXXX.XX.XX</Desc></Arg><Arg Name=\"HSName\" Value=\"\" Type=\"Text\" MaxLen=\"20\"><Desc>20 symbols for HS Name</Desc></Arg><Arg Name=\"VATGrRate\" Value=\"\" Type=\"Decimal\" MaxLen=\"5\"><Desc>Up to 5 symbols for programmable VAT rate</Desc></Arg><Arg Name=\"Quantity\" Value=\"\" Type=\"Decimal\" MaxLen=\"10\"><Desc>1 to 10 symbols for quantity</Desc><Meta MinLen=\"1\" Compulsory=\"false\" ValIndicatingPresence=\"*\" /></Arg><Arg Name=\"DiscAddP\" Value=\"\" Type=\"Decimal\" MaxLen=\"7\"><Desc>1 to 7 for percentage of discount/addition</Desc><Meta MinLen=\"1\" Compulsory=\"false\" ValIndicatingPresence=\",\" /></Arg><ArgsFormatRaw><![CDATA[ <NamePLU[36]> <;> <OptionVATClass[1]> <;> <Price[1..10]> <;> <MeasureUnit[3]> <;><HSCode[10]> <;> <HSName[20]> <;> <VATGrRate[1..5]> {<'*'> <Quantity[1..10]>} {<','> <DiscAddP[1..7]>} ]]></ArgsFormatRaw></Args></Command><Command Name=\"SellPLUfromExtDB_HS\" CmdByte=\"0x31\"><FPOperation>Register the sell (for correction use minus sign in the price field) of article with specified name, price, quantity, VAT class and/or discount/addition on the transaction.</FPOperation><Args><Arg Name=\"NamePLU\" Value=\"\" Type=\"Text\" MaxLen=\"36\"><Desc>36 symbols for article's name</Desc></Arg><Arg Name=\"reservde\" Value=\" \" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"Price\" Value=\"\" Type=\"Decimal\" MaxLen=\"10\"><Desc>Up to 10 symbols for article's price</Desc></Arg><Arg Name=\"reserved\" Value=\"  \" Type=\"OptionHardcoded\" MaxLen=\"3\" /><Arg Name=\"HSCode\" Value=\"\" Type=\"Text\" MaxLen=\"10\"><Desc>10 symbols for HS Code in format XXXX.XX.XX</Desc></Arg><Arg Name=\"reserved\" Value=\"          \" Type=\"OptionHardcoded\" MaxLen=\"20\" /><Arg Name=\"reserved\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"Quantity\" Value=\"\" Type=\"Decimal\" MaxLen=\"10\"><Desc>1 to 10 symbols for quantity</Desc><Meta MinLen=\"1\" Compulsory=\"false\" ValIndicatingPresence=\"*\" /></Arg><Arg Name=\"DiscAddP\" Value=\"\" Type=\"Decimal\" MaxLen=\"7\"><Desc>1 to 7 for percentage of discount/addition</Desc><Meta MinLen=\"1\" Compulsory=\"false\" ValIndicatingPresence=\",\" /></Arg><ArgsFormatRaw><![CDATA[ <NamePLU[36]> <;> <reservde[' ']> <;> <Price[1..10]> <;> <reserved['  ']> <;><HSCode[10]> <;> <reserved['          ']> <;> <reserved['0']> {<'*'> <Quantity[1..10]>} {<','> <DiscAddP[1..7]>} ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetDateTime\" CmdByte=\"0x48\"><FPOperation>Sets the date and time and current values.</FPOperation><Args><Arg Name=\"DateTime\" Value=\"\" Type=\"DateTime\" MaxLen=\"10\" Format=\"dd-MM-yy HH:mm\"><Desc>Date Time parameter in format: DD-MM-YY HH:MM</Desc></Arg><ArgsFormatRaw><![CDATA[ <DateTime \"DD-MM-YY HH:MM\"> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetDeviceNTP_Address\" CmdByte=\"0x4E\"><FPOperation>Program device's NTP address . To apply use - SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"AddressLen\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the address length</Desc></Arg><Arg Name=\"NTPAddress\" Value=\"\" Type=\"Text\" MaxLen=\"50\"><Desc>50 symbols for the device's NTP address</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'S'><;><'N'> <;><AddressLen[1..3]><;><NTPAddress[50]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetDeviceTCP_Addresses\" CmdByte=\"0x4E\"><FPOperation>Program device's network IP address, subnet mask, gateway address, DNS address. To apply use -SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionAddressType\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"DNS address\" Value=\"5\" /><Option Name=\"Gateway address\" Value=\"4\" /><Option Name=\"IP address\" Value=\"2\" /><Option Name=\"Subnet Mask\" Value=\"3\" /></Options><Desc>1 symbol with value:  - '2' - IP address  - '3' - Subnet Mask  - '4' - Gateway address  - '5' - DNS address</Desc></Arg><Arg Name=\"DeviceAddress\" Value=\"\" Type=\"Text\" MaxLen=\"15\"><Desc>15 symbols for the selected address</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'T'><;><AddressType[1]> <;><DeviceAddress[15]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetDeviceTCP_MACAddress\" CmdByte=\"0x4E\"><FPOperation>Program device's MAC address . To apply use - SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"6\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"MACAddress\" Value=\"\" Type=\"Text\" MaxLen=\"12\"><Desc>12 symbols for the MAC address</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'T'><;><'6'> <;><MACAddress[12]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetDHCP_Enabled\" CmdByte=\"0x4E\"><FPOperation>Program device's TCP network DHCP enabled or disabled. To apply use -SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionDHCPEnabled\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"Disabled\" Value=\"0\" /><Option Name=\"Enabled\" Value=\"1\" /></Options><Desc>1 symbol with value:  - '0' - Disabled  - '1' - Enabled</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'T'><;><'1'><;><DHCPEnabled[1]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetGPRS_APN\" CmdByte=\"0x4E\"><FPOperation>Program device's GPRS APN. To apply use -SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"A\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"gprsAPNlength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the APN len</Desc></Arg><Arg Name=\"APN\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>Up to 100 symbols for the device's GPRS APN</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'G'><;><'A'><;><gprsAPNlength[1..3]><;><APN[100]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetGPRS_AuthenticationType\" CmdByte=\"0x4E\"><FPOperation>Programs GPRS APN authentication type</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionAuthenticationType\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"CHAP\" Value=\"2\" /><Option Name=\"None\" Value=\"0\" /><Option Name=\"PAP\" Value=\"1\" /><Option Name=\"PAP or CHAP\" Value=\"3\" /></Options><Desc>1 symbol with value: - '0' - None - '1' - PAP - '2' - CHAP - '3' - PAP or CHAP</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'G'><;><'N'><;><AuthenticationType[1]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetGPRS_Password\" CmdByte=\"0x4E\"><FPOperation>Program device's GPRS password. To apply use - SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"PassLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the GPRS password len</Desc></Arg><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>Up to 100 symbols for the device's GPRS password</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'G'><;><'P'><;><PassLength[1..3]><;><Password[100]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetHTTPS_Address\" CmdByte=\"0x4E\"><FPOperation>Programs server HTTPS address.</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"H\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"ParamLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for parameter length</Desc></Arg><Arg Name=\"Address\" Value=\"\" Type=\"Text\" MaxLen=\"50\"><Desc>50 symbols for address</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'S'><;><'H'><;><ParamLength[1..3]><;><Address[50]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetIdle_Timeout\" CmdByte=\"0x4E\"><FPOperation>Program device's idle timeout setting. Set timeout for closing the connection if there is an inactivity. Maximal value - 7200, minimal value 1. 0 is for never close the connection. This option can be used only if the device has LAN or WiFi. To apply use - SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"I\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"IdleTimeout\" Value=\"\" Type=\"Decimal_with_format\" MaxLen=\"4\" Format=\"0000\"><Desc>4 symbols for Idle timeout in format ####</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'Z'><;><'I'><;><IdleTimeout[4]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetInvoice_ThresholdCount\" CmdByte=\"0x4E\"><FPOperation>Programs invoice threshold count</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"I\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"Value\" Value=\"\" Type=\"Decimal\" MaxLen=\"5\"><Desc>Up to 5 symbols for value</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'S'><;><'I'><;><Value[1..5]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetPINnumber\" CmdByte=\"0x41\"><FPOperation>Stores PIN number in operative memory.</FPOperation><Args><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"6\"><Desc>6-symbols string</Desc></Arg><Arg Name=\"\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"PINnum\" Value=\"\" Type=\"Text\" MaxLen=\"11\"><Desc>11 symbols for PIN registration number</Desc></Arg><ArgsFormatRaw><![CDATA[ <Password[6]> <;> <'1'> <;> <PINnum[11]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetSerialNum\" CmdByte=\"0x40\"><FPOperation>Stores the Manufacturing number into the operative memory.</FPOperation><Args><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"6\"><Desc>6-symbols string</Desc></Arg><Arg Name=\"SerialNum\" Value=\"\" Type=\"Text\" MaxLen=\"20\"><Desc>20 symbols Manufacturing number</Desc></Arg><ArgsFormatRaw><![CDATA[ <Password[6]> <;> <SerialNum[20]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetServer_UsedComModule\" CmdByte=\"0x4E\"><FPOperation>Program device used to talk with the server . To apply use - SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"E\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionModule\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"GSM\" Value=\"0\" /><Option Name=\"LAN/WiFi\" Value=\"1\" /></Options><Desc>1 symbol with value:  - '0' - GSM  - '1' - LAN/WiFi</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'S'><;><'E'><;><Module[1]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetTCP_ActiveModule\" CmdByte=\"0x4E\"><FPOperation>Selects the active communication module - LAN or WiFi. This option can be set only if the device has both modules at the same time. To apply use - SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"U\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionUsedModule\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"LAN module\" Value=\"1\" /><Option Name=\"WiFi module\" Value=\"2\" /></Options><Desc>1 symbol with value:  - '1' - LAN module  - '2' - WiFi module</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'Z'><;><'U'><;><UsedModule[1]><;> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetTCP_AutoStart\" CmdByte=\"0x4E\"><FPOperation>Program device's autostart TCP conection in sale/line mode. To apply use -SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"2\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionTCPAutoStart\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol with value:  - '0' - No  - '1' - Yes</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'Z'><;><'2'><;><TCPAutoStart[1]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetTCP_Password\" CmdByte=\"0x4E\"><FPOperation>Program device's TCP password. To apply use - SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"Z\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"1\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"PassLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the password len</Desc></Arg><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>Up to 100 symbols for the TCP password</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'Z'><;><'1'><;><PassLength[1..3]><;><Password[100]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetTime_ThresholdMinutes\" CmdByte=\"0x4E\"><FPOperation>Programs time threshold minutes</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"S\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"Value\" Value=\"\" Type=\"Decimal\" MaxLen=\"5\"><Desc>Up to 5 symbols for value</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'S'><;><'T'><;><Value[1..5]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetWiFi_NetworkName\" CmdByte=\"0x4E\"><FPOperation>Program device's TCP WiFi network name where it will be connected. To apply use -SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"N\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"WiFiNameLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the WiFi network name len</Desc></Arg><Arg Name=\"WiFiNetworkName\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>Up to 100 symbols for the device's WiFi ssid network name</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'W'><;><'N'><;><WiFiNameLength[1..3]><;><WiFiNetworkName[100]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SetWiFi_Password\" CmdByte=\"0x4E\"><FPOperation>Program device's TCP WiFi password where it will be connected. To apply use -SaveNetworkSettings()</FPOperation><Args><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"P\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"PassLength\" Value=\"\" Type=\"Decimal\" MaxLen=\"3\"><Desc>Up to 3 symbols for the WiFi password len</Desc></Arg><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"100\"><Desc>Up to 100 symbols for the device's WiFi password</Desc></Arg><ArgsFormatRaw><![CDATA[ <'P'><;><'W'><;><'P'><;><PassLength[1..3]><;><Password[100]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"SoftwareReset\" CmdByte=\"0x3F\"><FPOperation>Restore default parameters of the device.</FPOperation><Args><Arg Name=\"Password\" Value=\"\" Type=\"Text\" MaxLen=\"6\"><Desc>6-symbols string</Desc></Arg><ArgsFormatRaw><![CDATA[ <Password[6]> ]]></ArgsFormatRaw></Args></Command><Command Name=\"StartGPRStest\" CmdByte=\"0x4E\"><FPOperation>Start GPRS test on the device the result</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"G\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'G'><;><'T'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"StartLANtest\" CmdByte=\"0x4E\"><FPOperation>Start LAN test on the device the result</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'T'><;><'T'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"StartWiFiTest\" CmdByte=\"0x4E\"><FPOperation>Start WiFi test on the device the result</FPOperation><Args><Arg Name=\"\" Value=\"R\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"W\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"\" Value=\"T\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <'R'><;><'W'><;><'T'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"StoreEJ\" CmdByte=\"0x7C\"><FPOperation>Store whole Electronic Journal report to External USB Flash memory, External SD card.</FPOperation><Args><Arg Name=\"OptionReportStorage\" Value=\"\" Type=\"Option\" MaxLen=\"2\"><Options><Option Name=\"Storage in External SD card memory\" Value=\"J4\" /><Option Name=\"Storage in External SD card memory for JSON\" Value=\"JX\" /><Option Name=\"Storage in External USB Flash memory\" Value=\"J2\" /><Option Name=\"Storage in External USB Flash memory for JSON\" Value=\"Jx\" /></Options><Desc>2 symbols for destination:  - 'J2' - Storage in External USB Flash memory  - 'J4' - Storage in External SD card memory  - 'Jx' - Storage in External USB Flash memory for JSON  - 'JX' - Storage in External SD card memory for JSON</Desc></Arg><Arg Name=\"\" Value=\"*\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><ArgsFormatRaw><![CDATA[ <OptionReportStorage[2]><;><'*'> ]]></ArgsFormatRaw></Args></Command><Command Name=\"StoreEJByDate\" CmdByte=\"0x7C\"><FPOperation>Store Electronic Journal Report from report from date to date to External USB Flash memory, External SD card.</FPOperation><Args><Arg Name=\"OptionReportStorage\" Value=\"\" Type=\"Option\" MaxLen=\"2\"><Options><Option Name=\"Storage in External SD card memory\" Value=\"J4\" /><Option Name=\"Storage in External SD card memory for JSON\" Value=\"JX\" /><Option Name=\"Storage in External USB Flash memory\" Value=\"J2\" /><Option Name=\"Storage in External USB Flash memory for JSON\" Value=\"Jx\" /></Options><Desc>2 symbols for destination:  - 'J2' - Storage in External USB Flash memory  - 'J4' - Storage in External SD card memory  - 'Jx' - Storage in External USB Flash memory for JSON  - 'JX' - Storage in External SD card memory for JSON</Desc></Arg><Arg Name=\"\" Value=\"D\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"StartRepFromDate\" Value=\"\" Type=\"DateTime\" MaxLen=\"10\" Format=\"ddMMyy\"><Desc>6 symbols for initial date in the DDMMYY format</Desc></Arg><Arg Name=\"EndRepFromDate\" Value=\"\" Type=\"DateTime\" MaxLen=\"10\" Format=\"ddMMyy\"><Desc>6 symbols for final date in the DDMMYY format</Desc></Arg><ArgsFormatRaw><![CDATA[<OptionReportStorage[2]> <;> <'D'> <;> <StartRepFromDate \"DDMMYY\"> <;> <EndRepFromDate \"DDMMYY\"> ]]></ArgsFormatRaw></Args></Command><Command Name=\"Subtotal\" CmdByte=\"0x33\"><FPOperation>Calculate the subtotal amount with printing and display visualization options. Provide information about values of the calculated amounts. If a percent or value discount/addition has been specified the subtotal and the discount/addition value will be printed regardless the parameter for printing.</FPOperation><Args><Arg Name=\"OptionPrinting\" Value=\"0\" Type=\"OptionHardcoded\" MaxLen=\"1\" /><Arg Name=\"OptionDisplay\" Value=\"\" Type=\"Option\" MaxLen=\"1\"><Options><Option Name=\"No\" Value=\"0\" /><Option Name=\"Yes\" Value=\"1\" /></Options><Desc>1 symbol with value:  - '1' - Yes  - '0' - No</Desc></Arg><Arg Name=\"DiscAddV\" Value=\"\" Type=\"Decimal\" MaxLen=\"8\"><Desc>Up to 8 symbols for the value of the discount/addition. Use minus sign '-' for discount</Desc><Meta MinLen=\"1\" Compulsory=\"false\" ValIndicatingPresence=\":\" /></Arg><Arg Name=\"DiscAddP\" Value=\"\" Type=\"Decimal\" MaxLen=\"7\"><Desc>Up to 7 symbols for the percentage value of the discount/addition. Use minus sign '-' for discount</Desc><Meta MinLen=\"1\" Compulsory=\"false\" ValIndicatingPresence=\",\" /></Arg><ArgsFormatRaw><![CDATA[ <OptionPrinting['0']> <;> <OptionDisplay[1]> {<':'> <DiscAddV[1..8]>} {<','> <DiscAddP[1..7]>} ]]></ArgsFormatRaw></Args><Response ACK=\"false\"><Res Name=\"SubtotalValue\" Value=\"\" Type=\"Decimal\" MaxLen=\"10\"><Desc>Up to 10 symbols for the value of the subtotal amount</Desc></Res><ResFormatRaw><![CDATA[<SubtotalValue[1..10]>]]></ResFormatRaw></Response></Command></Defs>";
    $this->serverSendDefs($defs);
  }
  
  }

}
?>