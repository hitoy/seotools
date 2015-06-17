#!/usr/bin/env python
"""
GET alibaba url list use yahoo
by Hito http://www.hitoy.org/
"""

import urllib2,urllib,re

class List:
    def __init__(self,key,page=1):
        key=urllib.quote(key)
        count  = (page-1)*10
        url='https://search.yahoo.com/search?p=%s&n=%s&b=%s'%(key,count,page)
        header={"Accept": "text/plain","Connection":"close","User-Agent":"Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.149 Safari/537.36","Referer":"https://www.yahoo.com/"}
        req = urllib2.Request(url,headers=header)
        try:
            page = urllib2.urlopen(req,timeout=10)
            self.content = page.read()
        except:
            self.content = ''

    def listurl(self):
        if not self.content: return False
        reurl = re.compile(r'<a\sclass="\sac-algo ac-21th"\shref=.*?RU=http%3a%2f%2f(\S+)/RK=0/.*?"',re.I|re.M)
        url=reurl.findall(self.content)
        return map(urllib.unquote,url)
