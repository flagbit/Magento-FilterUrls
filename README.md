Magento Filter URL's
====================

This extension will convert URL's like

`http://magento170.local/furniture.html?color=36`

into

`http://magento170.local/furniture/brown.html`

Mechanism:
---------

The layer filter items try to load a SEO friendly URL from the database. If it does not exists it will be created automatically.

An addtional router will take care of the rewrite magic for those special URLs. We use some rewrites here to hook ourselfs
into Magento's layered navigation system. So beware of this behavior.

NOTICE:
---------

To make the module create stable and strong urls please add positions to your attribute configuration since those positions are used
to order the speaking option values within the URLs. More important attributes are to be positioned first within the
attribute configuration and thus are positioned first within the resulting URLs.