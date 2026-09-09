# Payment After Profiles

Positions the payment block as final step on selected CiviCRM contribution pages for an expected checkout flow by moving post-profile fields before the payment section by overriding `Main.tpl`.

## Configuration

Edit a contribution page and open **Include Profiles** tab. Check **Show both profiles before payment details**, then save.

Only pages using a bottom profile with the option will have a visible effect.

## Template compatibility review

The extension checks whether the installed core `Main.tpl` differs from the reviewed template. If it does, a System Status warning appears.

When the warning appears:

1. Compare the installed core template with `templates/CRM/Paymentafterprofiles/Contribute/Form/Contribution/Main.tpl` in this extension.
2. Incorporate core changes.
3. Calculate the hash of the **core template**:

   ```bash
   sha256sum /path/to/civicrm/templates/CRM/Contribute/Form/Contribution/Main.tpl
   ```

   Replace `/path/to/civicrm` with your CiviCRM core installation directory.

4. Update `$reviewedCoreHash` and `$reviewedCoreVersion` in `paymentafterprofiles.php` with the new core hash and reviewed CiviCRM version. The warning clears when the system check runs again.

Editing the extension's template alone does not require updating the hash if core has not changed.

## Use Case

Typically, you would just not include a "bottom profile" on a contribution page. To add section headers you could use formatting elements in top profile. However, the client use case is the first profile use CSS grid for a 2 column layout while the next profile (bottom) is standard. That means we need to re-arrange the order.
