# Group Custom

ownCloud application: create and manage Custom Groups.

App has been forked / patched for CNRS (French National Center for Scientific Research) use in ownCloud 9.

## Added features/changes

* users can share with custom groups they're member of
* ShareWithGroupMembersOnly admin option is managed
* shares are suppressed if owner of custom group is deleted
* allow admin to set a prefix that will be added to all future custom group names

Tested in OwnCloud 9.2

## Pre-requisites

You need clean url activated on your ownCloud instance to get proper behaviour from custom group. If not, all features will be present and functional but some display errors will happen.

## License and Author

|                      |                                          |
|:---------------------|:-----------------------------------------|
| **Author:**          | Patrick Paysant (<ppaysant@linagora.com>)
| **Copyright:**       | Copyright (c) 2016 CNRS DSI
| **License:**         | AGPL v3, see the COPYING file.

This app is a fork from
https://github.com/hjort/group_custom which is a fork of
https://github.com/sergerehem/group_custom which is a fork of
https://github.com/kadukeitor/group_custom

Many thanks to all these smart people :)

This fork is developed for an internal deployement of ownCloud at CNRS (French
National Center for Scientific Research).

If you want to be informed about this ownCloud project at CNRS, please contact
david.rousse@dsi.cnrs.fr, gilian.gambini@dsi.cnrs.fr or marc.dexet@dsi.cnrs.fr.
