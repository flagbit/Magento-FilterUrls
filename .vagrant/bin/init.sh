#!/bin/bash
# init Project

magentoVersion="magento-1.7.0.2"

checkFile="/var/www/${magentoVersion}"
sudo cat /proc/mounts |grep ${checkFile} > /dev/null

if [ $? -eq 0 ] ; then
	echo [-] ${checkFile} is already mounted
	echo [+] unmounting ${checkFile}
	sudo umount -f ${checkFile}
fi

if [ -d ${checkFile} ]; then
	echo [-] ${checkFile} exists
	echo [+] deleting ${checkFile}
	rm -r ${checkFile}
fi

mkdir ${checkFile}

checkFile="/usr/local/src/magento/ext/${magentoVersion}"
if [ -d ${checkFile} ]; then
	echo [-] ${checkFile} exists
	echo [+] deleting ${checkFile}
	rm -r ${checkFile}
fi
mkdir -p ${checkFile}

#########Install Extensions ####################################################

for i in /usr/local/src/vagrant/extensions/*.tgz; do tar xfz $i -C /usr/local/src/magento/ext/${magentoVersion}; done
sudo chmod -R 777 /usr/local/src/magento/ext/${magentoVersion}
sudo chown vagrant:www-data -R /usr/local/src/magento/ext/${magentoVersion}

checkFile="/usr/local/src/vagrant/dump/dump.tgz"
checkDir="/usr/local/src/magento/live"
if ( [ -f ${checkFile} ] && [ ! -d ${checkDir} ] ); then
	echo [-] ${checkDir} not exists
	echo [+] unpack live shop into /usr/local/src/magento/live
	mkdir /usr/local/src/magento/live
	tar xfz /usr/local/src/vagrant/dump/dump.tgz -C /usr/local/src/magento/live
	echo [+] preparing Userights
	livePath="/usr/local/src/magento/live"
	sudo chmod o+w ${livePath}/var ${livePath}/var/.htaccess ${livePath}/app/etc
	sudo chmod -R 775 ${livePath}/media ${livePath}/var
	echo [+] cleaning up Magento
	sudo chown vagrant:www-data ${livePath}/* -R
fi

checkFile="/usr/local/src/vagrant/dump/dump.sql"

if [ -f ${checkFile} ]; then
	echo [-] ${checkFile} exists

	echo [+] import database dump? \(y\|n\)
	read importFile

	if [ "$importFile" == "y" ]; then
		echo [+] import live Database

		mysql=`which mysql`

		${mysql} -u ${magentoVersion} -pvagrant1 ${magentoVersion} < ${checkFile}

	    sql="UPDATE core_config_data SET value = 'http://localhost:8080/${magentoVersion}/' WHERE path LIKE 'web/%/base_url';"
	    ${mysql} -u${magentoVersion} -pvagrant1 ${magentoVersion} -e "${sql}"

	    sql="UPDATE core_config_data SET value = '.localhost' WHERE path LIKE 'web/%/cookie_domain';"
		${mysql} -u${magentoVersion} -pvagrant1 ${magentoVersion} -e "${sql}"

	    sql="UPDATE core_config_data SET value = 'http://localhost:8080/${magentoVersion}/media/' WHERE path LIKE 'web/%/base_media_url';"
		${mysql} -u${magentoVersion} -pvagrant1 ${magentoVersion} -e "${sql}"
	fi
fi

echo [+] mapping mountpoints

sudo mount -t aufs -o br:/usr/local/src/magento/tmp/${magentoVersion}/ none /var/www/${magentoVersion}/
echo [+] /usr/local/src/magento/tmp/${magentoVersion}/ /var/www/${magentoVersion}/

sudo mount -o remount,append:/usr/local/src/magento/deployment /var/www/${magentoVersion}/
echo [+] /usr/local/src/magento/deployment/ /var/www/${magentoVersion}/

sudo mount -o remount,append:/usr/local/src/magento/versions/${magentoVersion} /var/www/${magentoVersion}/
echo [+] mount /usr/local/src/magento/versions/${magentoVersion}/ /var/www/${magentoVersion}/

sudo mount -o remount,append:/usr/local/src/magento/ext/${magentoVersion} /var/www/${magentoVersion}/
echo [+] mount /usr/local/src/magento/ext/${magentoVersion} /var/www/${magentoVersion}/

sudo ln -s /usr/share/phpmyadmin /var/www/phpmyadmin
echo [+] linking phpmyadmin to /var/www/phpmyadmin

#sudo mount -o remount,append:/usr/local/src/magento/live/ /var/www/${magentoVersion}/
#echo [+] mount /usr/local/src/magento/live/ /var/www/${magentoVersion}/




