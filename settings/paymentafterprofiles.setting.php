<?php

return [
  'paymentafterprofiles_page_ids' => [
    'name' => 'paymentafterprofiles_page_ids',
    'group_name' => 'Payment After Profiles',
    'type' => 'Array',
    'default' => [],
    'is_domain' => 1,
    'is_contact' => 0,
    'title' => ts('Contribution pages with payment after profiles', ['domain' => 'paymentafterprofiles']),
    'description' => ts('Contribution page IDs selected on the Include Profiles tab.', ['domain' => 'paymentafterprofiles']),
  ],
];
