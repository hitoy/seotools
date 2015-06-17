#!/usr/bin/env python
# -*- coding:utf-8 -*-
#Author: Hito
#Blog: http://www.hitoy.org/
print "Powered By Hito http://www.hitoy.org\n"
import sys,time
from get_url import *

key = raw_input("Please Input a keyword: ").strip()
urlfile=open("./url.txt","a")


page = 1
while True:
    try:
        yahoolist=List(key,page)
        urls = "\r\n".join(yahoolist.listurl())
        print urls
        urlfile.write(urls)
        page+=1
        time.sleep(1)
    except Exception,e:
        print e
        break

urlfile.close()
