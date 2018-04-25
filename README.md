# Membership Durations

An Extension for Civicrm

## Rationale

Currently, when a membership is created, edited orrenewed in CiviCRM the “end date” field on the membership itself is extended by the length of the membership but no record of the actual length of any one duration of the membership is recorded. As such it is not possible to see how many druations of membership a contact may have had.

## What is expected from this extension

Once this extension is enabled and installed and from then, on creation, updation or renewal of any membership the contact view will have a new tab for membership durations with a link to contribution records if a payment was recorded for the renewal.

## What this extension do

This extenssion hooks into either membership or membership payment posts and prepares, validates and saves the membership duration information.

## Assumptions

As the dates can be of any format, but there is some validation by Civicrm core when submiting, so only null validation done for it.

In case of Life time membership as there will be no end date so in this case the end date will be empty.

If there is a change in the membership duration during editing the membership, then as there is no contribution id posted in, so it will fetch the last contribution id which was  done for this memebrship.

Currently on renewing a membership the start date is not changed it still holds the date when the membership was started, here on renewal the start date of the membership duration will be the date on which renewal is done.

## Authors
- [Koka Prdeep Kumar]

## License
Copyright © 2018 [Koka Prdeep Kumar](https://github.com/netourish). 
Licensed under the [GNU Affero Public License 3.0](https://github.com/netourish/membershipduration/blob/master/LICENSE.txt)