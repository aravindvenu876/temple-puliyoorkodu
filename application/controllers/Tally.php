<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tally extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // $this->common_functions->get_common();
    }
    
    public function abc(){
        echo "dfsdf";
    }

    public function tally_example($templeId){
        $templeData = $this->db->select('*')->where('lang_id',1)->where('temple_id',$templeId)->get('temple_master_lang')->row_array();
        $this->db->select('accounting_entry.*,accounting_head.head,b.head as parent');
        $this->db->from('accounting_entry');
        $this->db->join('accounting_head','accounting_head.id = accounting_entry.account_head');
        $this->db->join('accounting_head b','b.id = accounting_head.parent_group_id');
        $this->db->where('accounting_entry.tally_status',0);
        $this->db->where('accounting_entry.status','ACTIVE');
        $this->db->where('accounting_entry.temple_id',$templeId);
        $this->db->limit(20);
        $TallyData = $this->db->get()->result();
        $requestXML = "";
        $requestXML .= "<ENVELOPE>\n";
        $requestXML .= "<HEADER>\n";
        $requestXML .= "<TALLYREQUEST>Import Data</TALLYREQUEST>\n";
        $requestXML .= "</HEADER>\n";
        $requestXML .= "<BODY>\n";
        $requestXML .= "<IMPORTDATA>\n";
        $requestXML .= "<REQUESTDESC>\n";
        $requestXML .= "<REPORTNAME>All Masters</REPORTNAME>\n";
        $requestXML .= "<STATICVARIABLES>\n";
        if($templeId == 1){
            $requestXML .= "<SVCURRENTCOMPANY>CHELAMATTAM TEMPLE</SVCURRENTCOMPANY>\n";
        }
        $requestXML .= "</STATICVARIABLES>\n";
        $requestXML .= "</REQUESTDESC>\n";
        $requestXML .= "<REQUESTDATA>\n";

        foreach($TallyData as $row){
            $this->db->select('accounting_sub_entry.*,accounting_head.head');
            $this->db->from('accounting_sub_entry');
            $this->db->join('accounting_head','accounting_head.id = accounting_sub_entry.sub_head_id');
            $this->db->where('accounting_sub_entry.entry_id',$row->id);
            $accountData = $this->db->get()->result();
            
            /**Ledger */
            $requestXML .= "<TALLYMESSAGE xmlns:UDF=\"TallyUDF\">\n";
            $requestXML .= "<LEDGER NAME=\"$row->head\" RESERVEDNAME=\"\">\n";
            $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
            $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
            $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
            $requestXML .= "<GUID></GUID>\n";
            $requestXML .= "<CURRENCYNAME>₹</CURRENCYNAME>\n";
            $requestXML .= "<PARENT>$row->parent</PARENT>\n";
            $requestXML .= "<TAXCLASSIFICATIONNAME/>\n";
            $requestXML .= "<TAXTYPE>Others</TAXTYPE>\n";
            $requestXML .= "<LEDADDLALLOCTYPE/>\n";
            $requestXML .= "<GSTTYPE/>\n";
            $requestXML .= "<APPROPRIATEFOR/>\n";
            $requestXML .= "<SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>\n";
            $requestXML .= "<EXCISELEDGERCLASSIFICATION/>\n";
            $requestXML .= "<EXCISEDUTYTYPE/>\n";
            $requestXML .= "<EXCISENATUREOFPURCHASE/>\n";
            $requestXML .= "<LEDGERFBTCATEGORY/>\n";
            $requestXML .= "<VATAPPLICABLE>&#4; Not Applicable</VATAPPLICABLE>\n";
            $requestXML .= "<ISBILLWISEON>No</ISBILLWISEON>\n";
            $requestXML .= "<ISCOSTCENTRESON>Yes</ISCOSTCENTRESON>\n";
            $requestXML .= "<ISINTERESTON>No</ISINTERESTON>\n";
            $requestXML .= "<ALLOWINMOBILE>No</ALLOWINMOBILE>\n";
            $requestXML .= "<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>\n";
            $requestXML .= "<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>\n";
            $requestXML .= "<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>\n";
            $requestXML .= "<ASORIGINAL>No</ASORIGINAL>\n";
            $requestXML .= "<ISCONDENSED>No</ISCONDENSED>\n";
            $requestXML .= "<AFFECTSSTOCK>No</AFFECTSSTOCK>\n";
            $requestXML .= "<ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>\n";
            $requestXML .= "<FORPAYROLL>No</FORPAYROLL>\n";
            $requestXML .= "<ISABCENABLED>No</ISABCENABLED>\n";
            $requestXML .= "<ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>\n";
            $requestXML .= "<INTERESTONBILLWISE>No</INTERESTONBILLWISE>\n";
            $requestXML .= "<OVERRIDEINTEREST>No</OVERRIDEINTEREST>\n";
            $requestXML .= "<OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>\n";
            $requestXML .= "<USEFORVAT>No</USEFORVAT>\n";
            $requestXML .= "<IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>\n";
            $requestXML .= "<ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>\n";
            $requestXML .= "<ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>\n";
            $requestXML .= "<ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>\n";
            $requestXML .= "<ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>\n";
            $requestXML .= "<ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>\n";
            $requestXML .= "<ISTDSEXPENSE>No</ISTDSEXPENSE>\n";
            $requestXML .= "<ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>\n";
            $requestXML .= "<ISRELATEDPARTY>No</ISRELATEDPARTY>\n";
            $requestXML .= "<USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>\n";
            $requestXML .= "<ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>\n";
            $requestXML .= "<APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>\n";
            $requestXML .= "<ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>\n";
            $requestXML .= "<INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>\n";
            $requestXML .= "<INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>\n";
            $requestXML .= "<ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>\n";
            $requestXML .= "<OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>\n";
            $requestXML .= "<ISAGAINSTFORMC>No</ISAGAINSTFORMC>\n";
            $requestXML .= "<ISCHEQUEPRINTINGENABLED>Yes</ISCHEQUEPRINTINGENABLED>\n";
            $requestXML .= "<ISPAYUPLOAD>No</ISPAYUPLOAD>\n";
            $requestXML .= "<ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>\n";
            $requestXML .= "<ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>\n";
            $requestXML .= "<ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>\n";
            $requestXML .= "<CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>\n";
            $requestXML .= "<ISTRANSPORTER>No</ISTRANSPORTER>\n";
            $requestXML .= "<USEFORNOTIONALITC>No</USEFORNOTIONALITC>\n";
            $requestXML .= "<ISECOMMOPERATOR>No</ISECOMMOPERATOR>\n";
            $requestXML .= "<SHOWINPAYSLIP>No</SHOWINPAYSLIP>\n";
            $requestXML .= "<USEFORGRATUITY>No</USEFORGRATUITY>\n";
            $requestXML .= "<ISTDSPROJECTED>No</ISTDSPROJECTED>\n";
            $requestXML .= "<FORSERVICETAX>No</FORSERVICETAX>\n";
            $requestXML .= "<ISINPUTCREDIT>No</ISINPUTCREDIT>\n";
            $requestXML .= "<ISEXEMPTED>No</ISEXEMPTED>\n";
            $requestXML .= "<ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>\n";
            $requestXML .= "<ISSTXPARTY>No</ISSTXPARTY>\n";
            $requestXML .= "<ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>\n";
            $requestXML .= "<ISUSEDFORCVD>No</ISUSEDFORCVD>\n";
            $requestXML .= "<LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>\n";
            $requestXML .= "<ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>\n";
            $requestXML .= "<ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>\n";
            $requestXML .= "<ISSEZPARTY>No</ISSEZPARTY>\n";
            $requestXML .= "<TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>\n";
            $requestXML .= "<ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>\n";
            $requestXML .= "<ISEDDSUPPORTED>No</ISEDDSUPPORTED>\n";
            $requestXML .= "<HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>\n";
            $requestXML .= "<HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>\n";
            $requestXML .= "<HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>\n";
            $requestXML .= "<HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>\n";
            $requestXML .= "<HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>\n";
            $requestXML .= "<HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>\n";
            $requestXML .= "<HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>\n";
            $requestXML .= "<HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>\n";
            $requestXML .= "<HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>\n";
            $requestXML .= "<HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>\n";
            $requestXML .= "<ISEBANKINGENABLED>No</ISEBANKINGENABLED>\n";
            $requestXML .= "<ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>\n";
            $requestXML .= "<ISBATCHENABLED>No</ISBATCHENABLED>\n";
            $requestXML .= "<ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>\n";
            $requestXML .= "<HASEDDCITY>No</HASEDDCITY>\n";
            $requestXML .= "<HASECHEQUECITY>No</HASECHEQUECITY>\n";
            $requestXML .= "<ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>\n";
            $requestXML .= "<HASCLIENTCODE>No</HASCLIENTCODE>\n";
            $requestXML .= "<PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>\n";
            $requestXML .= "<PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>\n";
            $requestXML .= "<ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>\n";
            $requestXML .= "<ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>\n";
            $requestXML .= "<ISSCBUAE>No</ISSCBUAE>\n";
            $requestXML .= "<ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>\n";
            $requestXML .= "<ISSALARYGROUPED>No</ISSALARYGROUPED>\n";
            $requestXML .= "<USEFORPURCHASETAX>No</USEFORPURCHASETAX>\n";
            $requestXML .= "<AUDITED>No</AUDITED>\n";
            $requestXML .= "<SORTPOSITION> </SORTPOSITION>\n";
            $requestXML .= "<ALTERID> </ALTERID>\n";
            $requestXML .= "<SERVICETAXDETAILS.LIST>      </SERVICETAXDETAILS.LIST>\n";
            $requestXML .= "<LBTREGNDETAILS.LIST>      </LBTREGNDETAILS.LIST>\n";
            $requestXML .= "<VATDETAILS.LIST>      </VATDETAILS.LIST>\n";
            $requestXML .= "<SALESTAXCESSDETAILS.LIST>      </SALESTAXCESSDETAILS.LIST>\n";
            $requestXML .= "<GSTDETAILS.LIST>      </GSTDETAILS.LIST>\n";
            $requestXML .= "<LANGUAGENAME.LIST>\n";
            $requestXML .= "<NAME.LIST TYPE=\"String\">\n";
            $requestXML .= "<NAME>$row->head</NAME>\n";
            $requestXML .= "</NAME.LIST>\n";
            $requestXML .= "<LANGUAGEID> 1033</LANGUAGEID>\n";
            $requestXML .= "</LANGUAGENAME.LIST>\n";
            $requestXML .= "<XBRLDETAIL.LIST>      </XBRLDETAIL.LIST>\n";
            $requestXML .= "<AUDITDETAILS.LIST>      </AUDITDETAILS.LIST>\n";
            $requestXML .= "<SCHVIDETAILS.LIST>      </SCHVIDETAILS.LIST>\n";
            $requestXML .= "<EXCISETARIFFDETAILS.LIST>      </EXCISETARIFFDETAILS.LIST>\n";
            $requestXML .= "<TCSCATEGORYDETAILS.LIST>      </TCSCATEGORYDETAILS.LIST>\n";
            $requestXML .= "<TDSCATEGORYDETAILS.LIST>      </TDSCATEGORYDETAILS.LIST>\n";
            $requestXML .= "<SLABPERIOD.LIST>      </SLABPERIOD.LIST>\n";
            $requestXML .= "<GRATUITYPERIOD.LIST>      </GRATUITYPERIOD.LIST>\n";
            $requestXML .= "<ADDITIONALCOMPUTATIONS.LIST>      </ADDITIONALCOMPUTATIONS.LIST>\n";
            $requestXML .= "<EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>\n";
            $requestXML .= "<EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>\n";
            $requestXML .= "<BANKALLOCATIONS.LIST>      </BANKALLOCATIONS.LIST>\n";
            $requestXML .= "<PAYMENTDETAILS.LIST>      </PAYMENTDETAILS.LIST>\n";
            $requestXML .= "<BANKEXPORTFORMATS.LIST>      </BANKEXPORTFORMATS.LIST>\n";
            $requestXML .= "<BILLALLOCATIONS.LIST>      </BILLALLOCATIONS.LIST>\n";
            $requestXML .= "<INTERESTCOLLECTION.LIST>      </INTERESTCOLLECTION.LIST>\n";
            $requestXML .= "<LEDGERCLOSINGVALUES.LIST>      </LEDGERCLOSINGVALUES.LIST>\n";
            $requestXML .= "<LEDGERAUDITCLASS.LIST>      </LEDGERAUDITCLASS.LIST>\n";
            $requestXML .= "<OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>\n";
            $requestXML .= "<TDSEXEMPTIONRULES.LIST>      </TDSEXEMPTIONRULES.LIST>\n";
            $requestXML .= "<DEDUCTINSAMEVCHRULES.LIST>      </DEDUCTINSAMEVCHRULES.LIST>\n";
            $requestXML .= "<LOWERDEDUCTION.LIST>      </LOWERDEDUCTION.LIST>\n";
            $requestXML .= "<STXABATEMENTDETAILS.LIST>      </STXABATEMENTDETAILS.LIST>\n";
            $requestXML .= "<LEDMULTIADDRESSLIST.LIST>      </LEDMULTIADDRESSLIST.LIST>\n";
            $requestXML .= "<STXTAXDETAILS.LIST>      </STXTAXDETAILS.LIST>\n";
            $requestXML .= "<CHEQUERANGE.LIST>      </CHEQUERANGE.LIST>\n";
            $requestXML .= "<DEFAULTVCHCHEQUEDETAILS.LIST>      </DEFAULTVCHCHEQUEDETAILS.LIST>\n";
            $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>\n";
            $requestXML .= "<AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>\n";
            $requestXML .= "<BRSIMPORTEDINFO.LIST>      </BRSIMPORTEDINFO.LIST>\n";
            $requestXML .= "<AUTOBRSCONFIGS.LIST>      </AUTOBRSCONFIGS.LIST>\n";
            $requestXML .= "<BANKURENTRIES.LIST>      </BANKURENTRIES.LIST>\n";
            $requestXML .= "<DEFAULTCHEQUEDETAILS.LIST>      </DEFAULTCHEQUEDETAILS.LIST>\n";
            $requestXML .= "<DEFAULTOPENINGCHEQUEDETAILS.LIST>      </DEFAULTOPENINGCHEQUEDETAILS.LIST>\n";
            $requestXML .= "<CANCELLEDPAYALLOCATIONS.LIST>      </CANCELLEDPAYALLOCATIONS.LIST>\n";
            $requestXML .= "<ECHEQUEPRINTLOCATION.LIST>      </ECHEQUEPRINTLOCATION.LIST>\n";
            $requestXML .= "<ECHEQUEPAYABLELOCATION.LIST>      </ECHEQUEPAYABLELOCATION.LIST>\n";
            $requestXML .= "<EDDPRINTLOCATION.LIST>      </EDDPRINTLOCATION.LIST>\n";
            $requestXML .= "<EDDPAYABLELOCATION.LIST>      </EDDPAYABLELOCATION.LIST>\n";
            $requestXML .= "<AVAILABLETRANSACTIONTYPES.LIST>      </AVAILABLETRANSACTIONTYPES.LIST>\n";
            $requestXML .= "<LEDPAYINSCONFIGS.LIST>      </LEDPAYINSCONFIGS.LIST>\n";
            $requestXML .= "<TYPECODEDETAILS.LIST>      </TYPECODEDETAILS.LIST>\n";
            $requestXML .= "<FIELDVALIDATIONDETAILS.LIST>      </FIELDVALIDATIONDETAILS.LIST>\n";
            $requestXML .= "<INPUTCRALLOCS.LIST>      </INPUTCRALLOCS.LIST>\n";
            $requestXML .= "<GSTCLASSFNIGSTRATES.LIST>      </GSTCLASSFNIGSTRATES.LIST>\n";
            $requestXML .= "<EXTARIFFDUTYHEADDETAILS.LIST>      </EXTARIFFDUTYHEADDETAILS.LIST>\n";
            $requestXML .= "<VOUCHERTYPEPRODUCTCODES.LIST>      </VOUCHERTYPEPRODUCTCODES.LIST>\n";
            $requestXML .= "</LEDGER>\n";

            /**VOUCHER */
            $requestXML .= "<VOUCHER REMOTEID=\"\" VCHKEY=\"\" VCHTYPE=\"$row->voucher_type\" ACTION=\"Create\" OBJVIEW=\"Accounting Voucher View\">\n";
            $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
            $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
            $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
            $requestXML .= "<DATE>".date('Ymd',strtotime($row->date))."</DATE>\n";
            $requestXML .= "<GUID></GUID>\n";
            $requestXML .= "<NARRATION>$row->voucher_type from $row->head</NARRATION>\n";
            $requestXML .= "<VOUCHERTYPENAME>$row->voucher_type</VOUCHERTYPENAME>\n";
            $requestXML .= "<VOUCHERNUMBER>1</VOUCHERNUMBER>\n";
            /**Need logic to enter cash or any entry */
            // $requestXML .= "<PARTYLEDGERNAME>Cash</PARTYLEDGERNAME>n";
            $requestXML .= "<CSTFORMISSUETYPE/>\n";
            $requestXML .= "<CSTFORMRECVTYPE/>\n";
            $requestXML .= "<FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>\n";
            $requestXML .= "<PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>\n";
            $requestXML .= "<VCHGSTCLASS/>\n";
            $requestXML .= "<VOUCHERTYPEORIGNAME>$row->voucher_type</VOUCHERTYPEORIGNAME>\n";
            $requestXML .= "<DIFFACTUALQTY>No</DIFFACTUALQTY>\n";
            $requestXML .= "<ISMSTFROMSYNC>No</ISMSTFROMSYNC>\n";
            $requestXML .= "<ASORIGINAL>No</ASORIGINAL>\n";
            $requestXML .= "<AUDITED>No</AUDITED>\n";
            $requestXML .= "<FORJOBCOSTING>No</FORJOBCOSTING>\n";
            $requestXML .= "<ISOPTIONAL>No</ISOPTIONAL>\n";
            $requestXML .= "<EFFECTIVEDATE>".date('Ymd',strtotime($row->date))."</EFFECTIVEDATE>\n";
            $requestXML .= "<USEFOREXCISE>No</USEFOREXCISE>\n";
            $requestXML .= "<ISFORJOBWORKIN>No</ISFORJOBWORKIN>\n";
            $requestXML .= "<ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>\n";
            $requestXML .= "<USEFORINTEREST>No</USEFORINTEREST>\n";
            $requestXML .= "<USEFORGAINLOSS>No</USEFORGAINLOSS>\n";
            $requestXML .= "<USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>\n";
            $requestXML .= "<USEFORCOMPOUND>No</USEFORCOMPOUND>\n";
            $requestXML .= "<USEFORSERVICETAX>No</USEFORSERVICETAX>\n";
            $requestXML .= "<ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>\n";
            $requestXML .= "<EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>\n";
            $requestXML .= "<USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>\n";
            $requestXML .= "<IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>\n";
            $requestXML .= "<EXCISEOPENING>No</EXCISEOPENING>\n";
            $requestXML .= "<USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>\n";
            $requestXML .= "<ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>\n";
            $requestXML .= "<ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>\n";
            $requestXML .= "<ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>\n";
            $requestXML .= "<INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>\n";
            $requestXML .= "<ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>\n";
            $requestXML .= "<ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>\n";
            $requestXML .= "<IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>\n";
            $requestXML .= "<ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>\n";
            $requestXML .= "<ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>\n";
            $requestXML .= "<ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>\n";
            $requestXML .= "<ISISDVOUCHER>No</ISISDVOUCHER>\n";
            $requestXML .= "<ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>\n";
            $requestXML .= "<ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>\n";
            $requestXML .= "<ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>\n";
            $requestXML .= "<GSTNOTEXPORTED>No</GSTNOTEXPORTED>\n";
            $requestXML .= "<IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>\n";
            $requestXML .= "<ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>\n";
            $requestXML .= "<ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>\n";
            $requestXML .= "<ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>\n";
            $requestXML .= "<ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>\n";
            $requestXML .= "<ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>\n";
            $requestXML .= "<ISCANCELLED>No</ISCANCELLED>\n";
            $requestXML .= "<HASCASHFLOW>Yes</HASCASHFLOW>\n";
            $requestXML .= "<ISPOSTDATED>No</ISPOSTDATED>\n";
            $requestXML .= "<USETRACKINGNUMBER>No</USETRACKINGNUMBER>\n";
            $requestXML .= "<ISINVOICE>No</ISINVOICE>\n";
            $requestXML .= "<MFGJOURNAL>No</MFGJOURNAL>\n";
            $requestXML .= "<HASDISCOUNTS>No</HASDISCOUNTS>\n";
            $requestXML .= "<ASPAYSLIP>No</ASPAYSLIP>\n";
            $requestXML .= "<ISCOSTCENTRE>No</ISCOSTCENTRE>\n";
            $requestXML .= "<ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>\n";
            $requestXML .= "<ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>\n";
            $requestXML .= "<ISBLANKCHEQUE>No</ISBLANKCHEQUE>\n";
            $requestXML .= "<ISVOID>No</ISVOID>\n";
            $requestXML .= "<ISONHOLD>No</ISONHOLD>\n";
            $requestXML .= "<ORDERLINESTATUS>No</ORDERLINESTATUS>\n";
            $requestXML .= "<VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>\n";
            $requestXML .= "<VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>\n";
            $requestXML .= "<ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>\n";
            $requestXML .= "<VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>\n";
            $requestXML .= "<ISVATDUTYPAID>Yes</ISVATDUTYPAID>\n";
            $requestXML .= "<ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>\n";
            $requestXML .= "<ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>\n";
            $requestXML .= "<ISDELETED>No</ISDELETED>\n";
            $requestXML .= "<CHANGEVCHMODE>No</CHANGEVCHMODE>\n";
            $requestXML .= "<ALTERID> </ALTERID>\n";
            $requestXML .= "<MASTERID> </MASTERID>\n";
            $requestXML .= "<VOUCHERKEY></VOUCHERKEY>\n";
            $requestXML .= "<EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>\n";
            $requestXML .= "<OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>\n";
            $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>\n";
            $requestXML .= "<AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>\n";
            $requestXML .= "<DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>\n";
            $requestXML .= "<SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>\n";
            $requestXML .= "<EWAYBILLDETAILS.LIST>      </EWAYBILLDETAILS.LIST>\n";
            $requestXML .= "<INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>\n";
            $requestXML .= "<INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>\n";
            $requestXML .= "<INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>\n";
            $requestXML .= "<ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>\n";
            $requestXML .= "<ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>\n";
            $requestXML .= "<INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>\n";
            foreach($accountData as $val){
                $requestXML .= "<ALLLEDGERENTRIES.LIST>\n";
                $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                $requestXML .= "<LEDGERNAME>$val->head</LEDGERNAME>\n";
                $requestXML .= "<GSTCLASS/>\n";
                if($val->type == "By"){
                    $requestXML .= "<ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>\n";
                }else{
                    $requestXML .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
                }
                $requestXML .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
                $requestXML .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
                if($val->head == "Cash"){
                    $requestXML .= "<ISPARTYLEDGER>Yes</ISPARTYLEDGER>\n";
                }else{
                    $requestXML .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
                }
                if($val->type == "By"){
                    $requestXML .= "<ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>\n";
                }else{
                    $requestXML .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
                }
                $requestXML .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
                $requestXML .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
                if($val->type == "By"){
                    $requestXML .= "<AMOUNT>-$val->debit</AMOUNT>\n";
                    $requestXML .= "<VATEXPAMOUNT>-$val->debit</VATEXPAMOUNT>\n";
                }else{
                    $requestXML .= "<AMOUNT>$val->credit</AMOUNT>\n";
                    $requestXML .= "<VATEXPAMOUNT>$val->credit</VATEXPAMOUNT>\n";
                }
                $requestXML .= "<SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>\n";
                $requestXML .= "<BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>\n";
                $requestXML .= "<BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>\n";
                $requestXML .= "<INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>\n";
                $requestXML .= "<OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>\n";
                $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>\n";
                $requestXML .= "<AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>\n";
                $requestXML .= "<INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>\n";
                $requestXML .= "<DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<RATEDETAILS.LIST>       </RATEDETAILS.LIST>\n";
                $requestXML .= "<SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>\n";
                $requestXML .= "<STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>\n";
                $requestXML .= "<EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>\n";
                $requestXML .= "<TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>\n";
                $requestXML .= "<TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>\n";
                $requestXML .= "<TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>\n";
                $requestXML .= "<VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>\n";
                $requestXML .= "<COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>\n";
                $requestXML .= "<REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>\n";
                $requestXML .= "<INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>\n";
                $requestXML .= "<VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>\n";
                $requestXML .= "<ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>\n";
                $requestXML .= "</ALLLEDGERENTRIES.LIST>\n";
            }
            $requestXML .= "<PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>\n";
            $requestXML .= "<ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>\n";
            $requestXML .= "<GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>\n";
            $requestXML .= "<GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>\n";
            $requestXML .= "<TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>\n";
            $requestXML .= "</VOUCHER>\n";
            $requestXML .= "</TALLYMESSAGE>\n"; 
            $updateDayBookData = array('tally_status' => 1);
            $this->db->where('id',$row->id)->update('accounting_entry',$updateDayBookData);
        
	}
        $requestXML .= "</REQUESTDATA>\n";  
        $requestXML .= "</IMPORTDATA>\n";  
        $requestXML .= "</BODY>\n";  
        $requestXML .= "</ENVELOPE>";
        $jobTrackerData['job'] = "Tally Import";
        $this->db->insert('_job_tracker',$jobTrackerData);
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/temple/tally_files/Tally.xml","wb");
        fwrite($fp,$requestXML);
        fclose($fp);
        
    }

}