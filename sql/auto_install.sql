-- /*******************************************************
-- *
-- * civicrm_duration
-- *
-- * This entity will store duration of all memberships.
-- *
-- *******************************************************/
CREATE TABLE IF NOT EXISTS `civicrm_membership_duration` (
     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique Duration ID',
     `contact_id` int unsigned    COMMENT 'FK to Contact',
     `membership_id` int unsigned    COMMENT 'FK to Membership',
     `contribution_id` int unsigned    COMMENT 'FK to Contribution',
     `start_date` datetime    COMMENT 'Start Date',
     `end_date` datetime    COMMENT 'End Date',
      PRIMARY KEY (`id`),
      CONSTRAINT FK_civicrm_membership_duration_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE,
      CONSTRAINT FK_civicrm_membership_duration_membership_id FOREIGN KEY (`membership_id`) REFERENCES `civicrm_membership`(`id`) ON DELETE CASCADE,
      CONSTRAINT FK_civicrm_membership_duration_contribution_id FOREIGN KEY (`contribution_id`) REFERENCES `civicrm_contribution`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
