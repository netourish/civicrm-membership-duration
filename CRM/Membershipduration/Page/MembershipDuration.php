<?php

class CRM_Membershipduration_Page_MembershipDuration extends CRM_Core_Page {

  /**
   * Run function
   *
   * @return return void
   */
  public function run() {
    $contactId = CRM_Utils_Request::retrieve('id', 'Positive',
        $this, FALSE, 0
    );
    $contact = civicrm_api3("Contact",'get',array(
        'id'=>$contactId,
        'sequential'=>1
    ));
    if($contact['count']==0) {
        throw new Exception(ts('Could not find membership'));
    }
    $contact = $contact['values'][0];
    CRM_Utils_System::setTitle(ts('Membership Durations of ').$contact['display_name']);

    $membershipDurations = civicrm_api3('MembershipDuration','get',array(
        'contact_id'=>$contactId,
        'sequential' => 1,
        'return' => array('start_date','end_date', 'contact_id','contribution_id','contribution_id.total_amount','contribution_id.currency'),
        'options' => array(
          'sort' => 'id DESC'
        )
    ));

    $columnHeaders = array(
        'Start Date',
        'End Date',
        'Contribution',
    );

    if($membershipDurations['count']!=0) {
          $membershipDurations = $membershipDurations['values'];
    } else {
        $membershipDurations = array();
    }

    foreach($membershipDurations as $index=>$membershipDuration) {
        if(isset($membershipDuration['contribution_id'])) {
            $contributionUrl = CRM_Utils_System::url('civicrm/contact/view/contribution',
                'reset=1&action=view&cid=' . $membershipDuration['contact_id'] . '&id=' . $membershipDuration['contribution_id']
            );
            $membershipDurations[$index]["contribution_url"] = $contributionUrl;
            $membershipDurations[$index]["total_contribution_amount"] = $membershipDuration["contribution_id.total_amount"];
            $membershipDurations[$index]["contribution_amount"] = $membershipDuration["contribution_id.currency"];
        }
    }

    $this->assign('columnHeaders', $columnHeaders);
    $this->assign('membershipDurations', $membershipDurations);
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    parent::run();
  }
}
