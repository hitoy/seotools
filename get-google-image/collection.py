#!/usr/bin/env python
# -*- coding:utf-8 -*-
import urllib2,urllib,sys,gzip,StringIO

class Collection:
    __referer = "http://www.google.com/"
    __useragent = "Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.149 Safari/537.36"

    def __init__(self,url,referer=__referer,ua=__useragent):
        self.__ua = ua
        self.__referer = referer
        self.__get_content(url)

    def __get_content(self,url):
        header = {"Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8","Accept-Encoding":"gzip, identity","Accept-Language":"en-US,en;q=0.8","Connection":"keep-alive","User-Agent":self.__ua,"Referer":self.__referer}
        try:
            req = urllib2.Request(url,headers=header)
            page = urllib2.urlopen(req,timeout=10)
            rpheader = page.info()
            body = page.read()
        except Exception,e:
            print e
            self.content=''
            return
        
        encoding = rpheader.get("Content-Encoding")
        if encoding == 'gzip':
            self.content=gz_decoding(body).strip()
        else:
            self.content=body.strip()



def gz_decoding(data):
    compressedstream = StringIO.StringIO(data)  
    gziper = gzip.GzipFile(fileobj=compressedstream)    
    data2 = gziper.read() 
    return data2   



if __name__ == "__main__":
    sys.exit()
