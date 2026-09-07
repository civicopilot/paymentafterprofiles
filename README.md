# Payment After Profiles

Moves the post-profile fields before the payment section on CiviCRM contribution pages by overriding `Main.tpl`.

## Template compatibility review

The extension checks whether the installed core `Main.tpl` differs from the reviewed template. If it does, a System Status warning appears.

When the warning appears:

1. Compare the installed core template with `templates/CRM/Contribute/Form/Contribution/Main.tpl` in this extension.
2. Incorporate core changes.
3. Calculate the hash of the **core template**:

   ```bash
   sha256sum /path/to/civicrm/templates/CRM/Contribute/Form/Contribution/Main.tpl
   ```

   Replace `/path/to/civicrm` with your CiviCRM core installation directory.

5. Update `$reviewedCoreHash` and `$reviewedCoreVersion` in `paymentafterprofiles.php` with the new core hash and reviewed CiviCRM version. The warning clears when the system check runs again.

Editing the extension's template alone does not require updating the hash if core has not changed.

## Use Case

Typically, you would just not include a "bottom profile" on a contribution page. To add section headers you can use formatting element in  profile. However, the client use case is the first profile use CSS grid for a 2 column layout while the next profile (bottom) is standard. That means we need to re-arrange the order.