<?php
use CRM_Membershipduration_ExtensionUtil as E;

use Civi\Test\HeadlessInterface;

class CRM_Membershipduration_BAO_MembershipDuration extends CRM_Membershipduration_DAO_MembershipDuration {

  /**
   * Static instance to hold the membership not found error code.
   *
   * @var integer
   */
  public static $MEMBERSHIP_NOT_FOUND_ERROR_CODE = 101;

  /**
   * Static instance to hold the contribution not found error code.
   *
   * @var integer
   */
  public static $CONTRIBUTION_NOT_FOUND_ERROR_CODE = 102;

  /**
   * Static instance to hold the start date not found error code.
   *
   * @var integer
   */
  public static $MEMBERSHIP_INVALID_STARTDATE_ERROR_CODE = 103;

  /**
   * Static instance to hold the end date not found error code.
   *
   * @var integer
   */
  public static $MEMBERSHIP_INVALID_ENDDATE_ERROR_CODE = 104;

  /**
   * Create or updated MembershipDuration based on provided data.
   *
   * @param array $providedData prepared date for creation or udation.
   *
   * @return mixed
   */
  public static function create($providedData) {
    $className = 'CRM_Membershipduration_DAO_MembershipDuration';
    $entityName = 'MembershipDuration';
    $hook = empty($providedData['id']) ? 'create' : 'edit';
    $providedData["sequential"] = 1;
    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $providedData), $providedData);
    $instance = new $className();
    $instance->copyValues($providedData);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);
    return $instance;
  }

  /**
   * Prepare insertion data for membership duration.
   *
   * @param array $dataToInsert data to insert
   *
   * @return mixed mixed
   */
  public static function insertData($dataToInsert) {
    //It is may be a membership editing and seems like no change in the dates selection.
    $membershipDurationData = self::membershipDurationExistance($dataToInsert);
    if ($membershipDurationData['count'] > 0)  {
      return false;
    }

    $dataToInsert = self::prepareData($dataToInsert);

    if (!$dataToInsert) {
      return false;
    }

    return self::create($dataToInsert);
  }

  /**
   * Prepare data
   *
   * @param array $dataToInsert data to insert
   *
   * @return mixed array
   */
  private static function prepareData($dataToInsert) {
    $contributionId = self::checkContribution($dataToInsert['membership_id']);
    $dataToInsert['contribution_id'] = ($contributionId) ? $contributionId : null;

    $dataToInsert['end_date'] = isset($dataToInsert['end_date']) ? $dataToInsert['end_date'] : '';

    //Date checking
    self::dateChecking($dataToInsert);

    return $dataToInsert;
  }

  /**
   * Update contribution
   *
   * @param array $dataToUpdate date to update
   *
   * @return mixed
   */
  public static function updateContribution($dataToUpdate) {
    $membershipDurationData = self::membershipDurationExistance(array('membership_id' => $dataToUpdate['membership_id']));

    if ($membershipDurationData['count'] > 0) {
      $existingData = array_pop($membershipDurationData['values']);

      $existingData['contribution_id'] = $dataToUpdate['contribution_id'];

      return self::create($existingData);
    }

    return false;

  }

  /**
   * Check if a contribution exists for a membership
   *
   * @param integer $membershipId membership id
   *
   * @return mixed
   */
  private static function checkContribution($membershipId) {
    $membershipPaymentParams = array(
      'membership_id' => $membershipId,
      'options' => array('sort' => 'id DESC', 'limit' => 1),
    );

    $membershipPayment = self::getCoreData('MembershipPayment', 'get', $membershipPaymentParams);

    /*print"<pre>";
    print_r($membershipPayment);
    exit;*/

    return ($membershipPayment['count'] == 0) ? false : $membershipPayment['values'][0]['contribution_id'];
  }

  /**
   * Date checking
   *
   * @param array $dataToInsert Data for insertion
   *
   * @return boolean
   */
  private static function dateChecking($dataToInsert) {
    $membershipTypeParams = array(
      'id' => $dataToInsert['membership_id'],
      'options' => array('sort' => 'id DESC', 'limit' => 1),
      'return' => array('membership_type_id.duration_unit'),
    );

    $membershipTypeData = self::getCoreData('Membership', 'getsingle', $membershipTypeParams);

    if($membershipTypeData['membership_type_id.duration_unit'] != 'lifetime') {
      self::dateValidation($dataToInsert['start_date'], self::$MEMBERSHIP_INVALID_STARTDATE_ERROR_CODE);
      self::dateValidation($dataToInsert['end_date'], self::$MEMBERSHIP_INVALID_ENDDATE_ERROR_CODE);
    } else {
      self::dateValidation($dataToInsert['start_date'], self::$MEMBERSHIP_INVALID_STARTDATE_ERROR_CODE);
    }

    return true;
  }

  /**
   * If a similar membership duration exists.
   *
   * @param array $dataToInsert Data for insertion
   *
   * @return array
   */
  private static function membershipDurationExistance($dataToUpdate) {
    return self::getCoreData('MembershipDuration', 'get', $dataToUpdate);
  }

  /**
   * Common function for fetching core data
   *
   * @param string $moduleName   Module name
   * @param string $functionName Function name
   * @param array  $inputData    Input data
   *
   * @return return array
   */
  private static function getCoreData($moduleName, $functionName, array $inputData) {
    $inputData['sequential'] = 1;

    return civicrm_api3($moduleName, $functionName, $inputData);
  }

  /**
   * Date validation
   *
   * @param string  $givenDate Given date
   * @param integer $errorCode Error code
   *
   * @return return boolean
   *
   * @throws API Exception
   */
  private static function dateValidation($givenDate, $errorCode) {
    if (empty($givenDate)) {
      //  Date exception handling..
      throw new API_Exception(ts('Incorrect Date'), $errorCode);
    }

    return true;

  }

}
