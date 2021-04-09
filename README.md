# biz.jmaconsulting.inboundemailactivity

This extension changes the which contacts are referenced by Inbound Email activities to make them (arguably) more sensible than core CiviCRM's defaults.

Core CiviCRM Inbound Emails result in Activities with 'Added by' and 'Assigned to' set to the contact related to the From email address, and 'With Contact' (aka Target Contact) set to the email-to-activity email's related contact. 'Assigned to' is normally a staff person who is assigned to do followup work, which wouldn't be true for inquiry or response emails. 'With Contact' is normally the external stakeholder, which in this case is the From email address's contact. As the system is adding the contact because it came in the email-to-activity inbox, 'Added by' is set to that email's related contact. No contact is 'Assigned to' followup on an Inbound Email automatically, although it might be nice as a new feature to have a setting for one or more to be added.

## Requirements

* PHP v7.4+
* CiviCRM 5.36+

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl biz.jmaconsulting.inboundemailactivity@https://github.com/JMAConsulting/biz.jmaconsulting.inboundemailactivity/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/JMAConsulting/biz.jmaconsulting.inboundemailactivity.git
cv en inboundemailactivity
```

## Usage

There is no user interface for this extension. Inbound Emails received after the extension is enabled will have the new relations to contacts.

## Known Issues

None.

## License

The extension is licensed under [AGPL-3.0](LICENSE.txt).
