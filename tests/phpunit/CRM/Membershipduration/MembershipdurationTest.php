<?php

use CRM_Membershipduration_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Membership duration testing
 *
 * Tips:
 *  - With HookInterface, you may implement CiviCRM hooks directly in the test class.
 *    Simply create corresponding functions (e.g. "hook_civicrm_post(...)" or similar).
 *  - With TransactionalInterface, any data changes made by setUp() or test****() functions will
 *    rollback automatically -- as long as you don't manipulate schema or truncate tables.
 *    If this test needs to manipulate schema or truncate tables, then either:
 *       a. Do all that using setupHeadless() and Civi\Test.
 *       b. Disable TransactionalInterface, and handle all setup/teardown yourself.
 *
 * @group headless
 */
class CRM_Membershipduration_MembershipdurationTest extends \PHPUnit_Framework_TestCase {

  /*public function setUpHeadless() {
    // Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
    // See: https://docs.civicrm.org/dev/en/latest/testing/phpunit/#civitest
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }*/

  public function setUp() {
    eval(`cv php:boot`);
    parent::setUp();
  }

  public function tearDown() {
    parent::tearDown();
  }

  /**
   * Example: Test that a version is returned.
   */
  public function testWellFormedVersion() {
    $this->assertRegExp('/^([0-9\.]|alpha|beta)*$/', \CRM_Utils_System::version());
  }

  /**
   * Example: Test that we're using a fake CMS.
   */
  public function testWellFormedUF() {
    //$this->assertEquals('UnitTests', CIVICRM_UF);
  }

  /**
   * Test case when Membership duration is being created with invalid membership.
   */
  public function testCreatewithinvalidmembership() {
      try {
          $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      } catch(\Exception $e) {
          $this->assertEquals($e->getCode(), CRM_Membershipduration_BAO_MembershipDuration::$MEMBERSHIP_NOT_FOUND_ERROR_CODE);
      }
  }

  /**
   * Test case when Membership duration is being created with invalid start date.
   */
  public function testCreatewithinvalidstartdate() {
      $this->test_membership_period_params["membership_id"] = $this->test_membership_id;
      try {
          $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      } catch(\Exception $e) {
          $this->assertEquals($e->getCode(), CRM_Membershipduration_BAO_MembershipDuration::$MEMBERSHIP_INVALID_STARTDATE_ERROR_CODE);
      }
      $this->test_membership_period_params["start_date"] = "INVALID_DATE";
      try {
          $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      } catch(\Exception $e) {
          $this->assertEquals($e->getCode(), CRM_Membershipduration_BAO_MembershipDuration::$MEMBERSHIP_INVALID_STARTDATE_ERROR_CODE);
      }
  }

  /**
   * Test case when Membership duration is being created with valid start date and Membership (Minimum required values)
   */
  public function testCreatewithminimumvalues() {
      $this->test_membership_period_params["membership_id"] = $this->test_membership_id;
      $this->test_membership_period_params["start_date"] = "2017-01-01";
      $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      $this->assertObjectHasAttribute('id', $membershipPeriod);
  }

  /**
   * Test case when Membership duration is being created with Invalid contribution_id;
   */
  public function testCreatewithinvalidcontribution() {
      $this->test_membership_period_params["membership_id"] = $this->test_membership_id;
      $this->test_membership_period_params["start_date"] = "2017-01-01";
      $this->test_membership_period_params["contribution_id"] = "";

      try {
          $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      } catch(\Exception $e) {
          $this->assertEquals($e->getCode(), CRM_Membershipduration_BAO_MembershipDuration::$CONTRIBUTION_NOT_FOUND_ERROR_CODE);
      }
  }

  /**
   * Test case when Membership duration is being created with invalid end date;
   */
  public function testCreatewithinvalidenddate() {
      $this->test_membership_period_params["membership_id"] = $this->test_membership_id;
      $this->test_membership_period_params["start_date"] = "2017-01-01";
      $this->test_membership_period_params["end_date"] = "INVALID_END_DATE";

      try {
          $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      } catch(\Exception $e) {
          $this->assertEquals($e->getCode(), CRM_Membershipduration_BAO_MembershipDuration::$MEMBERSHIP_INVALID_ENDDATE_ERROR_CODE);
      }
  }

  /**
   * Test case when Membership duration is being created with start_date > end_date
   */
  public function testCreatewithigreaterstartdate() {
      $this->test_membership_period_params["membership_id"] = $this->test_membership_id;
      $this->test_membership_period_params["start_date"] = "2017-01-01";
      $this->test_membership_period_params["end_date"] = "2016-12-31";

      try {
          $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      } catch(\Exception $e) {
          $this->assertEquals($e->getCode(), CRM_Membershipduration_BAO_MembershipDuration::$MEMBERSHIP_INVALID_STARTDATE_ERROR_CODE);
      }
  }

  /**
   * Test case when Membership duration is being created with valid start_date and end_date.
   */
  public function testCreatewithivalidstartandenddate() {
      $this->test_membership_period_params["membership_id"] = $this->test_membership_id;
      $this->test_membership_period_params["start_date"] = "2017-01-01";
      $this->test_membership_period_params["end_date"] = "2018-01-01";
      $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      $this->assertObjectHasAttribute('id', $membershipPeriod);
  }

  /**
   * Test case when Membership duration is being created with all valid details.
   */
  public function testCreatewithiallvaliddetails() {
      $this->test_membership_period_params["membership_id"] = $this->test_membership_id;
      $this->test_membership_period_params["start_date"] = "2017-01-01";
      $this->test_membership_period_params["end_date"] = "2018-01-01";
      $test_contribution = \CRM_Core_DAO::createTestObject('CRM_Contribute_DAO_Contribution', array(
          "financial_type_id"=>1,
          "total_amount"=>50,
          "contact_id"=>$this->test_contact_id
      ));
      $this->test_membership_period_params["contribution_id"] = $test_contribution->id;
      $membershipPeriod = CRM_Membershipduration_BAO_MembershipDuration::create($this->test_membership_period_params);
      $this->assertObjectHasAttribute('id', $membershipPeriod);
  }

}
