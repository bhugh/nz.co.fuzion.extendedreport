<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.0                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2011                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2011
 * $Id$
 *
 */

require_once 'CRM/Report/Form.php';
require_once 'CRM/Contribute/PseudoConstant.php';
require_once 'CRM/Extendedreport/Form/Report/ExtendedReport.php';
  
class CRM_Extendedreport_Form_Report_Price_Lineitem extends CRM_Extendedreport_Form_Report_ExtendedReport {
    protected $_addressField = false;

    protected $_emailField   = false;

    protected $_summary      = null;

    protected $_customGroupExtends = array( 'Contribution' );

    protected $_baseTable = 'civicrm_line_item';

    protected $_aclTable = 'civicrm_contact';

    function __construct($child = 0 ) {
      if(empty($child)){
        // hack because we are currently using this as base for other report
        // plan is to move functions into Form.php instead & won't be required
        $this->_columns = $this->getContactColumns()
        
                        //bhugh, to include registered by contact name in report
                        + $this->getRegisteredByParticipantColumns()
                        + $this->getRegisteredByContactColumns()
                        + $this->getContactFromParticipantColumns()

                        //bhugh, to include address columns in report
                        + $this->getAddressColumns()   
                        //bhugh, 2012/09, so that relationship fields (spouse, membership contact for orgs) can be included in reoprt
                        + $this->getRelationshipColumns()            
                        + $this->getRelationshipKeyContactColumns()         
                        //to get address data for the key relationship contact (spouse/membership contact)
                        + $this->getAddressColumns(array (
                                'prefix' => 'key_relationship_contact_',
                                'prefix_label' => 'Key Relationship ',
                                'group_by' => false,
                                'order_by' => true,
                                'filters' => true,
                                'defaults' => array(
                                  'country_id' => TRUE
                                )
                             )   
                          )
                                  
                        + $this->getEventColumns()
                        + $this->getParticipantColumns()
                        + $this->getContributionColumns()
                        + $this->getPriceFieldColumns()
                        + $this->getPriceFieldValueColumns()
                        + $this->getLineItemColumns()
                        ;
      }
        parent::__construct( );
    }

    function preProcess( ) {
        parent::preProcess( );
    }

    function select( ) {
        parent::select( );
    }
   /*
    * select from clauses to use (from those advertised using
    * $this->getAvailableJoins())
    */
    function fromClauses( ) {
      return array(
        'priceFieldValue_from_lineItem',
        'priceField_from_lineItem',
        'participant_from_lineItem',
        'contribution_from_lineItem',
        //'contact_from_participant',
        'contact_from_contribution_or_participant',
        'event_from_participant',
        
        //bhugh,2012/09, to allow inclusion of address, email, phone, relationship fields
        'address_from_contact',
        'email_from_contact',
        'phone_from_contact',
        'registeredbyparticipant_from_participant',
        'registeredbycontact_from_registeredbyparticipant',
        'participant_contact_from_participant',        
       
        //bhugh, 2012/09, allow spouse & key membership contact to be imported as well
        'relationship_from_contact',
        'keycontact_from_relationship',
        'email_from_keyrelationship_contact',
        'phone_from_keyrelationship_contact',
        'address_from_keyrelationship_contact',
        
        
      );

    }
    function groupBy( ) {
       parent::groupBy();

    }

    function orderBy( ) {
       parent::orderBy();
    }

    function statistics( &$rows ) {
        return parent::statistics( $rows );
    }

    function postProcess( ) {
      parent::postProcess( );
    }

    function alterDisplay( &$rows ) {
       parent::alterDisplay($rows);

    }
}