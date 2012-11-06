Magento Filter URL's
====================

This extension will convert URL's like

`http://magento170.local/furniture.html?color=36`

into

`http://magento170.local/furniture/brown.html`

Mechanism
---------

The layer filter items try to load a SEO friendly URL from the database. If it not exists it will be created automatically.

An addtional router will take care of the rewrite magic for those special URL's. We use some rewrite's here to hook ourselfs
into Magento's layered navigation system. So beware of this behavior.
